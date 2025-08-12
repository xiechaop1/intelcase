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
use common\models\Payment;
use common\models\Project;
use common\models\Report;
use common\models\Subscribed;
use common\models\Visit;
//use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class PaymentApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;
    private $_project;
    private $_reportId;
    private $_report;

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
                ->where([
                    'id' => $this->_projectId,
                ])
                ->one();

            if (empty($this->_reportId)) {
                return $this->fail('需要指定报备', -1000);
            }

//            if ($this->action == 'get_by_id' || $this->action == 'get_by_project_id') {
                $beginDate = date('Y-m-d 00:00:00', strtotime('-1year'));
//            } else {
//                $beginDate = date('Y-m-d 00:00:00');
//            }
            $this->_report = Report::find()
                ->where([
                    'id' => $this->_reportId,
                ])
                ->andFilterWhere([
                    'between', 'visit_time', $beginDate, date('Y-m-d 23:59:59')
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

    public function getById(){
        $paymentId = !empty($this->_get['payment_id']) ? $this->_get['payment_id'] : 0;

        if (empty($paymentId)) {
            return $this->fail('需要指定支付ID', -1000);
        }

//        $ret = [];

        $model = Payment::find()
            ->where([
                'id' => $paymentId,
            ])
            ->one();

        if (empty($model)) {
            return $this->fail('没有找到支付内容', -1000);
        }

        $ret = $model->toArray();
        $ret['pay_time_str'] = !empty($ret['pay_time']) ? date('Y-m-d H:i:s', $ret['pay_time']) : '';

        if (empty($ret)) {
            return $this->fail('支付不存在', -1000);
        }

        return $this->success($ret);
    }

    public function getByProjectId()
    {
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

        if (empty($projectId)) {
            return $this->fail('需要指定项目', -1000);
        }

        $model = Payment::find()
            ->where([
                'project_id' => $projectId,
            ])
            ->orderBy('id DESC')
            ->all();

        if (empty($model)) {
            return $this->fail('项目不存在', -1000);
        }

        return $this->success($model);
    }

    public function confirm() {

        $paymentId = !empty($this->_get['payment_id']) ? $this->_get['payment_id'] : 0;

        $recv_amount = !empty($this->_get['recv_amount']) ? $this->_get['recv_amount'] : 0;
        $recv_time = !empty($this->_get['recv_time']) ? $this->_get['recv_time'] : time();
        $fee = !empty($this->_get['fee']) ? $this->_get['fee'] : 0;
        $pay_status = !empty($this->_get['pay_status']) ? $this->_get['pay_status'] : Payment::PAYMENT_STATUS_COMPLETED;
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

        Yii::$app->privilege->checkByUser($this->_user, Privilege::PAYMENT_CONFIRM);

        if (empty($paymentId)) {
            return $this->fail('需要指定支付ID', -1000);
        }

        $model = Payment::find()
            ->where([
                'id' => $paymentId,
            ])
            ->one();

        if (!empty($recv_time) && is_string($recv_time)) {
            $recv_time = strtotime($recv_time);
        }

        if (!empty($model)) {
            $model->recv_amount = $recv_amount;
            $model->recv_time = $recv_time;
            $model->fee = $fee;
            $model->pay_status = $pay_status;
            $ret = $model->save();
            if ($ret) {
                $sub = Subscribed::find()
                    ->where(['id' => $model->sub_id])
                    ->one();

                $subTotalPrice = !empty($sub->sub_total_price) ? $sub->sub_total_price : 0;

                $subGuest = !empty($sub->owner) ? $sub->owner : '';
                $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
                $guestMobile = !empty($this->_report->guest_mobile) ? $this->_report->guest_mobile : '';
                $guestChannel = !empty($this->_report->guest_channel) ? $this->_report->guest_channel : '';
                $guestInfo = \common\helpers\Common::formatGuestInfo($projectName, $subGuest, '', date('Y-m-d H:i:s', time()), $guestChannel);

                $roomNo = !empty($sub->room_no) ? $sub->room_no : '';
                $payer = !empty($model->payer) ? $model->payer : '';
                $amountType = !empty($model->amount_type) ? $model->amount_type : '';
                $payTime = !empty($model->pay_time) ? $model->pay_time : time();
                $guestInfo2 =  '，房号：' . $roomNo . '，金额：' . number_format($recv_amount, 2) . '，付款人：' . $payer . '，付款方式：' . $amountType . '， 付款时间：' . Date('Y-m-d H:i:s', $payTime);


                if ($model->pay_type == Payment::PAYMENT_TYPE_PAY) {
                    $payments = Payment::find()
                        ->where(['project_id' => $this->_projectId])
                        ->andFilterWhere(['sub_id' => $model->sub_id])
                        ->andFilterWhere(['pay_status' => Payment::PAYMENT_STATUS_COMPLETED])
                        ->all();
                    $payStatus = \common\helpers\Payment::checkTotalAmount($payments, $subTotalPrice);

                    if (!empty($sub->sub_type) && $sub->sub_type == Subscribed::SUB_TYPE_RENT) {
                        $content = [
                            'content' => $guestInfo . $guestInfo2 . ' 完成支付',
                            'project_id' => $this->_projectId,
                            'title' => '完成支付',
                            'btn' => [
                                [
                                    'label' => '平移',
                                    'type' => 'movetime_page',
                                    'sub_id' => $model->sub_id,
                                    'project_id' => $this->_projectId,
                                    'report_id' => $this->_reportId,
                                    'payment_id' => $model->id,
                                ],
                            ],
                        ];
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
                        if ($payStatus == Subscribed::SUB_PAY_FULLY) {

                            $content = [
                                'content' => $guestInfo . $guestInfo2 . ' 完成支付',
                                'project_id' => $this->_projectId,
                                'title' => '完成支付',
                                'btn' => [
                                    [
                                        'label' => '签约',
                                        'type' => 'sub_confirm_deal_page',
                                        'project_id' => $this->_projectId,
                                        'sub_id' => $model->sub_id,
                                        'report_id' => $this->_reportId,
                                        'payment_id' => $model->id,
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
                            $guestAppeal = !empty($this->_report->guest_appeal) ? $this->_report->guest_appeal : '';
                            if (!empty($guestAppeal)) {
                                if ($guestAppeal == Visit::VISIT_GUEST_APPEAL_INVESTMENT
                                    || $guestAppeal == Visit::VISIT_GUEST_APPEAL_SELF_USE) {
                                    $recvId = !empty($this->_report->advisor_staff_id) ? $this->_report->advisor_staff_id : 0;
                                    $jumpType = 'sub_buy_page';
                                } else {
                                    $recvId = !empty($this->_report->consultant_staff_id) ? $this->_report->consultant_staff_id : 0;
                                    $jumpType = 'sub_rent_page';
                                }
                            }
                            $content = [
                                'content' => $guestInfo . $guestInfo2 . ' 完成部分支付，下次客户到来，请通过此链接继续进入进行支付',
                                'project_id' => $this->_projectId,
                                'title' => '完成部分支付',
                                'btn' => [
                                    [
                                        'label' => '再次支付',
                                        'type' => $jumpType,
                                        'project_id' => $this->_projectId,
                                        'report_id' => $this->_reportId,
                                        'sub_id' => $model->sub_id,
                                        'payment_id' => $model->id,
                                    ],
                                ],
                            ];
//                    $recvId = $this->_project->advisor_staff_id;

                            if (!empty($recvId)) {
                                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                            }
                        }
                    }
                } else {
                    // 退款
                    $content = [
                        'content' => $guestInfo . $guestInfo2 . ' 完成退款',
                        'project_id' => $this->_projectId,
                        'title' => '完成退款',
//                        'btn' => [
//                            [
//                                'label' => '平移',
//                                'type' => 'movetime_page',
//                                'sub_id' => $model->sub_id,
//                                'project_id' => $this->_projectId,
//                                'report_id' => $this->_reportId,
//                                'payment_id' => $model->id,
//                            ],
//                        ],
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
                }

                if (!empty($msgId)) {
                    Yii::$app->msg->removeBtn($msgId);
                }

                Yii::$app->oplog->write(\common\models\Log::OP_CODE_PAYMENT_CONFIRM, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, '', [
                    'payment_id' => $model->id,
                    'recv_amount' => $recv_amount,
                    'recv_time' => $recv_time,
                    'fee' => $fee,
                    'pay_status' => $pay_status,
                ], '确认支付', [
                    'payment_id' => $model->id,
                    'ret' => $ret,
                ]);
                return $this->success($model);
            } else {
                Yii::error($model->getErrors());
                Yii::$app->oplog->write(\common\models\Log::OP_CODE_PAYMENT_CONFIRM, \common\models\Log::OP_STATUS_FAILED, $this->_staffId, '', [
                    'payment_id' => $model->id,
                    'recv_amount' => $recv_amount,
                    'recv_time' => $recv_time,
                    'fee' => $fee,
                    'pay_status' => $pay_status,
                ], '确认支付', [
                    'payment_id' => $model->id,
                    'ret' => $ret,
                ]);
                return $this->fail('操作失败', -1000);
            }
        }
    }

    public function update() {
        $paymentId = !empty($this->_get['payment_id']) ? $this->_get['payment_id'] : 0;

        if (empty($paymentId)) {
            return $this->fail('需要指定支付ID', -1000);
        }

        $model = Payment::find()
            ->where([
                'id' => $paymentId,
            ])
            ->one();

        if (empty($model)) {
            return $this->fail('支付不存在', -1000);
        }

//        $model->attributes = $this->_get;
        if (!empty($this->_get)) {
            foreach ($this->_get as $key => $value) {
                if ($key == 'payment_id') {
                    continue;
                }
                if (empty($value)) {
                    continue;
                }
                if (isset($model->$key)) {
                    $model->$key = $value;
                }
            }
            if ($model->save()) {

//                $payments = Payment::find()
//                    ->where(['project_id' => $model->project_id])
//                    ->andFilterWhere(['sub_id' => $model->sub_id])
//                    ->andFilterWhere(['pay_status' => Payment::PAYMENT_STATUS_COMPLETED])
//                    ->all();
//
//                $sub = Subscribed::find()
//                    ->where(['id' => $model->sub_id])
//                    ->one();
//
//                $subTotalPrice = !empty($sub->sub_total_price) ? $sub->sub_total_price : 0;
//
//                $payStatus = \common\helpers\Payment::checkTotalAmount($payments, $subTotalPrice);
//                if ($payStatus == Subscribed::SUB_PAY_FULLY) {
//                    $content = [
//                        'content' => '项目 ' . $this->_project->project_name . ' 完成支付，请最终确认',
//                        'project_id' => $this->_projectId,
//                        'title' => '完成支付',
//                        'btn' => [
//                            'label' => '最终确认',
//                        ],
//                    ];
//                    $recvId = $this->_project->financial_staff_id;
//                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
//                } elseif ($payStatus == Subscribed::SUB_PAY_PARTLY) {
//                    $content = [
//                        'content' => '项目 ' . $this->_project->project_name . ' 完成不分支付，请确认',
//                        'project_id' => $this->_projectId,
//                        'title' => '完成股份支付',
//                        'btn' => [
//                            'label' => '确认',
//                        ],
//                    ];
//                    $recvId = $this->_project->financial_staff_id;
//                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
//                }

                return $this->success($model);
            } else {
                return $this->fail('操作失败', -1000);
            }
        }
        return $this->fail('没有需要更新的字段', -1000);


    }

    public function add() {

        $model = new Payment();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;
            $payer = !empty($this->_get['payer']) ? $this->_get['payer'] : '';
            $payType = !empty($this->_get['pay_type']) ? $this->_get['pay_type'] : Payment::PAYMENT_TYPE_PAY;
            $payTime = !empty($this->_get['pay_time']) ? $this->_get['pay_time'] : time();
            $payWay = !empty($this->_get['pay_way']) ? $this->_get['pay_way'] : '';
            $payStatus = !empty($this->_get['pay_status']) ? $this->_get['pay_status'] : 0;
            $amount = !empty($this->_get['amount']) ? $this->_get['amount'] : 0;
            $amountType = !empty($this->_get['amount_type']) ? $this->_get['amount_type'] : '';
            $payAccount = !empty($this->_get['pay_account']) ? $this->_get['pay_account'] : '';
            $recvAccount = !empty($this->_get['recv_account']) ? $this->_get['recv_account'] : '';
            $receiptNo = !empty($this->_get['receipt_no']) ? $this->_get['receipt_no'] : '';
            $recvAmount = !empty($this->_get['recv_amount']) ? $this->_get['recv_amount'] : 0;
            $fee = !empty($this->_get['fee']) ? $this->_get['fee'] : 0;
            $recvTime = !empty($this->_get['recv_time']) ? $this->_get['recv_time'] : 0;
            $desc = !empty($this->_get['reason']) ? $this->_get['reason'] : '';

            $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

            if (!empty($payTime) && is_string($payTime)) {
                $payTime = strtotime($payTime);
            }

            $paymentRet = Payment::find()
                ->where(['project_id' => $this->_projectId])
                ->andFilterWhere(['sub_id' => $subId])
                ->andFilterWhere(['pay_status' => Payment::PAYMENT_STATUS_COMPLETED])
//                ->andFilterWhere(['between', 'pay_time', date('Y-m-d 00:00:00')), date('Y-m-d 23:59:59'))])
                ->orderBy('id DESC')
                ->all();

            $subscribed = Subscribed::find()
                ->where([
                    'id' => $subId,
                ])
                ->orderBy('id DESC')
                ->one();


            if (empty($subscribed)) {
                return $this->fail('认购不存在', -1000);
            }
            $subTotalPrice = !empty($subscribed->sub_total_price) ? $subscribed->sub_total_price : 0;

            $recvAmountRet = 0;
            foreach ($paymentRet as $payment) {
                if ($payment->pay_type == Payment::PAYMENT_TYPE_REFUND) {
                    $recvAmountRet -= $payment->recv_amount;
                } else {
                    $recvAmountRet += $payment->recv_amount;
                }
            }
//            if ($recvAmountRet + $amount >= $subTotalPrice) {
//                // Todo: 认购总额超了，应该进入下一个流程了
//            }


            $model->payer = $payer;
            $model->sub_id = $subId;
            $model->project_id = $this->_projectId;
//            $model->report_id = $this->_reportId;
            $model->pay_time = $payTime;
            $model->pay_way = $payWay;
            $model->pay_type = $payType;
            $model->pay_status = $payStatus;
            $model->amount = $amount;
            $model->amount_type = $amountType;
            $model->pay_account = $payAccount;
            $model->recv_account = $recvAccount;
            $model->receipt_no = $receiptNo;
            $model->recv_amount = $recvAmount;
            $model->fee = $fee;
            $model->recv_time = $recvTime;
            $model->desc = $desc;


            $ret = $model->save();
            if ($ret === false) {
                Yii::error($model->getErrors());
            }

            $transaction->commit();

            $subGuest = !empty($subscribed->owner) ? $subscribed->owner : '';
            $projectName = !empty($this->_project->project_name) ? $this->_project->project_name : '未知项目';
            $guestMobile = !empty($this->_report->guest_mobile) ? $this->_report->guest_mobile : '';
            $guestChannel = !empty($this->_report->guest_channel) ? $this->_report->guest_channel : '';
            $guestInfo = \common\helpers\Common::formatGuestInfo($projectName, $subGuest, $guestMobile, date('Y-m-d H:i:s', time()), $guestChannel);


            $paymentId = $model->getPrimaryKey();
            if ($payType == Payment::PAYMENT_TYPE_PAY) {
                $payments = Payment::find()
                    ->where(['project_id' => $this->_projectId])
                    ->andFilterWhere(['sub_id' => $subId])
                    ->andFilterWhere(['pay_status' => Payment::PAYMENT_STATUS_COMPLETED])
                    ->all();

//            $sub = Subscribed::find()
//                ->where(['id' => $subId])
//                ->one();
//
//            $subTotalPrice = !empty($sub->sub_total_price) ? $sub->sub_total_price : 0;

                $payStatus = \common\helpers\Payment::checkTotalAmount($payments, $subTotalPrice, $model);
                $roomNo = !empty($subscribed->room_no) ? $subscribed->room_no : '未知房号';
                if ($payStatus == Subscribed::SUB_PAY_FULLY) {
                    $content = [
                        'content' => $guestInfo . ' 完成支付，' . '房号：' . $roomNo . '，金额：' . number_format($amount, 2) . '，付款人：' . $payer . '，付款方式：' . $amountType . '， 付款时间：' . Date('Y-m-d H:i:s', $payTime) . '，请最终确认',
                        'project_id' => $this->_projectId,
                        'title' => '完成支付',
                        'btn' =>
                        [
                            [
                                'label' => '最终确认',
                                'type' => 'payment_confirm_page',
                                'sub_type' => 1,        // 付款
                                'project_id' => $this->_projectId,
                                'payment_id' => $paymentId,
                                'report_id' => $this->_reportId,
                                'sub_id' => $subId,
                            ],
                        ],
                    ];
                    $recvId = $this->_project->financial_staff_id;
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                } else {
                    $content = [
                        'content' => $guestInfo . ' 完成部分支付，' . '房号：' . $roomNo . '，金额：' . number_format($amount, 2) . '，付款人：' . $payer . '，付款方式：' . $amountType . '， 付款时间：' . Date('Y-m-d H:i:s', $payTime) . '，请确认',
                        'project_id' => $this->_projectId,
                        'title' => '完成部分支付',
                        'btn' => [
                            [
                                'label' => '确认',
                                'type' => 'payment_confirm_page',
                                'sub_type' => 1,        // 付款
                                'project_id' => $this->_projectId,
                                'payment_id' => $paymentId,
                                'report_id' => $this->_reportId,
                                'sub_id' => $subId,
                            ],
                        ],
                    ];
                    $recvId = $this->_project->financial_staff_id;
                    Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
                }
            } else {
                $roomNo = !empty($subscribed->room_no) ? $subscribed->room_no : '未知房号';
                $content = [
                    'content' => $guestInfo . ' 进行退款，' . '房号：' . $roomNo . '，金额：' . number_format($amount, 2) . '，退款人：' . $payer . '，退款方式：' . $amountType . '， 退款时间：' . Date('Y-m-d H:i:s', $payTime) . '，请确认',
                    'project_id' => $this->_projectId,
                    'title' => '退款',
                    'btn' =>
                        [
                            [
                                'label' => '确认',
                                'type' => 'payment_confirm_page',
                                'sub_type' => 2,        // 退款
                                'project_id' => $this->_projectId,
                                'payment_id' => $paymentId,
                                'report_id' => $this->_reportId,
                                'sub_id' => $subId,
                            ],
                        ],
                ];
                $recvId = $this->_project->financial_staff_id;
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }

            if (!empty($msgId)) {
                Yii::$app->msg->removeBtn($msgId);
            }

//            $paymentId = Yii::$app->db->getLastInsertID();

            Yii::$app->oplog->write(\common\models\Log::OP_CODE_PAYMENT_ADD, \common\models\Log::OP_STATUS_SUCCESS, $this->_staffId, '', [
                'payment_id' => $paymentId,
                'payer' => $payer,
                'sub_id' => $subId,
                'project_id' => $this->_projectId,
                'pay_time' => $payTime,
                'pay_way' => $payWay,
                'pay_type' => $payType,
                'pay_status' => $payStatus,
                'amount' => $amount,
                'amount_type' => $amountType,
                'pay_account' => $payAccount,
                'recv_account' => $recvAccount,
                'receipt_no' => $receiptNo,
                'recv_amount' => $recvAmount,
                'fee' => $fee,
                'recv_time' => $recvTime,
            ], '新建支付', [
                'payment_id' => $paymentId,
                'ret' => $ret,
            ]);

            return $this->success([
                'payment_id' => $paymentId,
                'subscribed' => $subscribed,
                'payment' => $model,
                'recv_amount' => $recvAmountRet + $amount,
                'sub_total_price' => $subTotalPrice,
            ]);
        } catch (\Exception $e) {
            Yii::error($e);
            $transaction->rollBack();
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_PAYMENT_ADD, \common\models\Log::OP_STATUS_FAILED, $this->_staffId, '', [
                'payer' => $payer,
                'sub_id' => $subId,
                'project_id' => $this->_projectId,
                'pay_time' => $payTime,
                'pay_way' => $payWay,
                'pay_type' => $payType,
                'pay_status' => $payStatus,
                'amount' => $amount,
                'amount_type' => $amountType,
                'pay_account' => $payAccount,
                'recv_account' => $recvAccount,
                'receipt_no' => $receiptNo,
                'recv_amount' => $recvAmount,
                'fee' => $fee,
                'recv_time' => $recvTime,
            ], '新建支付', [
                'ret' => false,
            ]);
            return $this->fail('操作失败', -1000);
        }

    }


}