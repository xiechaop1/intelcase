<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


class MusicVideo extends \common\models\gii\MusicVideo
{
    

    const VIDEO_IS_DELETE_ALL   = -1;   // 全部
    const VIDEO_IS_DELETE_NO    = 0;    // 正常
    const VIDEO_IS_DELETE_YES   = 1;    // 下架

    public static $videoIsDelete = [
        self::VIDEO_IS_DELETE_ALL   => '全部',
        self::VIDEO_IS_DELETE_NO    => '正常',
        self::VIDEO_IS_DELETE_YES   => '下架',

    ];

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
            ]
        ];
    }


}