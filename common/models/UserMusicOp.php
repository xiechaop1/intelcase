<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


class UserMusicOp extends \common\models\gii\UserList
{

    const LIST_TYPE_LOCK    = 1;    // 锁定歌曲
    const LIST_TYPE_FAV     = 2;    // 喜欢歌曲
    const LIST_TYPE_BUY     = 3;    // 购买歌曲
    const LIST_TYPE_VIEW    = 4;    // 浏览歌曲

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

    public function getUser(){
        return $this->hasOne('common\models\User',  ['id' => 'user_id']);
    }


}