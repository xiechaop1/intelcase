<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/9/27
 * Time: 下午23:06
 */

namespace common\models;

use common\definitions\Common;


class Banner extends \common\models\gii\Banner
{
    const BANNER_STATUS_SHOW    = 1;
    const BANNER_STATUS_HIDE    = 2;

    public static $bannerStatus2Name = Array(
        self::BANNER_STATUS_SHOW        => '显示',
        self::BANNER_STATUS_HIDE        => '隐藏',
    );

    public function exec() {

        return $this->save();
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function fields()
    {
        return [
            'id',
            'page',
            'image',
            'target',
            'subject',
            'online_time',
            'offline_time',
            'sort',
        ];
    }

}