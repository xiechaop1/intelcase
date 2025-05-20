<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\definitions\Common;
use common\models\Payment;
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
    private $_reportId;

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();

            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
            $this->_reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

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


    public function add() {

        $model = new Payment();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;
            $payer = !empty($this->_get['payer']) ? $this->_get['payer'] : '';
            $payTime = !empty($this->_get['pay_time']) ? $this->_get['pay_time'] : 0;
            $payWay = !empty($this->_get['pay_way']) ? $this->_get['pay_way'] : 0;
            $payStatus = !empty($this->_get['pay_status']) ? $this->_get['pay_status'] : 0;
            $amount = !empty($this->_get['amount']) ? $this->_get['amount'] : 0;
            $amountType = !empty($this->_get['amount_type']) ? $this->_get['amount_type'] : 0;
            $payAccount = !empty($this->_get['pay_account']) ? $this->_get['pay_account'] : '';
            $recvAccount = !empty($this->_get['recv_account']) ? $this->_get['recv_account'] : '';
            $receiptNo = !empty($this->_get['receipt_no']) ? $this->_get['receipt_no'] : '';
            $recvAmount = !empty($this->_get['recv_amount']) ? $this->_get['recv_amount'] : 0;
            $fee = !empty($this->_get['fee']) ? $this->_get['fee'] : 0;
            $recvTime = !empty($this->_get['recv_time']) ? $this->_get['recv_time'] : 0;

            $paymentRet = Payment::find()
                ->where(['project_id' => $this->_projectId])
                ->andFilterWhere(['sub_id' => $subId])
                ->andFilterWhere(['pay_status' => Payment::PAYMENT_STATUS_COMPLETED])
//                ->andFilterWhere(['between', 'pay_time', strtotime(date('Y-m-d 00:00:00')), strtotime(date('Y-m-d 23:59:59'))])
                ->orderBy('id DESC')
                ->all();

            $subscribed = Subscribed::find()
                ->where([
                    'project_id' => $this->_projectId,
                ])
                ->one();

            $subTotalPrice = !empty($subscribed->sub_total_price) ? $subscribed->sub_total_price : 0;

            if (empty($subscribed)) {
                return $this->fail('订阅不存在', -1000);
            }

            $recvAmountRet = 0;
            foreach ($paymentRet as $payment) {
                $recvAmountRet += $payment->recv_amount;
            }
            if ($recvAmountRet + $amount >= $subTotalPrice) {
                // Todo: 认购总额超了，应该进入下一个流程了
            }


            $model->payer = $payer;
            $model->sub_id = $subId;
            $model->project_id = $this->_projectId;
            $model->report_id = $this->_reportId;
            $model->pay_time = $payTime;
            $model->pay_way = $payWay;
            $model->pay_status = $payStatus;
            $model->amount = $amount;
            $model->amount_type = $amountType;
            $model->pay_account = $payAccount;
            $model->recv_account = $recvAccount;
            $model->receipt_no = $receiptNo;
            $model->recv_amount = $recvAmount;
            $model->fee = $fee;
            $model->recv_time = $recvTime;


            $model->save();

            $transaction->commit();

            $paymentId = Yii::$app->db->getLastInsertID();

            return $this->success([
                'payment_id' => $paymentId,
                'subscribed' => $subscribed,
                'payment' => $model,
                'recv_amount' => $recvAmountRet + $amount,
                'sub_total_price' => $subTotalPrice,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


}