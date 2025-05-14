<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/10/29
 * Time: 7:37 PM
 */

namespace common\models;


class Orders extends \common\models\gii\Orders
{
    const ORDER_STATUS_NO_RECOMMEND = 0;

    const ORDER_STATUS_RECOMMEND = 10;

    public static $orderStatus2Name = [
        self::ORDER_STATUS_NO_RECOMMEND     => '未推荐',
        self::ORDER_STATUS_RECOMMEND        => '已推荐',
    ];

    const ALARM_COMPLETED                   = 1;
    const NO_ALARM                          = 0;


    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function getMember()
    {
        return $this->hasOne('common\models\Member', ['id' => 'user_id']);
    }

    public function getJob()
    {
        return $this->hasOne('common\models\Job', ['id' => 'post_id']);
    }

    public function getCompany()
    {
        return $this->hasOne('common\models\Company', ['id' => 'company_id']);
    }

    public function getRecommends()
    {
        return $this->hasMany('common\models\Recommend', ['user_id' => 'user_id', 'post_id' => 'post_id']);
    }

    public function getAllRecommends()
    {
        return $this->hasMany('common\models\Recommend', ['id' => 'order_id']);
    }

//    public function getDocument()
//    {
//        return $this->hasOne('common\models\Document', ['id' => 'document_id']);
//    }


}