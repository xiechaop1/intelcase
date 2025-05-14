<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


use common\definitions\Common;

class UserMusicList extends \common\models\gii\UserMusicList
{

//    const LIST_TYPE_LOCK    = 1;    // 锁定歌曲
//    const LIST_TYPE_FAV     = 2;    // 喜欢歌曲
//    const LIST_TYPE_BUY     = 3;    // 购买歌曲
//    const LIST_TYPE_VIEW    = 4;    // 浏览歌曲
//
//    public static $listTypeMap = [
//        self::LIST_TYPE_LOCK    => '锁定歌曲',
//        self::LIST_TYPE_FAV     => '喜欢歌曲',
//        self::LIST_TYPE_BUY     => '购买歌曲',
//        self::LIST_TYPE_VIEW    => '浏览歌曲',
//    ];

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

    public function getMusic(){
        return $this->hasOne('common\models\Music',  ['id' => 'music_id'])->onCondition(['music_status' => Music::MUSIC_STATUS_NORMAL, 'music_type' => Music::MUSIC_TYPE_NORMAL, 'is_delete' => Common::STATUS_NORMAL])->with('categories');
    }

    public function getMusicwithoutstatus(){
        return $this->hasOne('common\models\Music',  ['id' => 'music_id'])->with('categories');
    }

    public function getList(){
        return $this->hasOne('common\models\UserList',  ['id' => 'list_idx']);
    }


}