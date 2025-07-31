<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\definitions\Privilege;
use common\helpers\Common;
use common\models\Msg;
use common\models\Payment;
use common\models\Project;
use common\models\Report;
//use common\services\Log;
use common\models\Subscribed;
use common\models\Visit;
use frontend\actions\ApiAction;
use Mpdf\Tag\Sub;
use Yii;

class SubscribedApi extends ApiAction
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

            if ($this->action != "confirm_deal") {

                if (empty($this->_reportId)) {
                    return $this->fail('需要指定报备', -1000);
                }

                $beginTime = Date('Y-m-d 00:00:00', strtotime('-1year'));
                $this->_report = Report::find()
                    ->where([
                        'id' => $this->_reportId,
                    ])
                    ->andFilterWhere([
                        'between', 'visit_time', $beginTime, date('Y-m-d 23:59:59')
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
            }

            $this->valToken();
            switch ($this->action) {
                case 'add':
                    $ret = $this->add();
                    break;
                case 'update':
                    $ret = $this->update();
                    break;
                case 'confirm_info':
                    $ret = $this->confirmInfo();
                    break;
                case 'confirm_sign':
                    $ret = $this->confirmSign();
                    break;
                case 'confirm_deal':
                    $ret = $this->confirmDeal();
                    break;
                case 'get_by_project_id':
                    $ret = $this->getByProjectId();
                    break;
                case 'get_by_id':
                    $ret = $this->getById();
                    break;
                case 'get_with_payments_by_room_no':
                    $ret = $this->getWithPaymentsByRoomNo();
                    break;
                case 'get_with_payments_by_id':
                    $ret = $this->getWithPaymentsById();
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

    public function confirmInfo() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;
        $subStatus = !empty($this->_get['sub_status']) ? $this->_get['sub_status'] : Subscribed::SUBSCRIBED_STATUS_CONFIRM;
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;
        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;

        if ($this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
            || $this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
            $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
            $jumpType = 'sub_buy_page';
        } else {
            $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
            $jumpType = 'sub_rent_page';
        }

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        if (empty($model)) {
            return $this->fail('认购不存在', -1000);
        }

        $model->sub_status = $subStatus;
        $ret = $model->save();

        if (!empty($msgId)) {
            Yii::$app->msg->removeBtn($msgId);
        }

        $subGuest = !empty($model->sub_guest) ? $model->sub_guest : '';

        if ($subStatus == Subscribed::SUBSCRIBED_STATUS_CONFIRM) {

            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $content = [
                    'content' => '有一条新认购，项目：' . $projectName . '，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                    'project_id' => $this->_projectId,
                    'title' => '新认购',
                    'btn' => [
                        [
                            'label' => '支付',
                            'type' => 'payment_page',
                            'sub_id' => $subId,
                            'project_id' => $this->_projectId,
                            'report_id' => $this->_reportId,
                            'visit_id' => $visitId,
                        ]
                    ],
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

                $pmRecvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
                $content = [
                    'content' => '有一条新认购，项目：' . $projectName . '，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                    'project_id' => $this->_projectId,
                    'title' => '新认购',
                    'btn' => [
                    ],
                ];
                Yii::$app->msg->add($pmRecvId, $content, Msg::MSG_SENDER_SYSTEM);
            }
        } else {
            $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';



            $content = [
                'content' => '您提交的认购被拒绝，项目：' . $projectName . '，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                'project_id' => $this->_projectId,
                'title' => '认购被拒绝',
                'btn' => [
                    [
                        'label' => '修改',
                        'type' => $jumpType,
                        'sub_id' => $subId,
                        'project_id' => $this->_projectId,
                        'report_id' => $this->_reportId,
                        'visit_id' => $visitId,
                    ]
                ],
            ];
            Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

            $pmRecvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            $content = [
                'content' => '认购被拒绝，项目：' . $projectName . '，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                'project_id' => $this->_projectId,
                'title' => '认购被拒绝',
                'btn' => [
                ],
            ];
            Yii::$app->msg->add($pmRecvId, $content, Msg::MSG_SENDER_SYSTEM);
        }

        Yii::$app->oplog->write(
            \common\models\Log::OP_CODE_SUB_CONFIRM_DEAL,
            \common\models\Log::OP_STATUS_SUCCESS,
            $this->_staffId,
            $model->mobile,
            $model->getAttributes(),
            '确认认购信息',
            [
                'sub_id' => $model->getPrimaryKey()
            ]
        );

        return $this->success();

    }

    public function confirmDeal() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;
        $subStatus = !empty($this->_get['sub_status']) ? $this->_get['sub_status'] : Subscribed::SUBSCRIBED_STATUS_CONFIRM;
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

        if (empty($subId)) {
            return $this->fail('需要指定认购ID', -1000);
        }

        Yii::$app->privilege->checkByUser($this->_user, Privilege::SUB_CONFIRM_DEAL);

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        if (empty($model)) {
            return $this->fail('认购不存在', -1000);
        }

        $model->sub_status = $subStatus;
        $ret = $model->save();
        if ($ret === false) {
            Yii::error($model->getErrors());
            return $this->fail('操作失败', -1000);
        }
        if (!empty($msgId)) {
            Yii::$app->msg->removeBtn($msgId);
        }

        if ($subStatus == Subscribed::SUBSCRIBED_STATUS_CONFIRM) {
            $recvId = !empty($this->_project->financial_staff_id) ? $this->_project->financial_staff_id : 0;
            $pmRecvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $content = [
                    'content' => '有一条新认购，项目：' . $projectName . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                    'sub_id' => $subId,
                    'title' => '新认购',
                    'btn' => [
                        [
                            'label' => '确认',
//                            'type' => 'sub_confirm_deal_btn',
                            'type' => 'sub_confirm_payment_page',
                            'sub_status' => Subscribed::SUBSCRIBED_STATUS_CONFIRM_BY_FIN,
                            'sub_id' => $subId,
                            'project_id' => $this->_projectId,
                            'visit_id' => $model->visit_id,
                        ],
                        [
                            'label' => '拒绝',
//                            'type' => 'sub_confirm_deal_btn',
                            'type' => 'sub_confirm_payment_page',
                            'sub_status' => Subscribed::SUBSCRIBED_STATUS_REJECT,
                            'sub_id' => $subId,
                            'project_id' => $this->_projectId,
                            'visit_id' => $model->visit_id,
                        ]
                    ],
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

                $contentPm = [
                    'content' => '有一条新认购，时间：' . date('Y-m-d H:i:s', time()) . '，已经发送到出纳',
                    'sub_id' => $subId,
                    'title' => '新认购',
                    'btn' => [
                    ],
                ];
                Yii::$app->msg->add($pmRecvId, $contentPm, Msg::MSG_SENDER_SYSTEM);

            }

        } else if ($subStatus == Subscribed::SUBSCRIBED_STATUS_CONFIRM_BY_FIN) {
//            $recvId = !empty($this->_project->advisor_staff_id) ? $this->_project->advisor_staff_id : 0;
            if ($this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                || $this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
                $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
            } else {
                $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
            }
            $pmRecvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            $sub = Subscribed::find()
                ->where(['id' => $subId])
                ->one();
            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $content = [
                    'content' => '新认购 项目：' . $projectName . ' ' . $sub->room_no . ' 出纳已经确认！',
                    'sub_id' => $subId,
                    'title' => '新认购',
                    'btn' => [

                    ],
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);

                $contentPm = [
                    'content' => '有一条新认购，项目：' . $projectName . '，时间：' . date('Y-m-d H:i:s', time()) . '，出纳已经确认',
                    'sub_id' => $subId,
                    'title' => '新认购',
                    'btn' => [
                    ],
                ];
                Yii::$app->msg->add($pmRecvId, $contentPm, Msg::MSG_SENDER_SYSTEM);
            }
        }
        Yii::$app->oplog->write(
            \common\models\Log::OP_CODE_SUB_CONFIRM_DEAL,
            \common\models\Log::OP_STATUS_SUCCESS,
            $this->_staffId,
            $model->mobile,
            $model->getAttributes(),
            '确认认购成交',
            [
                'sub_id' => $model->getPrimaryKey()
            ]
        );

        return $this->success();
    }
    public function confirmSign() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;
//        $supplySubGuest = !empty($this->_get['supply_sub_guest']) ? $this->_get['supply_sub_guest'] : '';
//        $supplyGuestIdType = !empty($this->_get['supply_guest_id_type']) ? $this->_get['supply_guest_id_type'] : 0;
//        $supplyGuestIdNo = !empty($this->_get['supply_guest_id_no']) ? $this->_get['supply_guest_id_no'] : '';
//        $supplyGuestMobile = !empty($this->_get['supply_guest_mobile']) ? $this->_get['supply_guest_mobile'] : '';
//        $supplyTotalPrice = !empty($this->_get['supply_total_price']) ? $this->_get['supply_total_price'] : 0;

        Yii::$app->privilege->checkByUser($this->_user, Privilege::SUB_CONFIRM_SIGN);

        if (empty($subId)) {
            return $this->fail('需要指定认购ID', -1000);
        }

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        if (empty($model)) {
            return $this->fail('认购不存在', -1000);
        }

        foreach ($this->_get as $key => $value) {
            if (in_array($key, ['sub_id', 'project_id', 'report_id', 'is_test'])) {
                continue;
            }
            if (in_array($key, ['supply_sub_guest', 'supply_guest_id_type', 'supply_guest_id_no', 'supply_guest_mobile', 'supply_total_price'])) {

                if (!empty($value) && isset($this->_get[$key])) {
                    $model->$key = $value;
                }
            }
            $model->sub_status = Subscribed::SUBSCRIBED_STATUS_CONFIRM_BY_FIN;
        }

        try {

            $ret = $model->save();
            if ($ret === false) {
                Yii::error($model->getErrors());
                return $this->fail('操作失败', -1000);
            }

            if (!empty($msgId)) {
                Yii::$app->msg->removeBtn($msgId);
            }

            $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            if (!empty($recvId)) {
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $content = [
                    'content' => '完成了一次签约，项目：' . $projectName . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                    'project_id' => $this->_projectId,
                    'title' => '完成签约',
//                    'btn' => [
//                        [
//                            'label' => '确认',
//                            'type' => 'sub_confirm_deal_btn',
//                            'sub_status' => Subscribed::SUBSCRIBED_STATUS_CONFIRM,
//                            'sub_id' => $subId,
//                            'project_id' => $this->_projectId,
//                        ],
//                        [
//                            'label' => '拒绝',
//                            'type' => 'sub_confirm_deal_btn',
//                            'sub_status' => Subscribed::SUBSCRIBED_STATUS_REJECT,
//                            'sub_id' => $subId,
//                            'project_id' => $this->_projectId,
//                        ],
//                    ],
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }

            Yii::$app->oplog->write(
                \common\models\Log::OP_CODE_SUB_CONFIRM_SIGN,
                \common\models\Log::OP_STATUS_SUCCESS,
                $this->_staffId,
                $model->mobile,
                $model->getAttributes(),
                '确认认购签约',
                [
                    'sub_id' => $model->getPrimaryKey()
                ]
            );

        } catch (\Exception $e) {
            Yii::$app->oplog->write(
                \common\models\Log::OP_CODE_SUB_CONFIRM_SIGN,
                \common\models\Log::OP_STATUS_FAILED,
                $this->_staffId,
                $model->mobile,
                $model->getAttributes(),
                '确认认购签约失败',
                ['code' => $e->getCode(), 'msg' => $e->getMessage()]
            );
            return $this->fail('操作失败', -1000);
        }

        return $this->success();
    }

    public function update() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;

        if (empty($subId)) {
            return $this->fail('需要指定认购ID', -1000);
        }

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        if (empty($model)) {
            return $this->fail('认购不存在', -1000);
        }

        foreach ($this->_get as $key => $value) {
            if (in_array($key, ['sub_id', 'project_id', 'report_id', 'is_test'])) {
                continue;
            }
            if (!empty($value) && isset($this->_get[$key])) {
                $model->$key = $value;
            }
        }

        $model->save();

        return $this->success();
    }

    public function getById() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;

        if (empty($subId)) {
            return $this->fail('需要指定认购ID', -1000);
        }

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        if (empty($model)) {
            return $this->fail('认购不存在', -1000);
        }

        $ret = [];
        if (!empty($model)) {
            $tmp = $model->toArray();
            $tmpIdNos = Common::splitMobile($model->id_no);
            $tmp['id_no'] = Common::formatMultyMobiles($tmpIdNos);
            $ret = $tmp;
        }

        return $this->success(['sub' => $ret]);
    }

    public function getWithPaymentsByRoomNo() {
        $roomNo = !empty($this->_get['room_no']) ? $this->_get['room_no'] : '';
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
//        $mobile = !empty($this->_get['mobile']) ? $this->_get['mobile'] : '';

        if (empty($roomNo)
//            && empty($mobile)
        ) {
            return $this->fail('需要指定房间号', -1000);
        }

        $model = Subscribed::find()
            ->where([
                'room_no' => $roomNo,
                'project_id' => $projectId,
//                'mobile' => $mobile
            ])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->one();

        $payments = [];
        $payStatus = Subscribed::SUB_PAY_WAIT;
        if (!empty($model)) {
            $subId = $model->id;

            $payments = Payment::find()
                ->where(['sub_id' => $subId])
                ->all();

            $payStatus = \common\helpers\Payment::checkTotalAmount($payments, $model->sub_total_price);
        }

        return $this->success(['sub' => $model, 'payments' => $payments, 'pay_status' => $payStatus]);

    }

    public function getWithPaymentsById() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;

        if (empty($subId)) {
            return $this->fail('需要指定认购ID', -1000);
        }

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        $payments = Payment::find()
            ->where(['sub_id' => $subId])
            ->all();

        $payTotal = 0;
//        if (!empty($payments)) {
//            foreach ($payments as $pay) {
//                if ($pay->pay_type == Payment::PAYMENT_TYPE_PAY) {
//                    $payTotal += $pay->recv_amount;
//                } else {
//                    $payTotal -= $pay->recv_amount;
//                }
//            }
//            if ($payTotal > $model->sub_total_price) {
//                $payStatus = Subscribed::SUB_PAY_FULLY;
//            } else {
//                $payStatus = Subscribed::SUB_PAY_PARTLY;
//            }
//        }
        $payStatus = \common\helpers\Payment::checkTotalAmount($payments, $model->sub_total_price);

        if (empty($model)) {
            return $this->fail('认购不存在', -1000);
        }

        return $this->success(['sub' => $model, 'payments' => $payments, 'pay_status' => $payStatus]);
    }


    public function getByProjectId() {
        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $pageSize = !empty($this->_get['page_size']) ? $this->_get['page_size'] : 10;

        $query = Subscribed::find()
            ->where(['project_id' => $this->_projectId])
//            ->andFilterWhere(['report_id' => $this->_reportId])
            ->orderBy([
                'id' => SORT_DESC
            ]);

        $count = $query->count();
        $list = $query->offset(($page - 1) * $pageSize)->limit($pageSize)->all();

        return $this->success([
            'list' => $list,
            'total_count' => $count,
        ]);
    }

    public function add() {

        $model = new Subscribed();

        $transaction = Yii::$app->db->beginTransaction();

        $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;
        if (!empty($visitId)) {
            $visit = Visit::find()
                ->where(['id' => $visitId])
                ->one();
        }

        try {

            $subType = !empty($this->_get['sub_type']) ? $this->_get['sub_type'] : 0;
            $subGuest = !empty($this->_get['sub_guest']) ? $this->_get['sub_guest'] : '';
            $roomNo = !empty($this->_get['room_no']) ? $this->_get['room_no'] : '';
            $idType = !empty($this->_get['id_type']) ? $this->_get['id_type'] : "";
            $idNo = !empty($this->_get['id_no']) ? $this->_get['id_no'] : '';
            $guestMobile = $mobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
            $buildingArea = !empty($this->_get['building_area']) ? $this->_get['building_area'] : '';
            $balancePrice = !empty($this->_get['balance_price']) ? $this->_get['balance_price'] : 0;
            $subTotalPrice = !empty($this->_get['sub_total_price']) ? $this->_get['sub_total_price'] : 0;
            $payMethod = !empty($this->_get['pay_method']) ? $this->_get['pay_method'] : "";
            $owner = !empty($this->_get['owner']) ? $this->_get['owner'] : '';
            $lessor = !empty($this->_get['lessor']) ? $this->_get['lessor'] : '';
            $lessorDetail = !empty($this->_get['lessor_detail']) ? $this->_get['lessor_detail'] : '';
            $rentDateBegin = !empty($this->_get['rent_date_begin']) ? $this->_get['rent_date_begin'] : Date('Y-m-d 00:00:00');
            $rentDateEnd = !empty($this->_get['rent_date_end']) ? $this->_get['rent_date_end'] : Date('Y-m-d 00:00:00', strtotime('+1 year'));
            $freeRentDate = !empty($this->_get['free_rent_date']) ? $this->_get['free_rent_date'] : '';
            $increaseDate = !empty($this->_get['increase_date']) ? $this->_get['increase_date'] : '';
            $increaseRate = !empty($this->_get['increase_rate']) ? $this->_get['increase_rate'] : '';
            $deposit = !empty($this->_get['deposit']) ? $this->_get['deposit'] : 0;
            $dailyAmount = !empty($this->_get['daily_amount']) ? $this->_get['daily_amount'] : 0;
            $monthlyAmount = !empty($this->_get['monthly_amount']) ? $this->_get['monthly_amount'] : 0;
            $yearlyAmount = !empty($this->_get['yearly_amount']) ? $this->_get['yearly_amount'] : 0;
            $rentAmount = !empty($this->_get['rent_amount']) ? $this->_get['rent_amount'] : 0;
            $proRentAmount = !empty($this->_get['pro_rent_amount']) ? $this->_get['pro_rent_amount'] : 0;
            $alDailyAmount = !empty($this->_get['al_daily_amount']) ? $this->_get['al_daily_amount'] : 0;
            $alAmount = !empty($this->_get['al_amount']) ? $this->_get['al_amount'] : 0;
            $alOther = !empty($this->_get['al_other']) ? $this->_get['al_other'] : 0;
            $alTotalAmount = !empty($this->_get['al_total_amount']) ? $this->_get['al_total_amount'] : 0;
            $alDateBegin = !empty($this->_get['al_date_begin']) ? $this->_get['al_date_begin'] : '';
            $alDateEnd = !empty($this->_get['al_date_end']) ? $this->_get['al_date_end'] : '';
            $supplySubGuest = !empty($this->_get['supply_sub_guest']) ? $this->_get['supply_sub_guest'] : '';
            $supplyGuestIdType = !empty($this->_get['supply_guest_id_type']) ? $this->_get['supply_guest_id_type'] : "";
            $supplyGuestIdNo = !empty($this->_get['supply_guest_id_no']) ? $this->_get['supply_guest_id_no'] : '';
            $supplyGuestMobile = !empty($this->_get['supply_guest_mobile']) ? $this->_get['supply_guest_mobile'] : '';
            $supplyTotalPrice = !empty($this->_get['supply_total_price']) ? $this->_get['supply_total_price'] : 0;
            $visitId = !empty($this->_get['visit_id']) ? $this->_get['visit_id'] : 0;

            $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

            if (strpos("\n", $guestMobile) !== false) {
                $guestMobiles = str_replace("\n", '', $guestMobile);
            } else {
                $guestMobiles = [$guestMobile];
            }
            $mobileTag = False;
            foreach ($guestMobiles as $mobile) {
                $mobile = trim($mobile);
                $reportMobiles = $this->_report->guest_mobile;
                if (strpos($reportMobiles, $mobile) !== false) {
                    $mobileTag = True;
                    break;
                }
            }
            if (!$mobileTag) {
                return $this->fail('请填写报备客户手机号', -1000);
            }

            if (empty($monthlyAmount) || empty($yearlyAmount)) {
//                $monthlyAmount = 0;
//                $yearlyAmount = 0;
                if (!empty($dailyAmount) && !empty($buildingArea)) {
                    $monthlyAmount = $dailyAmount * $buildingArea * 365 / 12;
                    $monthlyAmount = number_format($monthlyAmount, 2, '.', '');
                    $yearlyAmount = $dailyAmount * $buildingArea * 365;
                    $yearlyAmount = number_format($yearlyAmount, 2, '.', '');

                    if (!empty($rendDateEnd) && !empty($rentDateBegin)) {
                        $rentAmount = $dailyAmount * $buildingArea * (int((strtotime($rentDateEnd) - strtotime($rentDateBegin)) / 86400) + 1);
                        $rentAmount = number_format($rentAmount, 2, '.', '');
                    }
                }
            }

            if (!empty($monthlyAmount) && !empty($buildingArea)) {
//                $dailyAmount = $monthlyAmount / 30;
                $yearlyAmount = $monthlyAmount * 12;

                if (!empty($rendDateEnd) && !empty($rentDateBegin)) {
                    $rentAmount = $monthlyAmount * (intval((strtotime($rentDateEnd) - strtotime($rentDateBegin)) / 86400) + 1);
                    $rentAmount = number_format($rentAmount, 2, '.', '');
                }
            }

            if (empty($alDailyAmount) || empty($alTotalAmount)) {
                $alDailyAmount =  $proRentAmount - $dailyAmount;
                if (!empty($rentAmount) && !empty($alDateBegin) && !empty($alDateEnd) && !empty($buildingArea)) {
                    $alTotalAmount = $alDailyAmount * (intval((strtotime($alDateEnd) - strtotime($alDateBegin)) / 86400) + 1) * $buildingArea;
                    $alTotalAmount = number_format($alTotalAmount, 2, '.', '');
                }
            }

            if (empty($alAmount)) {
                $alAmount = $alTotalAmount + $alOther;
                $alAmount = number_format($alAmount, 2, '.', '');
            }

            if (empty($deposit)) {
                $depositX = !empty(Subscribed::$depositX[$payMethod]) ? Subscribed::$depositX[$payMethod] : 0;
                $deposit = $monthlyAmount * $depositX;
                $deposit = number_format($deposit, 2, '.', '');
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

            $model->project_id = $this->_projectId;
            $model->visit_id = $visitId;
//            $model->report_id = $this->_reportId;
            $model->sub_type = $subType;
            $model->sub_guest = $subGuest;
            $model->room_no = $roomNo;
            $model->id_type = $idType;
            $model->id_no = $idNo;
            $model->mobile = $mobile;
            $model->building_area = $buildingArea;
            $model->balance_price = $balancePrice;
            $model->sub_total_price = $subTotalPrice;
            $model->pay_method = $payMethod;
            $model->owner = $owner;
            $model->lessor = $lessor;
            $model->lessor_detail = $lessorDetail;
            $model->rent_date_begin = $rentDateBegin;
            $model->rent_date_end = $rentDateEnd;
            $model->free_rent_date = $freeRentDate;
            $model->increase_date = $increaseDate;
            $model->increase_rate = (string)$increaseRate;
            $model->deposit = $deposit;
            $model->daily_amount = $dailyAmount;
            $model->monthly_amount = $monthlyAmount;
            $model->yearly_amount = $yearlyAmount;
            $model->rent_amount = $rentAmount;
            $model->pro_rent_amount = $proRentAmount;
            $model->al_daily_amount = $alDailyAmount;
            $model->al_amount = $alAmount;
            $model->al_other = $alOther;
            $model->al_total_amount = $alTotalAmount;
            $model->al_date_begin = $alDateBegin;
            $model->al_date_end = $alDateEnd;
            $model->supply_sub_guest = $supplySubGuest;
            $model->supply_guest_id_type = $supplyGuestIdType;
            $model->supply_guest_id_no = $supplyGuestIdNo;
            $model->supply_guest_mobile = $supplyGuestMobile;
            $model->supply_total_price = $supplyTotalPrice;


            $ret = $model->save();

            if ($ret === false) {
                Yii::error($model->getErrors());
            }

            $transaction->commit();

            // 获取最新一条数据ID
            $subId = $model->getPrimaryKey();
//            $subId = Yii::$app->db->getLastInsertID();

            if (!empty($msgId)) {
                Yii::$app->msg->removeBtn($msgId);
            }

//            $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
//            $recvId = !empty($this->_report->staff_id) ? $this->_report->staff_id : 0;
//            if ($this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
//                || $this->_report->guest_appeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
//                $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
//            } else {
//                $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
//            }
            if ($visit->visit_confirm_status == Visit::VISIT_CONFIRM_STATUS_SIGNED) {
                $content = [
                    'content' => '项目 ' . $this->_project->project_name . ' 进入签约流程',
                    'project_id' => $this->_projectId,
                    'title' => '签约',
                    'btn' => [
                        [
                            'label' => '签约',
                            'type' => 'sub_confirm_deal_page',
                            'project_id' => $this->_projectId,
                            'sub_id' => $model->id,
                            'report_id' => $this->_reportId,
                            'visit_id' => $visitId,
//                            'payment_id' => $model->id,
                        ],
                    ],
                ];
//                    $recvId = $this->_project->advisor_staff_id;
                $guestAppeal = !empty($this->_report->guest_appeal) ? $this->_report->guest_appeal : '';
                if (!empty($guestAppeal)) {
                    if ($guestAppeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                        || $guestAppeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
                        $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
                    } else {
                        $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
                    }
                }
                if (!empty($recvId)) {
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                }
            } else {
                $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
                if (!empty($recvId)) {
                    $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                    $content = [
                        'content' => '有一条新认购需要确认，项目：' . $projectName . '，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                        'project_id' => $this->_projectId,
                        'title' => '新认购',
                        'btn' => [
                            [
                                'label' => '查看',
                                'type' => 'sub_confirm_page',
                                'sub_id' => $subId,
                                'project_id' => $this->_projectId,
                                'report_id' => $this->_reportId,
                                'visit_id' => $visitId,
                            ]
                        ],
                    ];
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                }
//                if (!empty($recvId)) {
//                    $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
//                    $content = [
//                        'content' => '有一条新认购，项目：' . $projectName . '，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
//                        'project_id' => $this->_projectId,
//                        'title' => '新认购',
//                        'btn' => [
//                            [
//                                'label' => '确认',
//                                'type' => 'payment_page',
//                                'sub_id' => $subId,
//                                'project_id' => $this->_projectId,
//                                'report_id' => $this->_reportId,
//                                'visit_id' => $visitId,
//                            ]
//                        ],
//                    ];
//                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
//
//                    $pmRecvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
//                    $content = [
//                        'content' => '有一条新认购，项目：' . $projectName . '，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
//                        'project_id' => $this->_projectId,
//                        'title' => '新认购',
//                        'btn' => [
//                        ],
//                    ];
//                    Yii::$app->msg->add($pmRecvId, $content, Msg::MSG_SENDER_SYSTEM);
//                }
            }

            Yii::$app->oplog->write(
                \common\models\Log::OP_CODE_SUB_ADD,
                \common\models\Log::OP_STATUS_SUCCESS,
                $this->_staffId,
                $mobile,
                $model->getAttributes(),
                '添加认购',
                [
                    'sub_id' => $subId,
                    'project_id' => $this->_projectId,
                    'report_id' => $this->_reportId,
                    'subscribed' => $model,
                ]
            );

            return $this->success([
                'sub_id' => $subId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
                'subscribed' => $model,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();

            Yii::$app->oplog->write(\common\models\Log::OP_CODE_SUB_ADD, \common\models\Log::OP_STATUS_FAILED, $this->_staffId, $mobile, [
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);

            Yii::error($e);
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


}