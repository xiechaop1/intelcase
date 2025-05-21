<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Subscribed extends \common\models\gii\Subscribed
{
    const SUBSCRIBED_STATUS_DEFAULT      = 0;   // 默认
    const SUBSCRIBED_STATUS_CONFIRM     = 1;    // 确认
    const SUBSCRIBED_STATUS_REJECT      = 2;    // 拒绝

    public static $subscribedStatus2Name = [
        self::SUBSCRIBED_STATUS_DEFAULT    => '默认',
        self::SUBSCRIBED_STATUS_CONFIRM    => '确认',
        self::SUBSCRIBED_STATUS_REJECT     => '拒绝',
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