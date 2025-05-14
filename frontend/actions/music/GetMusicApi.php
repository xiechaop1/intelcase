<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\music;


use common\definitions\Common;
use common\models\Category;
use common\models\Order;
use common\models\Music;
use common\models\MusicLibrary;
use common\models\MusicCategory;
use common\models\User;
use common\models\UserMusicList;
use common\models\UserList;
use frontend\actions\ApiAction;
use yii;

class GetMusicApi extends ApiAction
{
    public $action;

    private $_userId;

    private $_user;

    private $_userType;
    private $_musicType;

    private $_get;

    private $_lockList;
    private $_lockListMusicIds = [];
    private $_favList;
    private $_favListMusicIds = [];

    public function run()
    {
        $this->_get = Yii::$app->request->get();

        $this->_userId = !empty($this->_get['user_id']) ? $this->_get['user_id'] : 0;

        $this->_musicType = Music::MUSIC_TYPE_UNAUTHORIZATION;

        if (!empty($this->_userId)) {
            $this->_user = User::findOne($this->_userId);

            $this->_userType = !empty($this->_user['user_type']) ? $this->_user['user_type'] : User::USER_TYPE_NORMAL;

            $this->_musicType = \common\helpers\Music::transUserTypeToMusicType($this->_user['user_type']);

//            if (!empty($this->_user['user_type'])) {
//                switch ($this->_user['user_type']) {
//                    case User::USER_TYPE_NORMAL:
//                        $this->_musicType = Music::MUSIC_TYPE_NORMAL;
//                        break;
//                    case User::USER_TYPE_INNER:
//                        $this->_musicType = Music::MUSIC_TYPE_STATIC;
//                        break;
//                    default:
//                        $this->_musicType = Music::MUSIC_TYPE_NORMAL;
//                        break;
//                }
//            }
        }

        // 获取用户的锁定列表和收藏列表

        if ($this->action != 'all_music_by_order'
            || (!empty($this->_get['order_status']) && $this->_get['order_status'] != Order::ORDER_STATUS_LOCK)) {
            $this->_lockList = Order::find()
                ->where([
//                        'user_id' => $this->_userId,
                    'order_status' => Order::ORDER_STATUS_LOCK
                ])
                ->andFilterWhere([
                    '>=', 'expire_time', time()
                ])
                ->all();
        }

        if (!empty($this->_userId)) {

            if ($this->action != 'all_music_by_list'
                || (!empty($this->_get['list_type']) && $this->_get['list_type'] != UserList::LIST_TYPE_FAV)
            ) {
                $favListRet = UserList::find()
                    ->where([
                        'user_id' => $this->_userId,
                        'list_type' => UserList::LIST_TYPE_FAV,
                        'user_type' => $this->_userType,
                    ])
                    ->one();

                $favListId = !empty($favListRet['id']) ? $favListRet['id'] : 0;

                $this->_favList = UserMusicList::find()
                    ->where([
                        'list_id' => $favListId,
                    ])
                    ->all();
            }

        }

        switch ($this->action) {
            case 'one':
                $ret = $this->get_music_one();
                break;
            case 'all_music_by_list':
                $ret = $this->get_all_music_by_list();
                break;
            case 'all_music_by_category':
                $ret = $this->get_all_music_by_category();
                break;
            case 'all_music_by_library':
                $ret = $this->get_all_music_by_library();
                break;
            case 'all_music_by_order':
                $ret = $this->get_all_music_by_order();
                break;
            default:
                $ret = [];
                break;

        }

        return $this->success($ret);
    }


    public function get_music_one(){

        $ret = [];

        $musicId = !empty($this->_get['id']) ? $this->_get['id'] : 0;

        $ret = Music::find()
            ->where(['id' => $musicId])
//            ->andFilterWhere(['music_type' => $this->_musicType])
//            ->with('categories')
            ->one()
            ->toArray();

        if (!empty($ret)) {

//
//            $ret['lyricJson'] = \common\helpers\Music::formatLyricToJson($ret['lyric']);
//
////            $ret['singer'] = \common\models\Singer::findOne($ret['singer_id']);
            $categories = \common\models\MusicCategory::find()
                ->with('category')
                ->where(['music_id' => $ret['id']])
                ->all();
            foreach ($categories as $cat) {
                if (!empty($cat->category)) {
                        $category = $cat->category->toArray();

                }
                $cat = $cat->toArray();
                $cat['category'] = $category;

//                $ret = $ret->toArray();
                $ret['categories'][] = $cat;
            }
//            var_dump($ret);exit;
//            if (!empty($ret['categories'])) {
//                foreach ($ret['categories'] as &$category) {
//                    $category = $category->toArray();
//                    $category['category'] = Category::findOne($category['category_id']);
//                    $category['category']['category_image'] = !empty($category['category']['category_image']) ? \common\helpers\Attachment::completeUrl($category['category']['category_image']) : '';
//                }
//            }
//            $ret['chorus_start_time_int'] = \common\helpers\Time::formatTimeToInt($ret['chorus_start_time']);
//            $ret['chorus_end_time_int'] = \common\helpers\Time::formatTimeToInt($ret['chorus_end_time']);
//            $ret = \common\helpers\Music::formatSource($ret);

            $this->_getOpList();
            $ret = $this->_formatMusic($ret);
        }

        return $ret;

    }

    public function get_all_music_by_list(){

        $isRandom = !empty($this->_get['is_random']) ? $this->_get['is_random'] : 0;
        $listId = !empty($this->_get['list_id']) ? $this->_get['list_id'] : 0;

        if (empty($listId)) {
            $userId = !empty($this->_get['user_id']) ? $this->_get['user_id'] : 0;
            $listType = !empty($this->_get['list_type']) ? $this->_get['list_type'] : 0;

            $listInfo = UserList::find()
                ->where([
                    'user_id' => $userId,
                    'list_type' => $listType,
                    'user_type' => $this->_userType,
                ])
                ->one();

            $listId = !empty($listInfo['id']) ? $listInfo['id'] : 0;
        }

        if (!$listId) {
            return [];
        }

        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $limit = !empty($this->_get['limit']) ? $this->_get['limit'] : 20;
        $offset = ($page-1) * $limit;


        $ret = UserMusicList::find()
            ->select('o_user_music_list.*, o_music.*')
            ->joinWith(['musicwithoutstatus'])
//            ->joinWith(['music' => function ($model) {
//                $model->onCondition(['o_music.music_status' => Music::MUSIC_STATUS_NORMAL]);
//            }])
            ->where(['list_id' => $listId])
            ->andFilterWhere([
                'o_music.is_delete' => Common::STATUS_NORMAL
            ])
            ->andFilterWhere([
                'o_music.music_type' => $this->_musicType
            ])
            ->andFilterWhere([
                'or',
                ['o_music.music_status' => Music::MUSIC_STATUS_NORMAL],
                ['o_music.op_user_id' => $this->_userId]
            ]);
//            ->orFilterWhere([
//                'and',
//                ['expire_time' => 0],
//                ['>', 'expire_time', time()]
//            ])
//            ->with('musicwithoutstatus')
            if ($isRandom) {
                $ret = $ret->orderBy('rand()');
            } else {
                $ret = $ret->orderBy(['o_user_music_list.updated_at' => SORT_DESC]);
            }
            $ret = $ret->offset($offset)
            ->limit($limit)
//            ->createCommand()
//            ->getRawSql();
//        var_dump($ret);exit;
            ->asArray()
            ->all();

            if ($listType == UserList::LIST_TYPE_FAV) {
                $this->_favList = $ret;
            }

            $musicRet = [];
            $this->_getOpList();
            foreach ($ret as $row) {
                $musicRet[] = $this->_formatMusic($row['musicwithoutstatus']);
            }
            $ret = $musicRet;

//        $idsQuery = UserMusicList::find()
//            ->select('music_id')
//            ->where(['list_id' => $listId]);
//
//        $ret = Music::find()
//            ->with('categories')
//            ->where(['in', 'id', $idsQuery])
//            ->andFilterWhere([
//                'or',
//                ['music_status' => Music::MUSIC_STATUS_NORMAL],
//                ['op_user_id' => $this->_userId]
//            ])
//            ->andFilterWhere(['is_delete' => \common\definitions\Common::STATUS_NORMAL]);
//            if ($isRandom) {
//                $ret = $ret->orderBy('rand()');
//            } else {
//                $ret = $ret->orderBy(['updated_at' => SORT_DESC]);
//            }
//            $ret = $ret->offset($offset)
//                ->limit($limit)
//    //            ->createCommand()
//    //            ->getRawSql();
//    //        var_dump($ret);exit;
//                ->asArray()
//                ->all();
//var_dump($ret);exit;
//            $ret = $this->_formatAllMusic($ret);

//        foreach ($ret as &$row) {
////            if (!empty($row['music'])) {
////                $row['music'] = \common\helpers\Music::formatSource($row['music']);
////            }
////            if (!empty($row['musicwithoutstatus'])) {
////                $row['musicwithoutstatus'] = \common\helpers\Music::formatSource($row['musicwithoutstatus']);
////            }
//            $row = \common\helpers\Music::formatSource($row);
//        }
//
        return $ret;

    }

    public function get_all_music_by_order(){


        $orderStatus = !empty($this->_get['order_status']) ? $this->_get['order_status'] : 0;

        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $limit = !empty($this->_get['limit']) ? $this->_get['limit'] : 20;
        $offset = ($page-1) * $limit;

        $model = Order::find()
            ->joinWith('musicwithoutstatus','user')
            ->where(['user_id' => $this->_userId])
            ->andFilterWhere(['o_music.is_delete' => Common::STATUS_NORMAL]);
//            ->andFilterWhere(['o_music.music_type' => $this->_musicType]);

        if (!empty($orderStatus)) {
            // 如果是已完成，则兼容已支付和已完成
            if ($orderStatus == Order::ORDER_STATUS_COMPLETED
                || $orderStatus == Order::ORDER_STATUS_PAIED
            ) {
                $orderStatus = [Order::ORDER_STATUS_PAIED, Order::ORDER_STATUS_COMPLETED];
            }
            $model->andWhere(['order_status' => $orderStatus]);
        }

//            ->orFilterWhere([
//                'and',
//                ['expire_time' => 0],
//                ['>', 'expire_time', time()]
//            ])
        $model->orderBy(['o_order.updated_at' => SORT_DESC])
                ->offset($offset)
                ->limit($limit);
        $ret = $model->asArray()
                ->all();

        if ($orderStatus == Order::ORDER_STATUS_LOCK) {
            $this->_lockList = $ret;
        }

        $this->_getOpList();
        foreach ($ret as &$row) {
            $row['created_at_friendly'] = Date('Y.m.d', $row['created_at']);
            $row['updated_at_friendly'] = Date('Y.m.d', $row['updated_at']);
            $row['expire_time_friendly'] = Date('Y.m.d', $row['expire_time']);
//            $row['musicwithoutstatus'] = \common\helpers\Music::formatSource($row['musicwithoutstatus']);

            $row['musicwithoutstatus'] = $this->_formatMusic($row['musicwithoutstatus']);
//            $row['download_resource_url'] = \common\helpers\Attachment::completeUrl($row['download_resource_url'], false);
        }

        return $ret;

    }

    public function get_all_music_by_category(){


        $categoryId = !empty($this->_get['category_id']) ? $this->_get['category_id'] : 0;

        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $limit = !empty($this->_get['limit']) ? $this->_get['limit'] : 20;
        $offset = ($page-1) * $limit;

//        $ret = MusicCategory::find()
//            ->joinWith(['music' => function ($model) {
//                $model->onCondition(['o_music.music_status' => Music::MUSIC_STATUS_NORMAL]);
//            }])
//            ->where(['category_id' => $categoryId])
//            ->with('music')
//            ->offset($offset)
//            ->limit($limit)
//            ->asArray()
//            ->all();

        $idsQuery = MusicCategory::find()
            ->select('music_id')
            ->where(['category_id' => $categoryId]);

        $ret = Music::find()
            ->where([
                'in', 'id', $idsQuery
            ])
            ->andWhere(['music_status' => Music::MUSIC_STATUS_NORMAL])
            ->andFilterWhere(['music_type' => $this->_musicType])
            ->andFilterWhere(['is_delete' => \common\definitions\Common::STATUS_NORMAL])
            ->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();

        $ret = $this->_formatAllMusic($ret);
//        if (!empty($ret)) {
//            foreach ($ret as &$row) {
//                $row = $this->_formatMusic($row);
////                $row = \common\helpers\Music::formatSource($row);
////                if (!empty($row['categories'])) {
////                    foreach ($row['categories'] as &$category) {
////                        $category['category'] = \common\helpers\Music::formatCategoryImage($category['category']);
////                    }
////                    $row['lyric_json'] = \common\helpers\Music::formatLyricToJson($row['lyric']);
////                }
//            }
//        }

        return $ret;

    }

    public function get_all_music_by_library(){

        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $limit = !empty($this->_get['limit']) ? $this->_get['limit'] : 10;
//        $limit = 10;    // Todo: 强制10首，等上线以后再变
        $isRandom = !empty($this->_get['is_random']) ? $this->_get['is_random'] : 0;
        $offset = ($page-1) * $limit;

        // 从Session中取出已经浏览过的歌曲
        $viewedMusic = Yii::$app->session->get('viewed_music');
        if (empty($viewedMusic)) {
            $viewedMusic = [];
        }

        if (!empty($this->_userId)) {
            $ret = Music::find()
                ->leftJoin('o_user_music_list', 'o_user_music_list.music_id = o_music.id and o_user_music_list.user_id = ' . $this->_userId . ' and o_user_music_list.list_type = ' . UserList::LIST_TYPE_VIEW)
                ->where(['music_status' => Music::MUSIC_STATUS_NORMAL])
                ->andFilterWhere(['music_type' => $this->_musicType])
                ->andFilterWhere(['is_delete' => \common\definitions\Common::STATUS_NORMAL]);
            if (!empty($viewedMusic)
                && sizeof($viewedMusic) < Yii::$app->params['maxViewCount']
            ) {
                $ret = $ret->andFilterWhere(['not in', 'o_music.id', $viewedMusic]);
            }
        } else {
            $ret = Music::find()
                ->where(['music_status' => Music::MUSIC_STATUS_NORMAL])
                ->andFilterWhere(['music_type' => $this->_musicType])
                ->andFilterWhere(['is_delete' => \common\definitions\Common::STATUS_NORMAL]);
        }
        $ret = $ret->with('categories', 'singer', 'opUser');
        if ($isRandom && !empty($this->_userId)) {
            $ret->orderBy('o_user_music_list.ct ASC, rand()');
        } else {
            $ret->orderBy(['updated_at' => SORT_DESC]);
        }
//        var_dump($ret->createCommand()->getRawSql());exit;
        $ret = $ret->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();

        $ret = $this->_formatAllMusic($ret);
//        foreach ($ret as &$row) {
//            $row = $this->_formatMusic($row);
////            $row = \common\helpers\Music::formatSource($row);
////            foreach ($row['categories'] as &$category) {
////                $category['category'] = \common\helpers\Music::formatCategoryImage($category['category']);
////            }
////            $row['lyric_json'] = \common\helpers\Music::formatLyricToJson($row['lyric']);
//        }
//
//        return $ret;

//        $libraryId = !empty($this->_get['library_id']) ? $this->_get['library_id'] : 0;
//
//        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
//        $limit = !empty($this->_get['limit']) ? $this->_get['limit'] : 20;
//        $offset = ($page-1) * $limit;
//
//        $ret = MusicLibrary::find()
//            ->joinWith(['music' => function ($model) {
//                $model->onCondition(['o_music.music_status' => Music::MUSIC_STATUS_NORMAL]);
//            }])
//            ->with('music');
//        if ($libraryId) {
//            $ret = $ret->where(['library_id' => $libraryId]);
//        }
//            $ret = $ret->offset($offset)
//            ->limit($limit)
//            ->asArray()
//            ->all();
//
        return $ret;

    }

    private function _formatMusic($row) {
        $row = \common\helpers\Music::formatSource($row);
        if (!empty($row['categories'])) {
            foreach ($row['categories'] as &$category) {
                $category['category'] = \common\helpers\Music::formatCategoryImage($category['category']);
            }
        }
        if (!empty($row['lyric_url'])) {
            if (file_exists($row['lyric_url'])) {
                $lyricTxt = file_get_contents($row['lyric_url']);
                $row['lyric_txt'] = $lyricTxt;
            }
//            $row['lyric_json'] = \common\helpers\Music::formatLyricToJson($lyricTxt);
        } else {
            $row['lyric_txt'] = \common\helpers\Music::formatLyricToTxt($row['lyric']);
//            $row['lyric_json'] = \common\helpers\Music::formatLyricToJson($row['lyric']);
        }

        $row['is_lock_by_user_id'] = isset($this->_lockListMusicIds[$row['id']]) ? $this->_lockListMusicIds[$row['id']] : 0;
        $row['is_fav'] = isset($this->_favListMusicIds[$row['id']]) ? 1 : 0;


        return $row;
    }

    private function _getOpList() {
        if (!empty($this->_lockList) && empty($this->_lockListMusicIds)) {
            foreach ($this->_lockList as $llrow) {
                $this->_lockListMusicIds[$llrow['music_id']] = $llrow['user_id'];
            }
        }

        if (!empty($this->_favList) && empty($this->_favListMusicIds)) {
            foreach ($this->_favList as $flrow) {
                $this->_favListMusicIds[$flrow['music_id']] = $flrow['id'];
            }
        }
    }
    private function _formatAllMusic($rows) {
        $this->_getOpList();
        if (!empty($rows)) {
            foreach ($rows as &$row) {
                $row = $this->_formatMusic($row);
            }
        }
        return $rows;
    }

}