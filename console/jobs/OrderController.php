<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 10:18 PM
 */

namespace console\controllers;


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
        $query->andWhere(['>', 'expire_time', time()]);
        $order = $query->all();

        foreach ($order as $o) {
            var_dump($o);
        }
    }
}