<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 10:18 PM
 */

namespace console\controllers;


use common\models\Log;
use common\models\Music;
use common\models\Order;
use yii\console\Controller;
use yii;

class OrderController extends Controller
{
    public function actionTimeout()
    {
        $query = Order::find()->where([
            'order_status' => Order::ORDER_STATUS_LOCK,
        ]);
        $query->with('musicwithoutstatus');
        $query->andFilterWhere(['<', 'expire_time', time()]);
        $query->andFilterWhere(['>', 'expire_time', 0]);
        $order = $query->all();

        foreach ($order as $o) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $o->order_status = Order::ORDER_STATUS_WAIT;
                $o->expire_time = 0;
                $o->save();

                $o->musicwithoutstatus->music_status = Music::MUSIC_STATUS_NORMAL;
                $o->musicwithoutstatus->save();

                Yii::$app->oplog->write(\common\models\Log::OP_CODE_UNLOCK, Log::OP_STATUS_SUCCESS, 0, $o->musicwithoutstatus->id, '系统超时解锁，原订单：' . json_encode($o->toArray()));

                $transaction->commit();
                echo $o->id . '更新成功';
                echo "\n";
            } catch (\Exception $e) {
                $transaction->rollBack();
                echo $o->id . '更新失败' . ' ' . $e->getMessage();
                echo "\n";
            }
        }
    }
}