<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\definitions\Common;
use common\models\Msg;
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

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();

            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
            $this->_reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

            $this->_project = Report::find()
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
                ->orderBy([
                    'id' => SORT_DESC
                ])
                ->one();

            if (empty($report)) {
                return $this->fail('报备不存在', -1000);
            }

            $this->valToken();
            switch ($this->action) {
                case 'add':
                    $ret = $this->add();
                    break;
                case 'update':
                    $ret = $this->update();
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

            $model->project_id = $this->_projectId;
            $model->report_id = $reportId;
            $model->guest_name = $guestName;
            $model->guest_mobile = $guestMobile;
            $model->guest_appeal = $guestAppeal;
            $model->budget = $budget;
            $model->staff_mobile = $staffMobile;
            $model->staff_id = $staffId;
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
            $visitId = Yii::$app->db->getLastInsertID();

            $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            if (!empty($recvId)) {
                $content = [
                    'content' => '有一条新到访，客户：' . $guestName . '，时间：' . date('Y-m-d H:i:s', $visitTime) . '，请及时处理。',
                    'report_id' => $reportId,
                    'project_id' => $this->_projectId,
                    'visit_id' => $visitId,
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }

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