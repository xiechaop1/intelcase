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
        $ret['staff'] = $model->staff->toArray();
        if (!empty($ret['staff_name'])) {
            $ret['staff']['staff_name'] = $ret['staff_name'];
        }
        $ret['consultant_staff'] = $model->consultantStaff;
        $ret['advisor_staff'] = $model->adviorStaff;
        $ret['project'] = $model->project;
        $ret['guest_mobile'] = \common\helpers\Common::formatMultyMobiles(\common\helpers\Common::splitMobile($model->guest_mobile));
        $ret['created_at'] = Date('Y-m-d H:i:s', $ret['created_at']);

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
                $guestMobiles = \common\helpers\Common::splitMobile($model->guest_mobile);
                if ($reportStatus == Report::REPORT_STATUS_PASS) {
//                    if (!empty($model->guest_mobile)) {
//                        $tagSplit = Report::$tagSplit;
//                        foreach ($tagSplit as $tag) {
//                            if (strpos($model->guest_mobile, $tag) !== false) {
//                                $guestMobiles = explode($tag, $model->guest_mobile);
//                            }
//                        }
//                    }

                    $visitCount = 0;
                    if (!empty($guestMobiles)) {
                        foreach ($guestMobiles as $guestMobile) {

                            $visitCountTmp = Visit::find()
                                ->select('visit_time')
                                ->where(['project_id' => $this->_projectId])
                                ->andFilterWhere(['guest_mobile' => $guestMobile])
                                ->groupBy([
                                    'visit_time'
                                ])
                                ->count();

                            $visitCount += $visitCountTmp;


                        }
                    }
                    $visitType = empty($visitCount) ? 0 : $visitCount + 1;
                    if ($visitType > 1) {
                        $type = 'visit_repeat_page';
                    } else {
                        $type = 'visit_page';
                    }
                    $content = [
                        'content' => \common\helpers\Common::formatGuestInfo($projectName, $model->guest_name, $model->guest_mobile, date('Y-m-d H:i:s', strtotime($model->visit_time)), $model->guest_channel) . '，已经确认有效，请您填写到访信息。',
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
                        'content' => \common\helpers\Common::formatGuestInfo($projectName, $model->guest_name, $model->guest_mobile, date('Y-m-d H:i:s', strtotime($model->visit_time)), $model->guest_channel) . '，经确认是无效报备。',
                        'title' => '无效报备',
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
            $staffName = !empty($this->_get['staff_name']) ? $this->_get['staff_name'] : '';
            $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
            $visitTime = !empty($this->_get['visit_time']) ? $this->_get['visit_time'] : Date('Y-m-d H:i:s');
//            $visitTime = !empty($this->_get['created']) ? $this->_get['created'] : $visitTime;
            $visitType = !empty($this->_get['visit_type']) ? $this->_get['visit_type'] : 0;

//            $tagSplit = [
//                "\n", ",", "，", "/"
//            ];
            $tagSplit = \common\helpers\Common::$tagSplit;
            foreach ($tagSplit as $t) {
                if (strpos($guestMobile, $t) !== false) {
                    $guestMobiles = explode($t, $guestMobile);
                }
            }
            if (empty($guestMobiles)) {
                $guestMobiles = [$guestMobile];
            }

            $visitCount = 0;
            $reportStatus = Report::REPORT_STATUS_PASS;
            foreach ($guestMobiles as $tmpMobile) {
                if ($reportStatus == Report::REPORT_STATUS_PASS) {

                    // 如果目前报备属于有效报备
                    // 先找到近一个月内非本人报备
                    $reportList = Report::find()
                        ->where(['project_id' => $this->_projectId])
                        ->andFilterWhere(['like', 'guest_mobile', $tmpMobile])
                        ->andFilterWhere(['<>', 'staff_mobile', $staffMobile])
                        ->andFilterWhere([
                            '>', 'visit_time', time() - 30 * 24 * 3600
                        ])
                        ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
                        ->orderBy('id DESC')
                        ->all();

                    // 如果存在非本人报备
                    if (!empty($reportList)) {
                        foreach ($reportList as $report) {
                            // 先判断，如果是当天有人报备（无论到不到访），均无效报备
                            if ($report->visit_time > time() - 24 * 3600) {
                                $reportStatus = Report::REPORT_STATUS_INVALID;
                                break;
                            }
                            // 当天没有，如果之前有报备且到访，则无效（一个月内）
                            $checkVisit = Visit::find()
                                ->where(['report_id' => $report->id])
                                ->andFilterWhere([
                                    'visit_status' => Visit::VISIT_STATUS_COMPLETED
                                ])
                                ->orderBy(['id' => SORT_DESC])
                                ->one();

                            if (!empty($checkVisit)) {
                                $reportStatus = Report::REPORT_STATUS_INVALID;
                                break;
                            }
                        }
                    }


//                    $checkVisits = Visit::find()
//                        ->where(['project_id' => $this->_projectId])
//                        ->andFilterWhere(['like', 'guest_mobile', $tmpMobile])
//                        ->andFilterWhere([
//                            '>', 'visit_time', time() - 24 * 3600 * 30
//                        ])
////                        ->andFilterWhere(['visit_status' => Visit::VISIT_STATUS_COMPLETED])
//                        ->orderBy('id DESC')
//                        ->limit(30)
//                        ->all();
//
//                    if (!empty($checkVisits)) {
//                        foreach ($checkVisits as $checkVisit) {
//                            if ($checkVisit->visit_status == Visit::VISIT_STATUS_COMPLETED) {
//                                if (!empty($checkVisit->report)) {
//                                    if ($checkVisit->report->staff_mobile != $staffMobile) {
//                                        $reportStatus = Report::REPORT_STATUS_INVALID;
//                                        break;
//                                    }
//                                }
//                            } else {
//                                if (!empty($checkVisit->report)) {
//                                    if ($checkVisit->report->staff_mobile != $staffMobile
//                                    && $checkVisit->report->visit_time > (time() - 24 * 3600)
//                                    ) {
//                                        $reportStatus = Report::REPORT_STATUS_INVALID;
//                                        break;
//                                    }
//                                }
//                            }
//                        }
//                    } else {
//                        $lastReport = Report::find()
//                            ->where(['project_id' => $this->_projectId])
//                            ->andFilterWhere(['like', 'guest_mobile', $tmpMobile])
//                            ->andFilterWhere(['<>', 'staff_mobile', $staffMobile])
//                            ->andFilterWhere([
//                                '>', 'visit_time', time() - 24 * 3600
//                            ])
//                            ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
//                            ->orderBy('id DESC')
//                            ->one();
//
//                        if (!empty($lastReport)) {
//                            $reportStatus = Report::REPORT_STATUS_INVALID;
//                        }
//                    }
                }
//                if (empty($lastReport)) {
//                    $lastReport = Report::find()
//                        ->where(['project_id' => $this->_projectId])
//                        ->andFilterWhere(['like', 'guest_mobile', $tmpMobile])
//                        ->andFilterWhere(['<>', 'staff_mobile', $staffMobile])
//                        ->andFilterWhere([
//                            '>', 'visit_time', time() - 24 * 3600
//                        ])
//                        ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
//                        ->orderBy('id DESC')
//                        ->one();

//                    $checkReport = Report::find()
//                        ->where(['project_id' => $this->_projectId])
//                        ->andFilterWhere(['like', 'guest_mobile', $tmpMobile])
//                        ->andFilterWhere([
//                            '>', 'visit_time', time() - 24 * 3600 * 30
//                        ])
//                        ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
//                        ->orderBy('id DESC')
//                        ->one();


//                }

                $firstReport = Report::find()
                    ->where(['project_id' => $this->_projectId])
                    ->andFilterWhere(['like', 'guest_mobile', $tmpMobile])
                    ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
                    ->orderBy('id ASC')
                    ->one();

                $vCountTmp = Visit::find()
                    ->select('visit_time')
                    ->where(['project_id' => $this->_projectId])
                    ->andFilterWhere(['like', 'guest_mobile', $tmpMobile])
                    ->groupBy([
                        'visit_time'
                    ])
                    ->count();

                $visitCount += $vCountTmp;
            }

            $visitType = empty($visitCount) ? 0 : $visitCount + 1;

//            if (!empty($lastReport)) {
//                $reportStatus = Report::REPORT_STATUS_INVALID;
//            } else {
//                $reportStatus = Report::REPORT_STATUS_PASS;
//            }

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
                if ( !empty($this->_user) && in_array($this->_user->role, [Staff::STAFF_ROLE_CONSULTANT, Staff::STAFF_ROLE_ADVISOR])) {
                    $firstAdvisorId = $randAdvisor = $staffId;
                }
//                else {
//                    if (empty($firstAdvisorId) || !Yii::$app->privilege->checkStaffTeam($firstAdvisorId, $team)) {
//                        $randAdvisor = Yii::$app->privilege->getTeamStaff($team, Staff::STAFF_ROLE_ADVISOR);
//                        $firstAdvisorId = !empty($randAdvisor) ? $randAdvisor->id : 0;
//                    }
//                }
            } else {
//                $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
                $team = !empty($this->_project->consultant_team) ? $this->_project->consultant_team : '';
                $firstConsultantId = !empty($firstReport->consultant_staff_id) ? $firstReport->consultant_staff_id : 0;
                if ( !empty($this->_user) && in_array($this->_user->role, [Staff::STAFF_ROLE_CONSULTANT, Staff::STAFF_ROLE_ADVISOR])) {
                    $firstConsultantId = $randConsultant = $staffId;
                }
//                else {
//                    if (empty($firstConsultantId) || !Yii::$app->privilege->checkStaffTeam($firstConsultantId, $team)) {
//                        $randConsultant = Yii::$app->privilege->getTeamStaff($team, Staff::STAFF_ROLE_CONSULTANT);
//                        $firstConsultantId = !empty($randConsultant) ? $randConsultant->id : 0;
//                    }
//                }
            }

            $model->project_id = $this->_projectId;
            $model->guest_name = $guestName;
            $model->guest_mobile = $guestMobile;
            $model->guest_channel = $guestChannel;
            $model->guest_appeal = $guestAppeal;
            $model->staff_mobile = $staffMobile;
            $model->staff_name = $staffName;
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
                        'content' => '有一条新报备，' . \common\helpers\Common::formatGuestInfo($projectName, $model->guest_name, $model->guest_mobile, date('Y-m-d H:i:s', strtotime($model->visit_time)), $model->guest_channel) . '，系统检测无效报备，请您确认。',
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
                        'content' => '新报备' . \common\helpers\Common::formatGuestInfo($projectName, $model->guest_name, $model->guest_mobile, date('Y-m-d H:i:s', strtotime($model->visit_time)), $model->guest_channel) . '，请及时处理。',
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
//                if ($guestAppeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
//                    || $guestAppeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
////                    $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
//                    $recvId = $firstAdvisorId;
//                } else {
////                    $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
//                    $recvId = $firstConsultantId;
//                }
//                $content = [];
//                if (!empty($recvId)) {
//                    $content = [
//                        'content' => '新报备' . \common\helpers\Common::formatGuestInfo($projectName, $model->guest_name, $model->guest_mobile, date('Y-m-d H:i:s', strtotime($model->visit_time)), $model->guest_channel),
//                        'title' => '新报备',
//                        'btn' => [
//                        ],
//                        'report_id' => $reportId,
//                        'project_id' => $this->_projectId,
//                    ];
//                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
//
                    $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
                $content = [
                    'content' => '新报备' . \common\helpers\Common::formatGuestInfo($projectName, $model->guest_name, $model->guest_mobile, date('Y-m-d H:i:s', strtotime($model->visit_time)), $model->guest_channel) . '，请及时处理。',
                    'title' => '新报备',
                    'report_id' => $reportId,
                    'project_id' => $this->_projectId,
                ];
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
//                }
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