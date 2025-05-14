<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/8/29
 * Time: 下午6:06
 */

namespace common\models;

use common\definitions\Common;


class Image extends \common\models\gii\Image
{

    const IMAGE_TYPE_BOUTIQUE       = 1;

    public static $level2Name = Array(
        self::IMAGE_TYPE_BOUTIQUE       => '精品团图片',
    );

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
            'image',
            'image_introduce',
            'data_id',
            'data_type',
        ];
    }

}