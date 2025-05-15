<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Payment extends \common\models\gii\Payment
{

    const PAYMENT_STATUS_WAIT           = 1;
    const PAYMENT_STATUS_COMPLETED      = 10;

    public $paymentStatus2Name = [
        self::PAYMENT_STATUS_WAIT         => '待支付',
        self::PAYMENT_STATUS_COMPLETED    => '已支付',
    ];


    public function behaviors()
    {

        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }


}