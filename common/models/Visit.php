<?php
/**
 * Created by PhpStorm.
 * User: Choice
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

    public static $visitConfirm2Name = [
        self::VISIT_CONFIRM_STATUS_CONFIRM    => '确认',
        self::VISIT_CONFIRM_STATUS_REJECT     => '拒绝',
        self::VISIT_CONFIRM_STATUS_BUY        => '认购',
        self::VISIT_CONFIRM_STATUS_SIGNED     => '签约',
    ];

    // 投资，自用，租赁
    const VISIT_GUEST_APPEAL_INVESTMENT = 1;    // 投资
    const VISIT_GUEST_APPEAL_SELF_USE = 2;      // 自用
    const VISIT_GUEST_APPEAL_RENT = 3;       // 租赁

    public static $visitGuestAppeal2Name = [
        self::VISIT_GUEST_APPEAL_INVESTMENT => '投资',
        self::VISIT_GUEST_APPEAL_SELF_USE => '自用',
        self::VISIT_GUEST_APPEAL_RENT => '租赁',
    ];


    public function behaviors()
    {

        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function getSubscribed()
    {
        return $this->hasMany(Subscribed::className(), ['mobile' => 'guest_mobile']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
}