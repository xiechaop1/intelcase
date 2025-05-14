<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Tag extends \common\models\gii\Tag
{
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
            'tag_name',
            'tag_type',
        ];
    }

    public function attributeLabels()
    {
        return [
            'tag_name' => 'Tag',
        ];
    }
}