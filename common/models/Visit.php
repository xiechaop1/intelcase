<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Visit extends \common\models\gii\Visit
{

    const VISIT_STATUS_DEFAULT          = 0;
    const VISIT_STATUS_COMPLETED         = 1;
    const VISIT_STATUS_WAIT              = 2;

    public $visitStatus2Name = [
        self::VISIT_STATUS_DEFAULT      => '默认',
        self::VISIT_STATUS_COMPLETED    => '已到访',
        self::VISIT_STATUS_WAIT         => '未到访',
    ];

    const VISIT_CONFIRM_STATUS_CONFIRM  = 1;
    const VISIT_CONFIRM_STATUS_REJECT   = 2;
    const VISIT_CONFIRM_STATUS_BUY      = 3;
    const VISIT_CONFIRM_STATUS_SIGNED    = 4;

    public $visitConfirm2Name = [
        self::VISIT_CONFIRM_STATUS_CONFIRM    => '确认',
        self::VISIT_CONFIRM_STATUS_REJECT     => '拒绝',
        self::VISIT_CONFIRM_STATUS_BUY        => '认购',
        self::VISIT_CONFIRM_STATUS_SIGNED     => '签约',
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