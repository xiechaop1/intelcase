<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/19
 * Time: 2:37 PM
 */

namespace common\models;


use common\definitions\Common;

class Order extends \common\models\gii\Order
{

    const ORDER_STATUS_ALL          = -1;   // 全部
    const ORDER_STATUS_WAIT         = 0;    // 待支付
    const ORDER_STATUS_LOCK         = 1;    // 已锁定
    const ORDER_STATUS_PAIED        = 2;    // 已支付
    const ORDER_STATUS_COMPLETED    = 3;    // 已结束
    const ORDER_STATUS_CANCELED     = 4;    // 已取消

    public static $orderStatus = [
        self::ORDER_STATUS_ALL          => '全部',
        self::ORDER_STATUS_WAIT         => '待支付',
        self::ORDER_STATUS_LOCK         => '已锁定',
        self::ORDER_STATUS_PAIED        => '购买中',
        self::ORDER_STATUS_COMPLETED    => '已完成',
        self::ORDER_STATUS_CANCELED     => '已取消',
    ];

    public static $orderStatusForEdit = [
        self::ORDER_STATUS_LOCK         => '已锁定',
        self::ORDER_STATUS_PAIED        => '购买中',
        self::ORDER_STATUS_COMPLETED    => '已完成',
        self::ORDER_STATUS_CANCELED     => '已取消',
    ];

    const ORDER_PERMISSION_DOWNLOAD_YES  = 1;    // 允许下载资源包
    const ORDER_PERMISSION_DOWNLOAD_NO   = 0;    // 不允许下载资源包

    public static $orderPermission = [
        self::ORDER_PERMISSION_DOWNLOAD_YES  => '允许下载资源包',
        self::ORDER_PERMISSION_DOWNLOAD_NO   => '不允许下载资源包',
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
        return $this->hasOne('common\models\Music', ['id' => 'music_id'])->onCondition(['o_music.music_status' => Music::MUSIC_STATUS_NORMAL, 'o_music.is_delete' => Common::STATUS_NORMAL])->with('categories');
    }

    public function getMusicwithoutstatus()
    {
        return $this->hasOne('common\models\Music', ['id' => 'music_id'])->with('categories');
    }

}