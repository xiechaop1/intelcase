<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Library extends \common\models\gii\Library
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function getMusicTop()
    {
        return $this->hasMany('common\models\MusicLibrary', ['library_id' => 'id'])->limit(20);
    }

    public function fields()
    {
        return [
            'id',
            'type',
            'library_name',
            'image',
            'category_id',
        ];
    }

    public function extraFields()
    {
        return [
            'getMusicTop' => function ($model) {
                if ($model->children) {
                    $musicTop = array_map(function ($item) {
                        return $item->toArray();
                    }, $model->getMusicTop);

                    return $musicTop;
                }
                return [];
            }
        ];
    }

    public function attributeLabels()
    {
        return [
            'library_name' => '曲库名',
            'image'  => '封面图',
            'type'  => '类型',
            'category_id'   => '曲库分类',
        ];
    }
}