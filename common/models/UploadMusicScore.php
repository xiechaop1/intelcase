<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


class UploadMusicScore extends \common\models\gii\UploadMusicScore
{

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
            ]
        ];
    }

    public function getUploadMusic(){
        return $this->hasOne('common\models\UploadMusic',  ['id' => 'upload_music_id']);
    }

    public function getUser(){
        return $this->hasOne('common\models\Admin',  ['id' => 'user_id']);
    }

    public function getOpUser(){
        return $this->hasOne('common\models\Admin',  ['id' => 'op_user_id']);
    }


}