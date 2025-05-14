<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\music;


use common\definitions\Common;
use common\models\Order;
use common\models\User;
use common\models\UserList;
use common\models\UserMusicList;
use common\models\Music;
use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class OpApi extends ApiAction
{
    public $action;
    private $_get;
    private $_musicId;
    private $_userId;

    private $_userInfo;
    private $_userType;
    private $_musicInfo;

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();

            $this->_userId = !empty($this->_get['user_id']) ? (int)$this->_get['user_id'] : 0;

            if (empty($this->_userId)) {
                return $this->fail('请您给出用户信息', -199);
            }

            $this->_userInfo = User::findOne($this->_userId);
            $this->_userType = User::USER_TYPE_NORMAL;
            if (!empty($this->_userInfo)) {
                $this->_userType = $this->_userInfo['user_type'];
            }

            if (empty($this->_get['music_id'])) {
                return $this->fail('请您给出歌曲信息', -100);
            } else {
                $this->_musicId = (int)$this->_get['music_id'];

                // 检查音乐是否存在
                $this->_musicInfo = Music::findOne($this->_musicId);
                if (empty($this->_musicInfo)) {
                    return $this->fail('歌曲不存在', -101);
                }
            }

            $this->valToken();
            switch ($this->action) {
                case 'view':
                    $ret = $this->view();
                    break;
                case 'fav':
                    $ret = $this->fav();
                    break;
                case 'unview':
                    $ret = $this->unView();
                    break;
                case 'unfav':
                    $ret = $this->unFav();
                    break;
                default:
                    $ret = [];
                    break;

            }
        } catch (\Exception $e) {
            $ret = $this->fail($e->getCode() . ': ' . $e->getMessage());
        }

        return $ret;
    }

    public function view() {

        $model = new UserMusicList();
        $transaction = Yii::$app->db->beginTransaction();

        try {
//            $listInfo = UserList::findOne([
//                'user_id' => $this->_userId,
//                'list_type' => UserList::LIST_TYPE_VIEW,
//            ]);
//
//            if (empty($listInfo)) {
//                $listModel = new UserList();
//                $listModel->user_id = $this->_userId;
//                $listModel->list_name = $this->_userId . '用户浏览歌单';
//                $listModel->list_type = UserList::LIST_TYPE_VIEW;
//                $listModel->save();
//                $listId = Yii::$app->db->getLastInsertId();
//            } else {
//                $listId = $listInfo['id'];
//            }

            $listInfo = UserList::getOrCreateUserList($this->_userId, UserList::LIST_TYPE_VIEW, $this->_userType);
            $listId = $listInfo['id'];

            $musicInfo = UserMusicList::findOne([
                'user_id' => $this->_userId,
                'music_id' => $this->_musicId,
                'list_id' => $listId,
            ]);

            if (!empty($musicInfo)) {
                $musicInfo->updated_at = time();
                $musicInfo->ct = $musicInfo->ct+1;
                $musicInfo->save();
            } else {

                $model->user_id = $this->_userId;
                $model->music_id = $this->_musicId;
                $model->list_id = $listId;
                $model->ct = 1;
                $model->list_type = UserList::LIST_TYPE_VIEW;
                $model->save();
            }

            // 更新歌单歌曲数量
            $this->_updateMusicCount($listId, $listInfo);
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW,
                \common\models\Log::OP_STATUS_SUCCESS,
                $this->_userId,
                $this->_musicId,
                '用户浏览',
                1);

            $transaction->commit();

            // 把已经浏览过的歌曲存到Session
            $viewedMusic = Yii::$app->session->get('viewed_music');
            if (empty($viewedMusic)) {
                $viewedMusic = [$this->_musicId];
            } else {
                if (!in_array($this->_musicId, $viewedMusic)) {
                    $viewedMusic[] = $this->_musicId;
                }
                // 如果超出最大限制，就把最早的删除
                if (sizeof($viewedMusic) > Yii::$app->params['maxViewCount']) {
                    array_shift($viewedMusic);
                }
            }
            Yii::$app->session->set('viewed_music', $viewedMusic);

            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }

    public function fav() {

        $model = new UserMusicList();
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $listInfo = UserList::getOrCreateUserList($this->_userId, UserList::LIST_TYPE_FAV, $this->_userType);

            $listId = $listInfo['id'];

            $musicInfo = UserMusicList::findOne([
                'user_id' => $this->_userId,
                'music_id' => $this->_musicId,
                'list_id' => $listId,
            ]);

            if (!empty($musicInfo)) {
                $musicInfo->updated_at = time();
                $musicInfo->ct = $musicInfo->ct+1;
                $musicInfo->save();
            } else {

                $model->user_id = $this->_userId;
                $model->music_id = $this->_musicId;
                $model->list_id = $listId;
                $model->ct = 1;
                $model->list_type = UserList::LIST_TYPE_FAV;
                $model->save();
            }

            // 更新歌单歌曲数量
            $this->_updateMusicCount($listId, $listInfo);

            Yii::$app->oplog->write(\common\models\Log::OP_CODE_FAVORITE, \common\models\Log::OP_STATUS_SUCCESS, $this->_userId, $this->_musicId, '用户喜欢', 1);

            $transaction->commit();
            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();

            Yii::$app->oplog->write(\common\models\Log::OP_CODE_FAVORITE, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户喜欢', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));

            return $this->fail('操作失败', -1000);
        }

    }



    public function unView() {

        $model = new UserMusicList();
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $listInfo = UserList::findOne([
                'user_id' => $this->_userId,
                'list_type' => UserList::LIST_TYPE_VIEW,
            ]);

            $listId = $listInfo['id'];

            $musicInfo = UserMusicList::findOne([
                'user_id'   => $this->_userId,
                'music_id'  => $this->_musicId,
                'list_id'   => $listId,
            ]);

            // 判断是否有数据
            if (empty($musicInfo)) {
                return $this->fail('没有找到已浏览该音乐数据', -301);
            }

            $ret = UserMusicList::deleteAll(['id' => $musicInfo['id']]);

            $this->_updateMusicCount($listId, $listInfo);

            $transaction->commit();
            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->fail('操作失败', -1000);
        }

    }

    public function unFav() {

        $model = new UserMusicList();
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $listInfo = UserList::findOne([
                'user_id' => $this->_userId,
                'list_type' => UserList::LIST_TYPE_FAV,
            ]);

            $listId = $listInfo['id'];

            $musicInfo = UserMusicList::findOne([
                'user_id'   => $this->_userId,
                'music_id'  => $this->_musicId,
                'list_id'   => $listId,
            ]);

            // 判断是否有数据
            if (empty($musicInfo)) {
                // 强制认为已经取消
                return $this->success('操作成功');
//                return $this->fail('没有找到已喜欢该音乐数据', -401);
            }

            $ret = UserMusicList::deleteAll(['id' => $musicInfo['id']]);

            $this->_updateMusicCount($listId, $listInfo);
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_FAVORITE, \common\models\Log::OP_STATUS_SUCCESS, $this->_userId, $this->_musicId, '用户取消喜欢', 1);
            $transaction->commit();
            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->oplog->write(\common\models\Log::OP_CODE_FAVORITE, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户取消喜欢', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }

    private function _updateMusicCount($listId, $listInfo) {
        try {
            // 更新歌单歌曲数量
//            $musicIds = Music::find()
//                ->where(['is_delete' => Common::STATUS_NORMAL])
//                ->andFilterWhere([
//                    'or',
//                    'music_status'  => \common\models\Music::MUSIC_STATUS_NORMAL,
//                    'op_user_id'    => $this->_userId,
//                ])
//                ->select('id');
//
//            $musicCount = UserMusicList::find()
//                ->where([
//                    'user_id' => $this->_userId,
//                    'list_id' => $listId,
//                ])
//                ->andFilterWhere([
//                    'music_id' => $musicIds
//                ])
////                ->createCommand()
////                ->getRawSql();
////                var_dump($musicCount);exit;
//                ->count();

            $musicType = \common\helpers\Music::transUserTypeToMusicType($this->_userType);

            $musicCount = UserMusicList::find()
                ->joinWith('musicwithoutstatus')
                ->where([
//                    'user_id' => $this->_userId,
                    'list_id' => $listId,
                ])
                ->andFilterWhere([
                    'o_music.is_delete' => Common::STATUS_NORMAL,
                ])
                ->andFilterWhere([
                    'o_music.music_type' => $musicType
                ])
                ->andFilterWhere([
                    'or',
                    ['o_music.music_status' => \common\models\Music::MUSIC_STATUS_NORMAL],
                    ['o_music.op_user_id' => $this->_userId],
                ])
//                ->createCommand()
//                ->getRawSql();
//            var_dump($musicCount);exit;
                ->count();

            $listInfo->ct = $musicCount;
            $listInfo->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

}