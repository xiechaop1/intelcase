<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/19
 * Time: 2:37 PM
 */

namespace common\models;


class Log extends \common\models\gii\Log
{

    const OP_STAUTS_ALL             = -1;   // 全部
    const OP_STATUS_SUCCESS         = 1;   // 成功
    const OP_STATUS_FAILED          = 0;    // 失败


    const OP_CODE_ALL               = -1;
    const OP_CODE_LOCK             = 1;    // 锁定
    const OP_CODE_UNLOCK           = 101;    // 解锁
    const OP_CODE_VIEW             = 3;    // 浏览
    const OP_CODE_ORDER            = 102;    // 下单
    const OP_CODE_PAIED            = 103;    // 支付
    const OP_CODE_CANCEL           = 104;    // 取消
    const OP_CODE_COMPLETED        = 105;    // 完成
    const OP_CODE_FAVORITE         = 7;    // 喜欢
    const OP_CODE_LOGIN            = 8;    // 登录
    const OP_CODE_REGISTER         = 9;    // 新用户

    public static $opStatusMap = [
        self::OP_STAUTS_ALL         => '全部',
        self::OP_STATUS_SUCCESS     => '成功',
        self::OP_STATUS_FAILED      => '失败',
    ];

    public static $opCodeMap = [
        self::OP_CODE_ALL           => '全部',
        self::OP_CODE_LOCK          => '锁定',
        self::OP_CODE_UNLOCK        => '解锁',
        self::OP_CODE_VIEW          => '浏览',
        self::OP_CODE_ORDER         => '下单',
        self::OP_CODE_PAIED         => '支付',
        self::OP_CODE_CANCEL        => '取消',
        self::OP_CODE_COMPLETED     => '完成',
        self::OP_CODE_FAVORITE      => '喜欢',
        self::OP_CODE_LOGIN         => '登录',
        self::OP_CODE_REGISTER      => '新用户',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    // 获取用户信息
    public function getUser()
    {
        return $this->hasOne('common\models\User', ['id' => 'user_id']);
    }

    // 获取音乐信息
    public function getMusic()
    {
        return $this->hasOne('common\models\Music', ['id' => 'music_id']);
    }

    public function getCodeName()
    {
        return self::$opCodeMap[$this->op_code];
    }

}