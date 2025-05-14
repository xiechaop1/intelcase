<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;

use yii;

class UserList extends \common\models\gii\UserList
{

    const LIST_TYPE_LOCK    = 1;    // 锁定歌曲
    const LIST_TYPE_FAV     = 2;    // 喜欢歌曲
    const LIST_TYPE_BUY     = 3;    // 购买歌曲
    const LIST_TYPE_VIEW    = 4;    // 浏览歌曲

    public static $listTypeMap = [
        self::LIST_TYPE_LOCK    => '锁定歌曲',
        self::LIST_TYPE_FAV     => '喜欢歌曲',
        self::LIST_TYPE_BUY     => '购买歌曲',
        self::LIST_TYPE_VIEW    => '浏览歌曲',
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

    public function getUser(){
        return $this->hasOne('common\models\User',  ['id' => 'user_id']);
    }

    public static function getOrCreateUserList($userId, $listType, $userType = 1, $listName = '') {

        $userList = UserList::findOne(['user_id' => $userId, 'list_type' => $listType, 'user_type' => $userType]);
        $listName = !empty($listName) ? $listName : $userId . '_' . self::$listTypeMap[$listType] . '_' . $userType .  '歌单';

        if (empty($userList)) {
            $userList = new UserList();
            $userList->user_id = $userId;
            $userList->list_type = $listType;
            $userList->user_type = $userType;
            $userList->list_name = $listName;
            $userList->exec();
            $userList['id'] = Yii::$app->db->getLastInsertId();
        }

        return $userList;
    }

}