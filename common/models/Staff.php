<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Staff extends \common\models\gii\Staff
{

    const STAFF_ROLE_SALES          = 1;      // 销售
    const STAFF_ROLE_PM             = 2;      // 项目经理
    const STAFF_ROLE_FINANCE        = 3;      // 财务

    const STAFF_STATUS_NORMAL       = 1;      // 正常
    const STAFF_STATUS_DISABLE      = 2;      // 禁用

    public static $staffRole2Name = [
        self::STAFF_ROLE_SALES         => '销售',
        self::STAFF_ROLE_PM            => '项目经理',
        self::STAFF_ROLE_FINANCE       => '财务',
    ];

    public static $staffStatus2Name = [
        self::STAFF_STATUS_NORMAL       => '正常',
        self::STAFF_STATUS_DISABLE      => '禁用',
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