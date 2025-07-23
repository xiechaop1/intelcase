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
use common\models\Staff;
use common\models\Subscribed;
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

    private $_report;

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

            $this->_report = Report::find()
                ->where([
                    'id' => $this->_reportId,
                ])
                ->andFilterWhere([
                    'between', 'visit_time', date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')
                ])
                ->andFilterWhere([
                    'report_status' => Report::REPORT_STATUS_PASS,
                ])
                ->orderBy([
                    'id' => SORT_DESC
                ])
                ->one();

            if (empty($this->_report)) {
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
                case 'info_confirm':
                    $ret = $this->infoConfirm();
                    break;
                case 'rechange_visit':
                    $ret = $this->rechangeVisit();
                    break;
                case 'rechange_visit_confirm':
                    $ret = $this->rechangeVisitConfirm();
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

        $ret = $model->toArray();
        $ret['project'] = $this->_project->toArray();
        $ret['report'] = $this->_report->toArray();
        $ret['staff'] = !empty($model->staff_id) ? $model->staff->toArray() : [];
        $ret['guest_mobile'] = \common\helpers\Common::formatMultyMobiles(\common\helpers\Common::splitMobile($model->guest_mobile));

        if (empty($ret)) {
            return $this->fail('到访不存在', -1000);
        }

        return $this->success($ret);
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
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

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
//        $model->visit_status_comment = $visitStatusComment;
//        $visitConfirmStatus = $model->visit_confirm_status;
        try {
            $ret = $model->save();
            if ($ret === false) {
                Yii::error($model->getErrors());
            }

            if (!empty($msgId)) {
                Yii::$app->msg->removeBtn($msgId);
            }
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_CONFIRM, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, $model->guest_mobile, [
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
                'visit_confirm_status' => $visitConfirmStatus,
//                'visit_status_comment' => $visitStatusComment,
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
//                'visit_status_comment' => $visitStatusComment,
            ], '用户确认到访', [
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);
            return $this->fail('操作失败', -1000);
        }

        if (
            $visitConfirmStatus == Visit::VISIT_CONFIRM_STATUS_SIGNED
            ||
            $visitConfirmStatus == Visit::VISIT_CONFIRM_STATUS_BUY) {

            if ($this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                || $this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
                $jumpType = 'sub_buy_page';
                $subType = Subscribed::SUB_TYPE_BUY;
                $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
//                $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
            } else {
                $jumpType = 'sub_rent_page';
                $subType = Subscribed::SUB_TYPE_RENT;
                $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
//                $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
            }

            $guestMobiles = \common\helpers\Common::splitMobile($model->guest_mobile);
            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $type = $visitConfirmStatus == Visit::VISIT_CONFIRM_STATUS_SIGNED ? '签约' : '认购';
                $content = [
                    'content' => '有一条新' . $type . '，项目：' . $projectName . '， 客户：' . $model->guest_name . ', 手机号：' . implode(',', $guestMobiles) . ', 时间：' . date('Y-m-d H:i:s') . '，请及时处理。',
                    'title' => '新' . $type,
                    'btn' => [
                        [
                            'label' => '确认',
                            'type'  => $jumpType,
                            'visit_id' => $visitId,
                            'report_id' => $this->_reportId,
                            'project_id' => $this->_projectId,
                            'sub_type' => $subType,
                            'visit_confirm_status' => $visitConfirmStatus,
                        ],
//                        [
//                            'label' => '取消',
//                            'type'  => 'visit_page',
//                            'visit_id' => $visitId,
//                            'report_id' => $this->_reportId,
//                            'project_id' => $this->_projectId,
//                            'visit_confirm_status' => $visitConfirmStatus,
//                        ],
                    ],
                    'visit_id' => $visitId,
                    'project_id' => $this->_projectId,
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

            }
        } else {
            if ($this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                || $this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
                $jumpType = 'sub_buy_page';
                $subType = Subscribed::SUB_TYPE_BUY;
                $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
//                $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
            } else {
                $jumpType = 'sub_rent_page';
                $subType = Subscribed::SUB_TYPE_RENT;
                $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
//                $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
            }

            $guestMobiles = \common\helpers\Common::splitMobile($model->guest_mobile);
            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $type = $visitConfirmStatus == Visit::VISIT_CONFIRM_STATUS_CONFIRM ? '确认' : '拒绝';
                $content = [
                    'content' => '新到访被' . $type . '，项目：' . $projectName . '， 客户：' . $model->guest_name . ', 手机号：' . implode(',', $guestMobiles) . ', 时间：' . date('Y-m-d H:i:s') . '',
                    'title' => '新到访状态变更',
                    'btn' => [

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
//            'visit_status_comment' => $visitStatusComment,
        ], '用户确认到访', [
            'visit_id' => $visitId,
            'project_id' => $this->_projectId,
            'report_id' => $this->_reportId,
        ]);

        return $this->success(['visit' => $model]);
    }

    public function rechangeVisit() {
        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;
        $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;
        $model = Visit::find()
            ->where(['id' => $visitId])
            ->one();

        if (empty($model)) {
            return $this->fail('到访不存在', -1000);
        }
        $guestMobiles = \common\helpers\Common::splitMobile($model->guest_mobile);
        $recvId = $this->_project->pm_staff_id;

        if ($this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
            || $this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
            $role = Staff::STAFF_ROLE_ADVISOR;
            $team = $this->_project->advisor_team;
        } else {
            $role = Staff::STAFF_ROLE_CONSULTANT;
            $team = $this->_project->consultant_team;
        }

        $content = [
            'content' => '有一条转接访，项目：' . $projectName . '，客户：' . $model->guest_name . ', 手机号：' . implode(',', $guestMobiles) . '，时间：' . date('Y-m-d H:i:s') . '，请及时处理。',
            'title' => '转接访确认',
            'btn' => [
                [
                    'label' => '确认',
                    'type' => 'rechange_visit_confirm_page',
                    'visit_id' => $visitId,
                    'report_id' => $this->_reportId,
                    'project_id' => $this->_projectId,
                    'role' => $role,
                    'team' => $team,
                ],
//                    [
//                        'label' => '取消',
//                        'type'  => 'visit_page',
//                        'visit_id' => $visitId,
//                        'report_id' => $this->_reportId,
//                        'project_id' => $this->_projectId,
//                    ],
            ],
            'visit_id' => $visitId,
            'project_id' => $this->_projectId,
        ];
        Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

        if (!empty($msgId)) {
            Yii::$app->msg->removeBtn($msgId);
        }

        return $this->success(['visit' => $model]);
    }

    public function rechangeVisitConfirm() {
        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;
        $role = !empty($this->_get['role']) ? $this->_get['role'] : 0;
        $consultantStaffId = !empty($this->_get['consultant_staff_id']) ? $this->_get['consultant_staff_id'] : 0;
        $advisorStaffId = !empty($this->_get['advisor_staff_id']) ? $this->_get['advisor_staff_id'] : 0;
        $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
        $model = Visit::find()
            ->where(['id' => $visitId])
            ->one();

        if (empty($model)) {
            return $this->fail('到访不存在', -1000);
        }

        if (!empty($consultantStaffId)) {
            $this->_report->consultant_staff_id = $consultantStaffId;
            $recvId = $consultantStaffId;
        }

        if (!empty($advisorStaffId)) {
            $this->_report->advisor_staff_id = $advisorStaffId;
            $recvId = $advisorStaffId;
        }

        $r = $this->_report->save();

        $visitType = $model->visit_type;
        if ($visitType > 1) {
            $type = 'visit_repeat_info_confirm_page';
        } else {
            $type = 'visit_info_confirm_page';
        }
        $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
        $guestName = !empty($model->guest_name) ? $model->guest_name : '未知客户';
        $visitTime = !empty($model->visit_time) ? $model->visit_time : date('Y-m-d H:i:s');
        $reportId = !empty($this->_report->id) ? $this->_report->id : 0;

        $content = [
            'content' => '有一条新到访，项目：' . $projectName . '，客户：' . $guestName . '，时间：' . date('Y-m-d H:i:s', strtotime($visitTime)) . '，请及时处理。',
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
                ],
                [
                    'label' => '转接访',
                    'type'  => 'rechange_visit_page',
                    'visit_id'  => $visitId,
                    'project_id' => $this->_projectId,
                    'report_id' => $reportId,
                ],
            ],
        ];
        Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

        return $this->success(['visit' => $model]);

    }

    public function infoConfirm() {
        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;
//        $visitConfirmStatus = !empty($this->_get['visit_confirm_status']) ? $this->_get['visit_confirm_status'] : Visit::VISIT_CONFIRM_STATUS_CONFIRM;
        $visitStatus = !empty($this->_get['visit_status']) ? $this->_get['visit_status'] : Visit::VISIT_STATUS_DEFAULT;
        $visitStatusComment = !empty($this->_get['visit_status_comment']) ? $this->_get['visit_status_comment'] : '';
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

        Yii::$app->privilege->checkByUser($this->_user, Privilege::VISIT_INFO_CONFIRM);

        if (empty($visitId)) {
            return $this->fail('需要指定到访ID', -1000);
        }

        $model = Visit::find()
            ->where(['id' => $visitId])
            ->one();

        if (empty($model)) {
            return $this->fail('到访不存在', -1000);
        }

//        $model->visit_confirm_status = $visitConfirmStatus;
        $model->visit_status = $visitStatus;
        $model->visit_status_comment = $visitStatusComment;
        try {
            $ret = $model->save();
            if ($ret === false) {
                Yii::error($model->getErrors());
            }

            if (!empty($msgId)) {
                Yii::$app->msg->removeBtn($msgId);
            }
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_INFO_CONFIRM, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, $model->guest_mobile, [
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
//                'visit_confirm_status' => $visitConfirmStatus,
                'visit_status_comment' => $visitStatusComment,
            ], '用户确认到访', [
                'ret' => $ret,
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
            ]);
        } catch (\Exception $e) {
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_INFO_CONFIRM, \common\models\Log::OP_STATUS_FAILED, $this->_staffId, $model->guest_mobile, [
                'visit_id' => $visitId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
//                'visit_confirm_status' => $visitConfirmStatus,
                'visit_status_comment' => $visitStatusComment,
            ], '用户确认到访', [
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);
            return $this->fail('操作失败', -1000);
        }

        $guestMobiles = \common\helpers\Common::splitMobile($model->guest_mobile);
        if ($visitStatus == Visit::VISIT_STATUS_WAIT) {
            $recvId = !empty($this->_report->staff_id) ? $this->_report->staff_id : 0;

            $visitStatusName = !empty(Visit::$visitStatus2Name[$visitStatus]) ? Visit::$visitStatus2Name[$visitStatus] : '未知';


            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $content = [
                    'content' => '未到访已经被确认，项目：' . $projectName . '，客户：' . $model->guest_name . ', 手机号：' . implode(',', $guestMobiles) . ', 状态：' . $visitStatusName . '，时间：' . date('Y-m-d H:i:s') . '',
                    'title' => '到访确认-未到访',
                    'btn' => [
                    ],
                    'visit_id' => $visitId,
                    'project_id' => $this->_projectId,
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

            }
        } else {
            $jumpType = 'visit_confirm_page';
            $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;

            $visitStatusName = !empty(Visit::$visitStatus2Name[$visitStatus]) ? Visit::$visitStatus2Name[$visitStatus] : '未知';

            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $content = [
                    'content' => '有一条待确认的到访消息，项目：' . $projectName . '，客户：' . $model->guest_name . ', 手机号：' . implode(',', $guestMobiles) . ', 状态：' . $visitStatusName . '，时间：' . date('Y-m-d H:i:s') . '，请及时处理。',
                    'title' => '到访确认',
                    'btn' => [
                        [
                            'label' => '确认',
                            'type' => 'visit_confirm_page',
                            'visit_id' => $visitId,
                            'report_id' => $this->_reportId,
                            'project_id' => $this->_projectId,
                        ],
//                    [
//                        'label' => '取消',
//                        'type'  => 'visit_page',
//                        'visit_id' => $visitId,
//                        'report_id' => $this->_reportId,
//                        'project_id' => $this->_projectId,
//                    ],
                    ],
                    'visit_id' => $visitId,
                    'project_id' => $this->_projectId,
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

            }
        }

        Yii::$app->oplog->write(\common\models\Log::OP_CODE_VISIT_INFO_CONFIRM, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, $model->guest_mobile, [
            'visit_id' => $visitId,
            'project_id' => $this->_projectId,
            'report_id' => $this->_reportId,
//            'visit_confirm_status' => $visitConfirmStatus,
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
            $visitTime = !empty($this->_get['visit_time']) ? $this->_get['visit_time'] : Date('Y-m-d H:i:s');
            $visitType = !empty($this->_get['visit_type']) ? $this->_get['visit_type'] : 0;
            $visitStatus = !empty($this->_get['visit_status']) ? $this->_get['visit_status'] : 0;
            $visitStatusComment = !empty($this->_get['visit_status_comment']) ? $this->_get['visit_status_comment'] : '';
            $location = !empty($this->_get['location']) ? $this->_get['location'] : '';
            $reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;
            $visitCt = !empty($this->_get['visit_ct']) ? $this->_get['visit_ct'] : 0;
            $visitConfirmStatus = !empty($this->_get['visit_confirm_status']) ? $this->_get['visit_confirm_status'] : 0;
            $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

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

//            if (strpos($guestMobile, "\n") !== false) {
//                $guestMobiles = explode("\n", $guestMobile);
//            } else {
//                $guestMobiles = [$guestMobile];
//            }

            $mobileTag = False;
            if (!empty($guestMobiles)) {
                foreach ($guestMobiles as $mobile) {
                    $mobile = trim($mobile);
                    $reportMobiles = $this->_report->guest_mobile;
                    if (strpos($reportMobiles, $mobile) !== false) {
                        $mobileTag = True;
                        break;
                    }
                }
            }
            if (!$mobileTag) {
                return $this->fail('请填写报备客户手机号', -1000);
            }

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

            $visitCount = Visit::find()
                ->select('visit_time')
                ->where(['project_id' => $this->_projectId])
                ->andFilterWhere(['guest_mobile' => $guestMobile])
                ->groupBy([
                    'visit_time'
                ])
                ->count();

            $visitType = empty($visitCount) ? 0 : $visitCount + 1;

//            $visitType = empty($reportCount) ? 0 : $reportCount + 1;

            $model->project_id = intval($this->_projectId);
            $model->report_id = intval($reportId);
            $model->guest_name = $guestName;
            $model->guest_mobile = $guestMobile;
            $model->guest_appeal = $guestAppeal;
            $model->budget = $budget;
            $model->location = $location;
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

            if (!empty($msgId)) {
                Yii::$app->msg->removeBtn($msgId);
            }
//            $visitId = Yii::$app->db->getLastInsertID();

            if ($this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                || $this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
                $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
//                $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
            } else {
                $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
//                $recvId = !empty($this->_project->consultant_staff_id) ? $this->_project->consultant_staff_id : 0;
            }
            if (!empty($recvId)) {
                if ($visitType > 1) {
                    $type = 'visit_repeat_info_confirm_page';
                } else {
                    $type = 'visit_info_confirm_page';
                }
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $content = [
                    'content' => '有一条新到访，项目：' . $projectName . '，客户：' . $guestName . '，时间：' . date('Y-m-d H:i:s', strtotime($visitTime)) . '，请及时处理。',
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
                        ],
                        [
                            'label' => '转接访',
                            'type'  => 'rechange_visit_page',
                            'visit_id'  => $visitId,
                            'project_id' => $this->_projectId,
                            'report_id' => $reportId,
                        ],
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