<?php
/**
 * Created by PhpStorm.
 * User: choiceGroup
 * Date: 2023/5/18
 * Time: 下午6:06
 */

namespace common\models;


use common\definitions\Common;

class User extends \common\models\gii\User
{

    public $lockCount;

    const USER_STATUS_NORMAL    = 0;    // 正常
    const USER_STATUS_FORBIDDEN = 1;    // 禁用
//    const USER_STATUS_INVITED   = 2;    // 被邀请

    public static $userStatus = [
        self::USER_STATUS_NORMAL    => '正常',
        self::USER_STATUS_FORBIDDEN => '禁用',
//        self::USER_STATUS_INVITED   => '未激活',
    ];

    const USER_TYPE_NORMAL = 1; // 普通用户
    const USER_TYPE_INNER  = 2; // 内部用户

    public static $userTypeNameMap = [
        self::USER_TYPE_NORMAL => '野蛮B用户',
        self::USER_TYPE_INNER  => '野蛮A用户',
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

    public function getUserLastOrderCompletedMusic() {
        return $this->_getLastMusicByOrder([Order::ORDER_STATUS_PAIED, Order::ORDER_STATUS_COMPLETED]);
    }

    public function getUserLastOrderPaiedMusic() {
        return $this->_getLastMusicByOrder([Order::ORDER_STATUS_PAIED, Order::ORDER_STATUS_COMPLETED]);
    }

    public function getUserLastLockMusic() {
//        $order = Order::find()->where(['user_id' => $this->id, 'order_status' => Order::ORDER_STATUS_LOCK])->orderBy(['updated_at' => SORT_DESC])->limit(10);
//        if ($order) {
//            $music = Music::find()
//                ->where(['id' => $order->music_id])
//                ->andFilterWhere(['music_status' => Music::MUSIC_STATUS_NORMAL])
//                ->andFilterWhere(['is_delete' => Common::STATUS_NORMAL])
//                ->one()
//                ->toArray();
//            $music = \common\helpers\Music::formatSource($music);
//            return $music;
//        }
//        return [];
        return $this->_getLastMusicByOrder(Order::ORDER_STATUS_LOCK);
    }

    private function _getLastMusicByOrder($orderStatus) {
        $musicList = Order::find()
            ->select('o_order.*, o_music.*')
            ->joinWith('musicwithoutstatus')
            ->where(['o_order.user_id' => $this->id, 'order_status' => $orderStatus])
            ->andFilterWhere([
                'o_music.is_delete' => Common::STATUS_NORMAL
            ])
            ->orderBy(['o_order.updated_at' => SORT_DESC])
            ->one();

        if ($musicList['musicwithoutstatus']) {
            $music = $musicList['musicwithoutstatus']->toArray();
            $music = \common\helpers\Music::formatSource($music);
            return $music;
        }
        return [];
//            ->one();
    }

    public function getUserLastViewMusic() {
        return $this->_getLastMusicByListType(UserList::LIST_TYPE_VIEW);
    }

    public function getUserLastFavMusic() {
        return $this->_getLastMusicByListType(UserList::LIST_TYPE_FAV);
    }

    private function _getLastMusicByListType($listType) {
        $userList = UserList::findOne(['user_id' => $this->id, 'list_type' => $listType, 'user_type' => $this->user_type]);
        if ($userList) {
            $musicList = UserMusicList::find()
                ->select('o_user_music_list.*, o_music.*')
                ->joinWith('musicwithoutstatus')
                ->where(['o_user_music_list.list_id' => $userList->id])
                ->andFilterWhere([
                    'o_music.is_delete' => Common::STATUS_NORMAL
                ])
                ->andFilterWhere([
                    'or',
                    ['o_music.music_status' => Music::MUSIC_STATUS_NORMAL],
                    ['o_music.op_user_id' => $this->id]
                ])
                ->orderBy(['o_user_music_list.updated_at' => SORT_DESC])
//                ->createCommand()->getRawSql();
//            var_dump($musicList);exit;
                ->one();

            if ($musicList['musicwithoutstatus']) {
                $music = $musicList['musicwithoutstatus']->toArray();
//                $music = Music::find()
//                    ->where(['id' => $musicList->music_id])
//                    ->andFilterWhere(['music_status' => Music::MUSIC_STATUS_NORMAL])
//                    ->andFilterWhere(['is_delete' => Common::STATUS_NORMAL])
//                    ->one()->toArray();
                $music = \common\helpers\Music::formatSource($music);
                return $music;
            }
        }
        return [];
    }

    public function getUserLockCount() {
        $orderCount = Order::find()->where(['user_id' => $this->id, 'order_status' => Order::ORDER_STATUS_LOCK])->count();
        return $orderCount;
    }

    public function getUserFavCount() {
        $userList = UserList::findOne(['user_id' => $this->id, 'list_type' => UserList::LIST_TYPE_FAV, 'user_type' => $this->user_type]);
        return $userList ? $userList->ct : 0;
    }

    public function getUserViewCount() {
        $userList = UserList::findOne(['user_id' => $this->id, 'list_type' => UserList::LIST_TYPE_VIEW, 'user_type' => $this->user_type]);
        return $userList ? $userList->ct : 0;
    }

    public function getUserOrderCompletedCount() {
        $musicType = \common\helpers\Music::transUserTypeToMusicType($this->user_type);
        return Order::find()
            ->joinWith('musicwithoutstatus')
            ->where(['user_id' => $this->id, 'order_status' => Order::ORDER_STATUS_COMPLETED])
//            ->andFilterWhere(['o_music.music_type' => $musicType])
            ->count();
    }

    public function getUserOrderPaiedCount() {
        $musicType = \common\helpers\Music::transUserTypeToMusicType($this->user_type);
        return Order::find()
            ->joinWith('musicwithoutstatus')
            ->where(['user_id' => $this->id, 'order_status' => Order::ORDER_STATUS_PAIED])
//            ->andFilterWhere(['o_music.music_type' => $musicType])
            ->count();
    }

    public function getUserOrderCanceledCount() {
        return Order::find()
            ->with('musicwithoutstatus')
            ->where(['user_id' => $this->id, 'order_status' => Order::ORDER_STATUS_CANCELED])
            ->count();
    }
}