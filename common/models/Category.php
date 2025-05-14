<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Category extends \common\models\gii\Category
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
        return $this->hasMany('common\models\MusicCategory', ['category_id' => 'id'])->limit(20);
    }

    public function fields()
    {
        return [
            'id',
            'sort_by',
            'category_name',
            'category_image',
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
            'category_name' => '分类名',
            'sort_by'  => '排序'
        ];
    }
}