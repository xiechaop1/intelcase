<?php
/**
 * Created by PhpStorm.
 * Order: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\order;


use common\definitions\Common;
use common\models\Music;
use common\models\Order;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;

class Orders extends Action
{
    public function run()
    {
        $orderId = Net::post('data-id');
        if ($orderId) {
            $model = \backend\models\Order::findOne($orderId);
            $isNew = false;
        }

        if (empty($model)) {
            $model = new \backend\models\Order();
            $isNew = true;
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $order = \common\models\Order::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($order) {
                        $order->is_delete = Common::STATUS_DELETED;
                        if ($order->save()) {

                        }
                        Yii::$app->session->setFlash('success', '操作成功');
                    } else {
                        Yii::$app->session->setFlash('fail', '操作失败');
                    }
                    break;
                case 'confirm':
                    if ($order
                        && $order->order_status == Order::ORDER_STATUS_PAIED
                    ) {
                        $order->order_status = Order::ORDER_STATUS_COMPLETED;
                        if ($order->save()) {

                        }
                        Yii::$app->session->setFlash('success', '操作成功');
                    } else {
                        Yii::$app->session->setFlash('danger', '订单当前不是已支付状态');
                    }

                    break;
                case 'reset':
                    if ($order) {
                        $order->is_delete = Common::STATUS_NORMAL;
                        if ($order->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                default:
                    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                    $model->load(Yii::$app->request->post());
                    return ActiveForm::validate($model);
            }

            return $this->controller->responseAjax(1, '');
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $transaction = Yii::$app->db->beginTransaction();
            if ($model->order_status == Order::ORDER_STATUS_LOCK) {
                // 锁定音乐
                $musicInfo = Music::findOne($model->music_id);
                if ($musicInfo) {
                    $musicInfo->music_status = Music::MUSIC_STATUS_LOCK;
                    $musicInfo->save();
                }
            } else if ($model->order_status == Order::ORDER_STATUS_COMPLETED) {
                $musicInfo = Music::findOne($model->music_id);
                if ($musicInfo) {
                    $musicInfo->music_status = Music::MUSIC_STATUS_BOUGHT;
                    $musicInfo->save();
                }
            } else {
                $musicInfo = Music::findOne($model->music_id);
                if ($musicInfo) {
                    $musicInfo->music_status = Music::MUSIC_STATUS_NORMAL;
                    $musicInfo->save();
                }
            }

            if ($model->save()) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', '操作成功');
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', '操作失败');
            }
            return $this->controller->refresh();
        }

        $searchModel = new \backend\models\Order();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

        $orderStatusList = [
            Order::ORDER_STATUS_ALL         => Order::$orderStatus[Order::ORDER_STATUS_ALL],
            Order::ORDER_STATUS_PAIED       => Order::$orderStatus[Order::ORDER_STATUS_PAIED],
            Order::ORDER_STATUS_COMPLETED   =>  Order::$orderStatus[Order::ORDER_STATUS_COMPLETED],
        ];

        return $this->controller->render('orders', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'orderModel'    => $model,
            'orderStatus'   => Order::$orderStatus,
            'params'        => $_GET,
            'orderStatusList'   => $orderStatusList,
        ]);
    }
}