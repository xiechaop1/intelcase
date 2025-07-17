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
use common\models\Log;
use common\models\Staff;
use common\models\Visit;
use frontend\actions\ApiAction;
use Yii;

class ReportApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;
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
//            $this->_get = Yii::$app->request->get();


            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

            $this->_project = Project::find()
                ->where(['id' => $this->_projectId])
                ->one();

            $this->_staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;

            if (!empty($this->_staffId)) {
                $this->_user = \common\models\Staff::find()
                    ->where(['id' => $this->_staffId])
                    ->one();
            }

            $this->valToken();
            switch ($this->action) {
                case 'add':
                    $ret = $this->add();
                    break;
                case 'get_by_id':
                    $ret = $this->getById();
                    break;
                case 'update':
                    $ret = $this->update();
                    break;
                case 'confirm':
                    $ret = $this->confirm();
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
        $reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;

        if (empty($reportId)) {
            return $this->fail('需要指定报备ID', -1000);
        }

        $model = Report::find()
            ->where(['id' => $reportId])
            ->one();

        $ret = $model->toArray();
        $ret['staff'] = $model->staff;
        $ret['consultant_staff'] = $model->consultantStaff;
        $ret['advisor_staff'] = $model->adviorStaff;
        $ret['project'] = $model->project;


        if (empty($ret)) {
            return $this->fail('请做一次有效报备', -1000);
        }

        return $this->success($ret);
    }

    public function confirm() {
        $reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;
        $reportStatus = !empty($this->_get['report_status']) ? $this->_get['report_status'] : Report::REPORT_STATUS_INVALID;
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

        Yii::$app->privilege->checkByUser($this->_user, Privilege::REPORT_CONFIRM);

        if (empty($reportId)) {
            return $this->fail('需要指定报备ID', -1000);
        }

        $model = Report::find()
            ->where(['id' => $reportId])
            ->one();

//        if (empty($model)) {
//            return $this->fail('请做一次有效报备', -1000);
//        }

        $model->report_status = $reportStatus;
        try {
            $model->save();

            if (!empty($msgId)) {
                Yii::$app->msg->removeBtn($msgId);
            }

//            $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
            $recvId = $model->staff_id;
            $content = [];
            $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
            if (!empty($recvId)) {
                if ($reportStatus == Report::REPORT_STATUS_PASS) {
                    $visitCount = Visit::find()
                        ->select('visit_time')
                        ->where(['project_id' => $this->_projectId])
                        ->andFilterWhere(['guest_mobile' => $model->guest_mobile])
                        ->groupBy([
                            'visit_time'
                        ])
                        ->count();

                    $visitType = empty($visitCount) ? 0 : $visitCount + 1;
                    if ($visitType > 1) {
                        $type = 'visit_repeat_page';
                    } else {
                        $type = 'visit_page';
                    }
                    $content = [
                        'content' => '有客户：' . $model->guest_name . '，项目：' . $projectName . '，手机号：' . $model->guest_mobile . '，时间：' . date('Y-m-d H:i:s', strtotime($model->visit_time)) . '，已经确认有效，请您填写到访信息。',
                        'title' => '新报备',
                        'btn' => [
                            [
                                'label' => '到访',
                                'type' => $type,
                                'report_id' => $reportId,
                                'project_id' => $this->_projectId,
                            ],
                        ],
                        'report_id' => $reportId,
                        'project_id' => $this->_projectId,
                    ];
                } else {
                    $content = [
                        'content' => '有客户：' . $model->guest_name . '，项目：' . $projectName . '，手机号：' . $model->guest_mobile . '， 时间：' . date('Y-m-d H:i:s', strtotime($model->visit_time)) . '，经确认是无效报备。',
                        'title' => '新报备',
                        'btn' => [
                        ],
                        'report_id' => $reportId,
                        'project_id' => $this->_projectId,
                    ];
                }
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }
        } catch (\Exception $e) {
            return $this->fail('操作失败', -1000);
        }

        return $this->success(['report' => $model]);
    }

    public function update() {
        $reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;

        if (empty($reportId)) {
            return $this->fail('需要指定报备ID', -1000);
        }

        $model = Report::find()
            ->where(['id' => $reportId])
            ->one();

        if (empty($model)) {
            return $this->fail('请做一次有效报备', -1000);
        }

        try {
            if (!empty($this->_get['report_status'])) {
                $model->report_status = $this->_get['report_status'];
            }

            $model->save();
        } catch (\Exception $e) {
            return $this->fail('操作失败', -1000);
        }

        return $this->success(['report' => $model]);
    }

    public function add() {

        $model = new Report();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $guestName = !empty($this->_get['guest_name']) ? $this->_get['guest_name'] : '';
            $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
            $guestChannel = !empty($this->_get['guest_channel']) ? $this->_get['guest_channel'] : '';
            $guestAppeal = !empty($this->_get['guest_appeal']) ? $this->_get['guest_appeal'] : '';
            $staffMobile = !empty($this->_get['staff_mobile']) ? $this->_get['staff_mobile'] : '';
            $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
            $visitTime = !empty($this->_get['visit_time']) ? $this->_get['visit_time'] : Date('Y-m-d H:i:s');
//            $visitTime = !empty($this->_get['created']) ? $this->_get['created'] : $visitTime;
            $visitType = !empty($this->_get['visit_type']) ? $this->_get['visit_type'] : 0;

            $tagSplit = [
                "\n", ",", "，", "/"
            ];
            foreach ($tagSplit as $t) {
                if (strpos($guestMobile, $t) !== false) {
                    $guestMobiles = explode($t, $guestMobile);
                }
            }
            if (empty($guestMobiles)) {
                $guestMobiles = [$guestMobile];
            }

            $visitCount = 0;
            foreach ($guestMobiles as $guestMobile) {
                $lastReport = Report::find()
                    ->where(['project_id' => $this->_projectId])
                    ->andFilterWhere(['like', 'guest_mobile', $guestMobile])
                    ->andFilterWhere([
                        '>', 'visit_time', time() - 24 * 3600
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

                $firstReport = Report::find()
                    ->where(['project_id' => $this->_projectId])
                    ->andFilterWhere(['like', 'guest_mobile', $guestMobile])
                    ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
                    ->orderBy('id ASC')
                    ->one();

                $vCountTmp = Visit::find()
                    ->select('visit_time')
                    ->where(['project_id' => $this->_projectId])
                    ->andFilterWhere(['like', 'guest_mobile', $guestMobile])
                    ->groupBy([
                        'visit_time'
                    ])
                    ->count();

                $visitCount += $vCountTmp;
            }

            $visitType = empty($visitCount) ? 0 : $visitCount + 1;

            if (!empty($lastReport)) {
                $reportStatus = Report::REPORT_STATUS_INVALID;
            } else {
                $reportStatus = Report::REPORT_STATUS_PASS;
            }

//            $staff = Staff::find()
//                ->where(['mobile' => $staffMobile])
//                ->one();
//
//            $staffId = !empty($staff) ? $staff->id : 0;
            $staffId = $this->_user->id;

            if ($guestAppeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                || $guestAppeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
//                $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
                $team = !empty($this->_project->advisor_team) ? $this->_project->advisor_team : '';
                $firstAdvisorId = !empty($firstReport->advisor_staff_id) ? $firstReport->advisor_staff_id : 0;
                if (empty($firstAdvisorId) || !Yii::$app->privilege->checkStaffTeam($firstAdvisorId, $team)) {
                    $randAdvisor = Yii::$app->privilege->getTeamStaff($team, Staff::STAFF_ROLE_ADVISOR);
                    $firstAdvisorId = !empty($randAdvisor) ? $randAdvisor->id : 0;
                }
            } else {
//                $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
                $team = !empty($this->_project->consultant_team) ? $this->_project->consultant_team : '';
                $firstConsultantId = !empty($firstReport->consultant_staff_id) ? $firstReport->consultant_staff_id : 0;
                if (empty($firstConsultantId) || !Yii::$app->privilege->checkStaffTeam($firstConsultantId, $team)) {
                    $randConsultant = Yii::$app->privilege->getTeamStaff($team, Staff::STAFF_ROLE_CONSULTANT);
                    $firstConsultantId = !empty($randConsultant) ? $randConsultant->id : 0;
                }
            }

            $model->project_id = $this->_projectId;
            $model->guest_name = $guestName;
            $model->guest_mobile = $guestMobile;
            $model->guest_channel = $guestChannel;
            $model->guest_appeal = $guestAppeal;
            $model->staff_mobile = $staffMobile;
            $model->staff_id = $staffId;
            $model->advisor_staff_id = !empty($firstAdvisorId) ? $firstAdvisorId : 0;
            $model->consultant_staff_id = !empty($firstConsultantId) ? $firstConsultantId : 0;
            $model->visit_time = $visitTime;
            $model->visit_type = $visitType;
            $model->report_status = $reportStatus;
            $ret = $model->save();

            $transaction->commit();
            // 获取最新一条数据ID
            $reportId = $model->getPrimaryKey();
//            $reportId = Yii::$app->db->getLastInsertID();


            $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
            if ($reportStatus == Report::REPORT_STATUS_INVALID) {
                $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
                $content = [];
                if (!empty($recvId)) {
                    $content = [
                        'content' => '有一条新报备，客户：' . $guestName . '，项目：' . $projectName . '，手机号：' . $guestMobile . '， 时间：' . date('Y-m-d H:i:s', strtotime($visitTime)) . '，系统检测无效报备，请您确认。',
                        'title' => '新报备',
                        'btn' => [
                            [
                                'label' => '确认',
                                'type'  => 'report_confirm_page',
                                'report_id' => $reportId,
                                'project_id' => $this->_projectId,
                            ],
                        ],
                        'report_id' => $reportId,
                        'project_id' => $this->_projectId,
                    ];
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                }
            } else {
                if ($visitType > 1) {
                    $type = 'visit_repeat_page';
                } else {
                    $type = 'visit_page';
                }
//                $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
                $recvId = $model->staff_id;
                $content = [];
                if (!empty($recvId)) {
                    $content = [
                        'content' => '有客户：' . $guestName . '，项目：' . $projectName . '，手机号：' . $guestMobile . '，时间：' . date('Y-m-d H:i:s', strtotime($visitTime)) . '，请及时处理。',
                        'title' => '新报备',
                        'btn' => [
                            [
                                'label' => '到访',
                                'type' => $type,
                                'report_id' => $reportId,
                                'project_id' => $this->_projectId,
                            ],
                        ],
                        'report_id' => $reportId,
                        'project_id' => $this->_projectId,
                    ];
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                }
                if ($guestAppeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                    || $guestAppeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
//                    $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
                    $recvId = $firstAdvisorId;
                } else {
//                    $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
                    $recvId = $firstConsultantId;
                }
                $content = [];
                if (!empty($recvId)) {
                    $content = [
                        'content' => '有客户：' . $guestName . '，项目：' . $projectName . '，手机号：' . $guestMobile . '，项目：' . $this->_project['project_name'] . '，时间：' . date('Y-m-d H:i:s', strtotime($visitTime)) . '',
                        'title' => '新报备',
                        'btn' => [
                        ],
                        'report_id' => $reportId,
                        'project_id' => $this->_projectId,
                    ];
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

                    $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                }
            }

            Yii::$app->oplog->write(Log::OP_CODE_REPORT_ADD, Log::OP_STATUS_SUCCESS, $this->_staffId, $guestMobile, [
                'project_id' => $this->_projectId,
                'report_id' => $reportId,
                'guest_name' => $guestName,
                'guest_mobile' => $guestMobile,
                'visit_time' => $visitTime,
                'visit_type' => $visitType,
            ], '用户报备', [
                'ret' => $ret,
                'project_id' => $this->_projectId,
                'report_id' => $reportId,
            ]);


            return $this->success([
                'report_id' => $reportId,
                'content' => $content,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->oplog->write(Log::OP_CODE_REPORT_ADD, Log::OP_STATUS_FAILED, $this->_staffId, $guestMobile, [
                'project_id' => $this->_projectId,
//                'report_id' => $reportId,
                'guest_name' => $guestName,
                'guest_mobile' => $guestMobile,
                'visit_time' => $visitTime,
                'visit_type' => $visitType,
            ], '用户报备', [
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);
            return $this->fail('操作失败', -1000);
        }

    }


}