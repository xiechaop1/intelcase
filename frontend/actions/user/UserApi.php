<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\user;


use common\definitions\Common;
use common\models\Staff;
//use liyifei\base\actions\ApiAction;
use frontend\actions\ApiAction;
use yii;

class UserApi extends ApiAction
{
    public $action;

    public $userId;

    private $_get;

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();
            switch ($this->action) {
                case 'get_user':
                    $ret = $this->getUser();
                    break;
                case 'get_session':
                    $ret = $this->getSession();
                    break;
                case 'get_mobile':
                    $ret = $this->getMobile();
                    break;
                case 'get_token':
                    $ret = $this->getToken();
                    break;
                case 'login':
                    $ret = $this->login();
                    break;
                case 'update_user':
                    $this->valToken();
                    $ret = $this->updateUser();
                    break;
                default:
                    $ret = [];
                    break;

            }
        } catch (\Exception $e) {
            return $this->fail($e->getCode() . ': ' . $e->getMessage());
        }

        return $this->success($ret);
    }

    public function login() {
        $code = $this->_get['code'];
        $userInfo = $this->_get['user_info'];
        try {
//            $ret = Yii::$app->wechat->login($code);
            $ret = Yii::$app->wechat->getSession($code);
            $openId = $ret['openid'];
            $sessionKey = $ret['session_key'];

            if (!empty($userInfo)) {
//                $userInfo = json_decode($userInfo, true);
                $encryptedData = $userInfo['encryptedData'];
                $iv = $userInfo['iv'];
                $decipher = Yii::$app->wechat->decryptData($encryptedData, $iv, $sessionKey);

                $ret = $decipher;
            }

//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_LOGIN, 1, $this->_userId, 0, '用户' . $this->_userId . '登录');
//            return $this->success($ret);
            return $ret;
        } catch (\Exception $e) {
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_LOGIN, 0, $this->_userId, 0, '用户' . $this->_userId . '登录');
//            return $this->fail($e->getCode() . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function getSelfToken() {

    }

    public function getToken() {
        try {

            $userId = !empty($this->_get['user_id']) ? $this->_get['user_id'] : 0;
            $ret = null;
            if (!empty($userId)) {
                $userInfo = Staff::findOne($userId);

                if (!empty($userInfo['wx_token'])
                    && !empty($userInfo['wx_token_expire_time'])
                    && $userInfo['wx_token_expire_time'] > time()) {
                    $ret = [
                        'token' => $userInfo['wx_token'],
                        'expire_time' => $userInfo['wx_token_expire_time'],
                    ];
                } else {
                    $tokenRet = Yii::$app->wechat->getToken();
                    $ret['token'] = $userInfo['wx_token'] = $tokenRet['access_token'];
                    $ret['expire_time'] = $userInfo['wx_token_expire_time'] = time() + $tokenRet['expires_in'];
                    $userInfo->save();
                }

            }

//            return $this->success($ret);
            return $ret;
        } catch (\Exception $e) {
            throw $e;
//            return $this->fail($e->getCode() . ': ' . $e->getMessage());
        }
    }

    public function getSession() {
        $code = $this->_get['code'];
        try {
            $ret = Yii::$app->wechat->getSession($code);

            $openId = $ret['openid'];
            $user = Staff::findOne(['wx_openid' => $openId
//                , 'staff_status' => Staff::STAFF_STATUS_NORMAL
            ]);
//            if (!empty($user)
//                && $user->staff_status == Staff::STAFF_STATUS_DISABLE
//            ) {
//                throw new \Exception('很抱歉，非授权用户暂不支持登录', -1001);
//            }
            if (!empty($user['id'])) {
                $this->_get['user_id'] = $user['id'];
                $tokenRet = $this->getToken();
                $user['wx_token'] = $tokenRet['token'];
                $user['wx_token_expire_time'] = $tokenRet['expire_time'];

//                Yii::$app->oplog->write(\common\models\Log::OP_CODE_LOGIN, 1, $user['id'], 0, '用户登录');
            }
            $ret['user'] = $user;

            return $ret;
        } catch (\Exception $e) {
            throw $e;
//            return $this->fail($e->getCode() . ': ' . $e->getMessage());
        }
    }

    public function getMobile() {
        $code = $this->_get['code'];
        $openId = $this->_get['open_id'];
        $unionId = !empty($this->_get['union_id']) ? $this->_get['union_id'] : '';
        try {
            $mobile = Yii::$app->wechat->getMobile($code);
            $user = null;
            if (!empty($mobile)) {
                $user = Staff::findOne(['mobile' => $mobile]);

                // 判断用户状态（是不是在白名单里，也就是状态是"被邀请"）
                $userInfo = !empty($this->_get['user_info']) ? json_decode($this->_get['user_info'], true) : [];
                $tokenRet = Yii::$app->wechat->getToken();
                if (empty($user)) {
                    $user = new Staff();
                    if (empty($user->staff_name)) {
                        $user->staff_name = !empty($userInfo['nickName']) ? $userInfo['nickName'] : '';
                    } else {
                        $user->staff_name = '用户' . rand(10000,99999);
                    }
                    if (empty($user->avatar)) {
                        $user->avatar = !empty($userInfo['avatarUrl']) ? $userInfo['avatarUrl'] : '';
                    }

                    $user->wx_openid = $openId;
                    $user->wx_unionid = $unionId;
                    $user->staff_status = Staff::STAFF_STATUS_NORMAL;
                    $user->mobile = $mobile;
                    $user->role = Staff::STAFF_ROLE_SALES;
                    $user->wx_token = !empty($tokenRet['access_token']) ? $tokenRet['access_token'] : '';
                    $user->wx_token_expire_time = !empty($tokenRet['expires_in']) ? time() + $tokenRet['expires_in'] : '';
                    $user->save();
//                    $user->save();
                } else if ($user->staff_status == Staff::STAFF_STATUS_DISABLE) {
                    throw new \Exception('很抱歉，非授权用户暂不支持登录', -1001);
//                    return [];
                } else {
//                    $userInfo = !empty($this->_get['user_info']) ? json_decode($this->_get['user_info'], true) : [];
                    if (empty($user->staff_name)) {
                        $user->staff_name = !empty($userInfo['nickName']) ? $userInfo['nickName'] : '';
                    }
                    if (empty($user->avatar)) {
                        $user->avatar = !empty($userInfo['avatarUrl']) ? $userInfo['avatarUrl'] : '';
                    }

                    $user->wx_openid = $openId;
                    $user->wx_unionid = $unionId;
                    $user->staff_status = Staff::STAFF_STATUS_NORMAL;
//                    $tokenRet = Yii::$app->wechat->getToken();
                    $user->wx_token = !empty($tokenRet['access_token']) ? $tokenRet['access_token'] : '';
                    $user->wx_token_expire_time = !empty($tokenRet['expires_in']) ? time() + $tokenRet['expires_in'] : '';
                    $user->save();

                }

//                Yii::$app->oplog->write(\common\models\Log::OP_CODE_REGISTER, 1, $user->id, 0, '获取用户手机号和微信信息');

            }
            return $user;
        } catch (\Exception $e) {
            throw $e;
//            return $this->fail($e->getCode() . ': ' . $e->getMessage());
        }
    }

    public function getUser() {


        $userId = !empty($this->_get['user_id']) ? $this->_get['user_id'] : 0;

        $retModel = Staff::find()->where(['id' => $userId]);

        $ret = $retModel->one();

        if ($ret) {
//
            $r = $ret->toArray();

        } else {
            $r = [];
        }

//        $ret->toArray();

        return $r;
    }

    public function updateUser() {
        $userId = !empty($this->_get['user_id']) ? $this->_get['user_id'] : 0;

        $user = \common\models\Staff::findOne($userId);

        if (empty($user)) {
            return $this->fail('用户不存在', -100);
        }

        $user->load(['User' => $this->_get]);
        try {
            $ret = $user->save();
            return $user->toArray();
        } catch (\Exception $e) {
            throw $e;
//            return $this->fail($e->getCode() . ': ' . $e->getMessage());
        }
    }


}