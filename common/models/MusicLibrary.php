<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


class MusicLibrary extends \common\models\gii\MusicLibrary
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

    public function getLibrary(){
        return $this->hasOne('common\models\Library',  ['id' => 'library_id']);
    }


}