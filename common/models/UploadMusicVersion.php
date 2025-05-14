<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


class UploadMusicVersion extends \common\models\gii\UploadMusicVersion
{


    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
            ]
        ];
    }

    public function exec() {

        $ret = $this->save();
        return $ret;
    }

    public function getFiles() {
        return $this->hasMany('common\models\UploadMusic', ['ver_id' => 'id']);
    }

    public function getScores() {
        return $this->hasMany('common\models\UploadMusicScore', ['ver_id' => 'id']);
    }

}