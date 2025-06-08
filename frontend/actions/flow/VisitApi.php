<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\definitions\Common;
use common\definitions\Privilege;
use common\models\Msg;
use common\models\Project;
use common\models\Report;
use common\models\Visit;
//use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class VisitApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;
    private $_reportId;

    private $_project;

    private $_staffId;
    private $_user;

    public function run()
    {
        try {
            if (Yii::$app->request->method == 'POST') {
                $this->_get = Yii::$app->request->post();
            } else {
                $this->_get = Yii::$app->request->get();
            }

            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
            $this->_reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;

            $this->_staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;

            if (!empty($this->_staffId)) {
                $this->_user = \common\models\Staff::find()
                    ->where(['id' => $this->_staffId])
                    ->one();
            }

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

            $this->_project = Project::find()
                ->where(['id' => $this->_projectId])
                ->one();

            if (empty($this->_reportId)) {
                return $this->fail('需要指定报备', -1000);
            }

            $report = Report::find()
                ->where([
                    'id' => $this->_reportId,
                ])
                ->andFilterWhere([
                    'between', 'visit_time', strtotime(date('Y-m-d 00:00:00')), strtotime(date('Y-m-d 23:59:59'))
                ])
                ->andFilterWhere([
                    'report_status' => Report::REPORT_STATUS_PASS,
                ])
                ->orderBy([
                    'id' => SORT_DESC
                ])
                ->one();

            if (empty($report)) {
                return $this->fail('请做一次有效报备', -1000);
            }

            $this->valToken();
            switch ($this->action) {
                case 'add':
                    $ret = $this->add();
                    break;
                case 'update':
                    $ret = $this->update();
                    break;
                case 'confirm':
                    $ret = $this->confirm();
                    break;
                case 'get_by_id':
                    $ret = $this->getById();
                    break;
                case 'get_by_project_id':
                    $ret = $this->getByProjectId();
                    break;
                default:
                    $ret = [];
                    break;

            }
        } catch (\Exception $e) {
            $ret = $this->fail($e->getCode() . ': ' . $e->getMessage());
        }

        return $ret;
    }

    public function getById() {
        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;

        if (empty($visitId)) {
            return $this->fail('需要指定到访ID', -1000);
        }

        $model = Visit::find()
            ->where(['id' => $visitId])
            ->one();

        if (empty($model)) {
            return $this->fail('到访不存在', -1000);
        }

        return $this->success($model);
    }

    public function getByProjectId()
    {
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

        if (empty($projectId)) {
            return $this->fail('需要指定项目', -1000);
        }

        $model = Visit::find()
            ->where([
                'project_id' => $projectId,
            ])
            ->one();

        if (empty($model)) {
            return $this->fail('项目不存在', -1000);
        }

        return $this->success($model);
    }

    public function confirm() {
        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;
        $visitConfirmStatus = !empty($this->_get['visit_confirm_status']) ? $this->_get['visit_confirm_status'] : Visit::VISIT_CONFIRM_STATUS_CONFIRM;
        $visitStatusComment = !empty($this->_get['visit_status_comment']) ? $this->_get['visit_status_comment'] : '';

        Yii::$app->privilege->checkByUser($this->_user, Privilege::VISIT_CONFIRM);

        if (empty($visitId)) {
            return $this->fail('需要指定到访ID', -1000);
        }

        $model = Visit::find()
            ->where(['id' => $visitId])
            ->one();

        if (empty($model)) {
            return $this->fail('到访不存在', -1000);
        }

        $model->visit_confirm_status = $visitConfirmStatus;
        $model->visit_status_comment = $visitStatusComment;
        try {
            $ret = $model->save();
            if ($ret === false) {
                Yii::error($model->getErrors());
            }
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_CONFIRM, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, $model->guest_mobile, [
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
                'visit_confirm_status' => $visitConfirmStatus,
                'visit_status_comment' => $visitStatusComment,
            ], '用户确认到访', [
                'ret' => $ret,
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
            ]);
        } catch (\Exception $e) {
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_CONFIRM, \common\models\Log::OP_STATUS_FAILED, $this->_staffId, $model->guest_mobile, [
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
                'visit_confirm_status' => $visitConfirmStatus,
                'visit_status_comment' => $visitStatusComment,
            ], '用户确认到访', [
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);
            return $this->fail('操作失败', -1000);
        }

        if ($visitConfirmStatus == Visit::VISIT_CONFIRM_STATUS_SIGNED
         || $visitConfirmStatus == Visit::VISIT_CONFIRM_STATUS_BUY) {
            $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
            $content = [];

            if ($model->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
            || $model->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
                $jumpType = 'sub_buy_page';
            } else {
                $jumpType = 'sub_rent_page';
            }

            if (!empty($recvId)) {
                $content = [
                    'content' => '有一条新认购/签约，时间：' . date('Y-m-d H:i:s') . '，请及时处理。',
                    'title' => '新认购/签约',
                    'btn' => [
                        [
                            'label' => '确认',
                            'type'  => $jumpType,
                            'visit_id' => $visitId,
                            'report_id' => $this->_reportId,
                            'project_id' => $this->_projectId,
                        ],
                        [
                            'label' => '取消',
                            'type'  => 'visit_page',
                            'visit_id' => $visitId,
                            'report_id' => $this->_reportId,
                            'project_id' => $this->_projectId,
                        ],
                    ],
                    'visit_id' => $visitId,
                    'project_id' => $this->_projectId,
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }
        }

        Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_CONFIRM, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, $model->guest_mobile, [
            'visit_id' => $visitId,
            'project_id' => $this->_projectId,
            'report_id' => $this->_reportId,
            'visit_confirm_status' => $visitConfirmStatus,
            'visit_status_comment' => $visitStatusComment,
        ], '用户确认到访', [
            'visit_id' => $visitId,
            'project_id' => $this->_projectId,
            'report_id' => $this->_reportId,
        ]);

        return $this->success(['visit' => $model]);
    }

    public function update() {
        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;

        if (empty($visitId)) {
            return $this->fail('需要指定到访ID', -1000);
        }

        $model = Visit::find()
            ->where(['id' => $visitId])
            ->one();

        if (empty($model)) {
            return $this->fail('到访不存在', -1000);
        }

        if (!empty($this->_get['visit_status'])) {
            $model->visit_status = $this->_get['visit_status'];
        }

        if (!empty($this->_get['visit_status_comment'])) {
            $model->visit_status_comment = $this->_get['visit_status_comment'];
        }

        $model->save();

        return $this->success();
    }

    public function add() {

//        $visit = Visit::find()
//            ->where([
//                'report_id' => $this->_reportId
//            ])
//            ->one();
//
//        if (!empty($visit)) {
//            return $this->fail('该报备已存在到访记录', -1000);
//        }

        $model = new Visit();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $guestName = !empty($this->_get['guest_name']) ? $this->_get['guest_name'] : '';
            $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
            $personCt = !empty($this->_get['person_ct']) ? $this->_get['person_ct'] : 0;
            $guestAppeal = !empty($this->_get['guest_appeal']) ? $this->_get['guest_appeal'] : '';
            $budget = !empty($this->_get['budget']) ? $this->_get['budget'] : '';
            $staffMobile = !empty($this->_get['staff_mobile']) ? $this->_get['staff_mobile'] : '';
            $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
            $visitTime = !empty($this->_get['visit_time']) ? $this->_get['visit_time'] : Date('Y-m-d 00:00:00');
            $visitType = !empty($this->_get['visit_type']) ? $this->_get['visit_type'] : 0;
            $visitStatus = !empty($this->_get['visit_status']) ? $this->_get['visit_status'] : 0;
            $visitStatusComment = !empty($this->_get['visit_status_comment']) ? $this->_get['visit_status_comment'] : '';
            $reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;
            $visitCt = !empty($this->_get['visit_ct']) ? $this->_get['visit_ct'] : 0;
            $visitConfirmStatus = !empty($this->_get['visit_confirm_status']) ? $this->_get['visit_confirm_status'] : 0;


            $lastReport = Report::find()
                ->where(['project_id' => $this->_projectId])
                ->andFilterWhere(['guest_mobile' => $guestMobile])
                ->andFilterWhere([
                    '<', 'visit_time', time() - 24 * 3600
                ])
                ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
                ->orderBy('id DESC')
                ->one();

            $reportCount = Report::find()
                ->select('visit_time')
                ->where([
                    'project_id' => $this->_projectId,
                    'guest_mobile' => $guestMobile,
                ])
                ->groupBy([
                    'visit_time'
                ])
                ->count();

            $visitType = empty($reportCount) ? 0 : $reportCount + 1;

            $model->project_id = intval($this->_projectId);
            $model->report_id = intval($reportId);
            $model->guest_name = $guestName;
            $model->guest_mobile = $guestMobile;
            $model->guest_appeal = $guestAppeal;
            $model->budget = $budget;
            $model->staff_mobile = $staffMobile;
            $model->staff_id = intval($staffId);
            $model->visit_time = $visitTime;
            $model->visit_type = $visitType;
            $model->visit_status = $visitStatus;
            $model->visit_status_comment = $visitStatusComment;
            $model->visit_confirm_status = $visitConfirmStatus;
            $model->visit_ct = $visitCt;
            $model->person_ct = $personCt;

            $model->save();

            $transaction->commit();

            // 获取最新一条数据ID
            $visitId = $model->getPrimaryKey();
//            $visitId = Yii::$app->db->getLastInsertID();

            $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            if (!empty($recvId)) {
                if ($visitType > 1) {
                    $type = 'visit_repeat_confirm_page';
                } else {
                    $type = 'visit_confirm_page';
                }
                $content = [
                    'content' => '有一条新到访，客户：' . $guestName . '，时间：' . date('Y-m-d H:i:s', strtotime($visitTime)) . '，请及时处理。',
                    'report_id' => $reportId,
                    'project_id' => $this->_projectId,
                    'visit_id' => $visitId,
                    'title' => '新到访',
                    'btn' => [
                        [
                            'label' => '确认',
                            'type'  => $type,
                            'visit_id'  => $visitId,
                            'project_id' => $this->_projectId,
                            'report_id' => $reportId,
                        ]
                    ],
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }

            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_ADD, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, $guestMobile, [
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $reportId,
                'guest_name' => $guestName,
                'guest_mobile' => $guestMobile,
                'visit_time' => $visitTime,
            ], '用户添加到访', [
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $reportId,
            ]);

            return $this->success([
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $reportId,
                'model' => $model,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


}