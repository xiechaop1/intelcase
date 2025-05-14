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
use common\models\UploadMusic;
use common\models\UploadMusicGroup;
use common\models\UploadMusicVersion;
use common\models\User;
use common\models\UserList;
use common\models\UserMusicList;
use common\models\Music;
use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class UploadInfoApi extends ApiAction
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

//            $this->_userId = !empty($this->_get['user_id']) ? (int)$this->_get['user_id'] : 0;

            $this->_userId = !empty($_REQUEST['user_id']) ? (int)$_REQUEST['user_id'] : 0;

            if (empty($this->_userId)) {
                return $this->fail('请您给出用户信息', -199);
            }

            $this->_userInfo = User::findOne($this->_userId);
            $this->_userType = User::USER_TYPE_NORMAL;
            if (!empty($this->_userInfo)) {
                $this->_userType = $this->_userInfo['user_type'];
            }

//            if (empty($this->_get['music_id'])) {
//                return $this->fail('请您给出歌曲信息', -100);
//            } else {
//                $this->_musicId = (int)$this->_get['music_id'];
//
//                // 检查音乐是否存在
//                $this->_musicInfo = Music::findOne($this->_musicId);
//                if (empty($this->_musicInfo)) {
//                    return $this->fail('歌曲不存在', -101);
//                }
//            }

            $this->valToken();
            switch ($this->action) {
                case 'create':
                    $ret = $this->create();
                    break;
                case 'update':
                    $ret = $this->update();
                    break;
                case 'get_group_list':
                    $ret = $this->getGroupList();
                    break;
                case 'get_version_list':
                    $ret = $this->getVerisonList();
                    break;
                case 'get_file_list':
                    $ret = $this->getFileList();
                    break;
                case 'get_file_detail':
                    $ret = $this->getFileDetail();
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

    public function create() {

        $transaction = Yii::$app->db->beginTransaction();

        $params = $_REQUEST;

        try {
            if (!empty($params['title'])) {
                $modelGroup = UploadMusicGroup::find()
                    ->where(['title' => $params['title']])
                    ->one();

                if (empty($modelGroup)) {
                    $modelGroup = new UploadMusicGroup();
                    $modelGroup->load($this->_get, '');
                    $modelGroup->user_id = $this->_userId;
                    $modelGroup->save();

                    $uploadMusicGroupId = Yii::$app->db->getLastInsertID();
                } else {
                    $uploadMusicGroupId = $modelGroup->id;
                }

                if (!empty($params['ts'])) {
                    $ts = $params['ts'];
                } else {
                    $ts = time();
                }
                $ts = date('YmdHis', $ts);

                $modelVersion = UploadMusicVersion::find()
                    ->where([
                        'ver_name' => $ts,
                        'group_id' => $uploadMusicGroupId,
                    ])
                    ->one();

                if (empty($modelVersion)) {
                    $modelVersion = new UploadMusicVersion();
                    $modelVersion->ver_name = $ts;
                    $modelVersion->group_id = $uploadMusicGroupId;
                    $modelVersion->save();

                    $uploadMusicVersionId = Yii::$app->db->getLastInsertID();
                } else {
                    $uploadMusicVersionId = $modelVersion->id;
                }

            } else {
                throw new \Exception('请您给出歌曲组信息', -100);
            }

            if (!empty($params['fileurl'])) {
               if (strpos($params['fileurl'], ',') !== false) {
                   $files = explode(',', $params['fileurl']);
               } else {
                   $files[] = $params['fileurl'];
               }
            }

            if (!empty($files)) {
                foreach ($files as $f) {
                    $model = new UploadMusic();
                    $model->fileurl = $f;
                    $model->ver_id = $uploadMusicVersionId;
                    $model->user_id = $this->_userId;
                    $model->save();
                }
            }

            $uploadMusicId = Yii::$app->db->getLastInsertID();

            $transaction->commit();

            return $this->success([
                'file' => $model,
                'group' => $modelGroup,
                'version' => $modelVersion,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


    public function getFileList() {
        $list = UploadMusic::find()->where(['user_id' => $this->_userId]);

        $verId = !empty($this->_get['ver_id']) ? (int)$this->_get['ver_id'] : 0;
        if (!empty($verId)) {
            $list = $list->andWhere(['ver_id' => $verId]);
        }
        $list = $list->orderBy('id desc')->asArray()->all();

        return $this->success($list);
    }

    public function getGroupList() {
        $list = UploadMusicGroup::find()
            ->where(['user_id' => $this->_userId])
            ->orderBy('id desc')
            ->asArray()->all();

        return $this->success($list);

    }

    public function getVerisonList() {

        $groupId = !empty($this->_get['group_id']) ? (int)$this->_get['group_id'] : 0;

        $list = UploadMusicVersion::find();
        if (!empty($groupId)) {
            $list = $list->where(['group_id' => $groupId]);
        }
        $list = $list->orderBy('id desc')
            ->all();

        $ret = [];
        if (!empty($list)) {
            foreach ($list as &$l) {
//                $l['filelist'] = $l->files;
                $tmp = $l->toArray();
                $tmp['filelist'] = $l->files;
                $tmp['scorelist'] = $l->scores;
                $ret[] = $tmp;
            }
        }

        return $this->success($ret);
    }

    public function getFileDetail() {
        $model = UploadMusic::findOne($this->_get['id']);
        return $this->success($model);
    }



}