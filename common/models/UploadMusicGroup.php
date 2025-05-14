<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


class
UploadMusicGroup extends \common\models\gii\UploadMusicGroup
{

    const MUSIC_TYPE_NORMAL     = 1;        // 正常
    const MUSIC_TYPE_STATIC     = 2;        // 静态
    const MUSIC_TYPE_TEACH      = 3;        // 教学

    const MUSIC_TYPE_UNAUTHORIZATION = 4;    // 未授权

    public static $musicType = [
        self::MUSIC_TYPE_NORMAL     => '正常',
        self::MUSIC_TYPE_STATIC     => '静态',
        self::MUSIC_TYPE_TEACH      => '教学',
        self::MUSIC_TYPE_UNAUTHORIZATION => '未授权',
    ];

    public static $musicNormalType = [
        self::MUSIC_TYPE_NORMAL     => '正常',
        self::MUSIC_TYPE_UNAUTHORIZATION => '未授权',
    ];

    const MUSIC_STATUS_ALL      = -1;       // 全部
    const MUSIC_STATUS_LOCK     = 1;        // 锁定
    const MUSIC_STATUS_NORMAL   = 0;      // 正常
    const MUSIC_STATUS_BOUGHT   = 3;    // 被购买

    public static $musicStatus = [
        self::MUSIC_STATUS_ALL      => '全部',
        self::MUSIC_STATUS_NORMAL   => '正常',
        self::MUSIC_STATUS_LOCK     => '锁定',
        self::MUSIC_STATUS_BOUGHT   => '已购买',
    ];

    const MUSIC_IS_DELETE_ALL   = -1;   // 全部
    const MUSIC_IS_DELETE_NO    = 0;    // 正常
    const MUSIC_IS_DELETE_YES   = 1;    // 下架

    public static $musicIsDelete = [
        self::MUSIC_IS_DELETE_ALL   => '全部',
        self::MUSIC_IS_DELETE_NO    => '正常',
        self::MUSIC_IS_DELETE_YES   => '下架',

    ];

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

    public function getCategories() {
        return $this->hasMany('common\models\MusicCategory', ['music_id' => 'id'])->with('category');
    }
    public function getUploadUser(){
        return $this->hasOne('common\models\User',  ['id' => 'upload_user_Id']);
    }

    public function getLatestUploadUser(){
        return $this->hasOne('common\models\User',  ['id' => 'latest_upload_user_id']);
    }

    public function getLatestUploadMusic(){
        return $this->hasOne('common\models\UploadMusic',  ['id' => 'latest_upload_music_id']);
    }

    public function getMusicList() {
        return $this->hasMany('common\models\UploadMusic',  ['group_id' => 'id'])->with('user')->orderBy(['created_at' => SORT_DESC]);
    }

}