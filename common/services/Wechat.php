<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/2/20
 * Time: 2:12 PM
 */

namespace common\services;


use common\services\Curl;
use common\models\User;
use yii\base\Component;
use yii;

class Wechat extends Component
{

    const WECHAT_HOST = 'https://api.weixin.qq.com';

    public $appId;
    public $appSecret;
//    const WECHAT_APP_ID = 'wxdc22108a3be1428d';
//    const WECHAT_APP_ID = 'wx15fe47d044ab1a36';   // test
//    const WECHAT_SECRET = 'c71f42740ff11f631691b3a73d374bc4';
//    const WECHAT_SECRET = '97e1f573c2ba3f75dbe88ffebddedf5a'; // test

    private $_token;

    public function login($code) {
        $uri = '/sns/jscode2session';

        $params = [
            'appid'     => $this->appId,
            'secret'    => $this->appSecret,
            'js_code'   => $code,
            'grant_type'=> 'authorization_code',
        ];

        $uri = $this->_createUri($uri, self::WECHAT_HOST, $params);

        try {
            $ret = $this->_getApi($uri);
//            var_dump($ret);exit;
            if (!empty($ret['errcode']) && $ret['errcode'] != 0) {
                throw new \Exception($ret['errmsg'], $ret['errcode']);
            }

            // 如果用户不存在，创建用户
            $openId = $ret['openid'];
            $user = User::findOne(['wx_openid' => $openId]);

            if (empty($user)) {

                // 获取手机号
                $mobile = $this->getMobile($code);

                $user = User::findOne(['mobile' => $mobile]);

                // 判断用户状态（是不是在白名单里，也就是状态是"被邀请"）
                if (empty($user)
                    || $user->user_status == User::USER_STATUS_FORBIDDEN
                ) {
                    throw new \Exception('用户不存在或已被禁用', -1001);
                } else {
                    $user->wx_openid = $openId;
                    $user->wx_unionid = $ret['unionid'];
                    $user->user_status = User::USER_STATUS_NORMAL;

                }

                // 创建新用户
//                $user = new User();
//                $user->open_id = $openId;
//                $user->union_id = $ret['unionid'];
//                $user->mobile = $mobile;
//                $user->save();
//                $user->id = Yii::$app->db->getLastInsertID();
            }
            $this->_token = $this->getToken();
            $user->wx_token = $this->_token;
            $user->save();

            return $user;

        } catch (\Exception $e) {
            throw new \Exception('微信登录失败：' . $e->getMessage());
        }

        return $ret;
    }

    public function getSession($code)
    {
        $uri = '/sns/jscode2session';

        $params = [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'js_code' => $code,
            'grant_type' => 'authorization_code',
        ];

        $uri = $this->_createUri($uri, self::WECHAT_HOST, $params);

        try {
            $ret = $this->_getApi($uri);
        } catch (\Exception $e) {
            throw new \Exception('获取Session失败：' . $e->getMessage(), $e->getCode());
        }

        return $ret;
    }
    public function getMobile($code){
        $uri = '/wxa/business/getuserphonenumber';

        $tokenRet = $this->getToken();
        $token = !empty($tokenRet['access_token']) ? $tokenRet['access_token'] : '';

        $params = [
            'access_token'  => $token,
        ];

        $uri = $this->_createUri($uri, self::WECHAT_HOST, $params);

        try {
            $params = [
                'code' => $code
            ];
            $ret = $this->_getPostApi($uri, $params);

            return !empty($ret['phone_info']['phoneNumber']) ? $ret['phone_info']['phoneNumber'] : '';

        } catch (\Exception $e) {
            throw new \Exception('获取微信手机号失败：' . $e->getMessage());
        }

        return $ret;
    }

    /**
     * 发送订阅消息
     * @param string $touser 接收者（用户）的 openid
     * @param string $template_id 所需下发的订阅消息的id
     * @param array $data 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
     * @param string|null $page 点击模板卡片后的跳转页面，仅限本小程序内的页面
     * @param string|null $miniprogram_state 跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
     * @return mixed
     * @throws \Exception
     */
    public function sendSubscribeMessage($touser, $template_id, $data, $page = null, $miniprogram_state = null)
    {
        $uri = '/cgi-bin/message/subscribe/send';

        $tokenRet = $this->getToken();
        $token = !empty($tokenRet['access_token']) ? $tokenRet['access_token'] : '';

        $params = [
            'access_token' => $token,
        ];

        $uri = $this->_createUri($uri, self::WECHAT_HOST, $params);

        $postData = [
            'touser' => $touser,
            'template_id' => $template_id,
            'data' => $data,
        ];

        if (!empty($page)) {
            $postData['page'] = $page;
        }
        if (!empty($miniprogram_state)) {
            $postData['miniprogram_state'] = $miniprogram_state;
        }

        try {
            $ret = $this->_getPostApi($uri, $postData);
            return $ret;
        } catch (\Exception $e) {
            throw new \Exception('发送订阅消息失败：' . $e->getMessage());
        }
    }

    public function getToken() {
        if (!empty($this->_token)) {
            return $this->_token;
        }
        $uri = '/cgi-bin/token';

        $params = [
            'grant_type'    => 'client_credential',
            'appid'         => $this->appId,
            'secret'        => $this->appSecret,
        ];

        $uri = $this->_createUri($uri, self::WECHAT_HOST, $params);

        try {
            $ret = $this->_getApi($uri);

            if (!empty($ret['access_token'])) {
                $this->_token = $ret;
//                return $ret;
            } else {
                throw new \Exception('获取微信token失败：' . $ret['errmsg'], $ret['errcode']);
            }

        } catch (\Exception $e) {
            throw new \Exception('获取微信token失败：' . $e->getMessage());
        }

        return $ret;
    }

    public function decryptData($encryptedData, $iv, $sessionKey) {
        $decrypted = openssl_decrypt($encryptedData, 'aes-128-cbc', base64_decode($sessionKey), OPENSSL_RAW_DATA);
        return $decrypted;
    }

    private function _createUri($uri, $host, $params = []) {
        $uri = $host . $uri;
        if (!empty($params)) {
            $uri .= '?' . http_build_query($params);
        }
        return $uri;
    }

    private function _getApi($uri) {
        $ret = Curl::curlGet($uri);
        $ret = json_decode($ret, true);
        if (!empty($ret['errcode']) && $ret['errcode'] != 0) {
            throw new \Exception($ret['errmsg'], $ret['errcode']);
        }
        return $ret;
    }

    private function _getPostApi($uri, $postParams = []) {
        $ret = Curl::curlPost($uri, $postParams, [], true);
        $ret = json_decode($ret, true);
        if (!empty($ret['errcode']) && $ret['errcode'] != 0) {
            throw new \Exception($ret['errmsg'], $ret['errcode']);
        }
        return $ret;
    }

}