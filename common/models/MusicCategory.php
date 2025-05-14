<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


class MusicCategory extends \common\models\gii\MusicCategory
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
            ]
        ];
    }

    public function getMusic(){
        return $this->hasOne('common\models\Music',  ['id' => 'music_id']);
    }

    public function getCategory(){
        return $this->hasOne('common\models\Category',  ['id' => 'category_id']);
    }


}