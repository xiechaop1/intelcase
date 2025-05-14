<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/26
 * Time: 9:36 PM
 */

namespace frontend\controllers;


use liyifei\base\helpers\Net;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class PassportController extends Controller
{
    public $layout = '@frontend/views/layouts/main_login.php';

    public function init()
    {
        parent::init();

        $this->enableCsrfValidation = false;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'web_login', 'web_reset_password', 'web_register', 'verification-code', 'register', 'reset_password', 'reg-click', 'check_mobile'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout', 'verification-code', 'web_reset_password', 'web_register', 'web_login', 'reset_password', 'register', 'login', 'check_mobile'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['POST'],
                    'verification-code' => ['POST', 'GET'],
                    'register' => ['POST'],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            // 记录注册按钮点击
            'reg-click' => 'frontend\actions\passport\RegClick',
            // 发送验证码
            'verification-code' => [
                'class' => 'frontend\actions\passport\VerificationCode',
                'way' => Net::post('way', 'mobile'),
                'type' => Net::post('type', '1'),
            ],
            // 注册
            'register' => [
                'class' => 'frontend\actions\passport\Register',
//                'way' => Net::post('way', 'mobile'),
                'token' => Net::post('token', '')
            ],
            'check_mobile' => 'frontend\actions\passport\CheckMobile',
            // 登录
            'login' => [
                'class' => 'frontend\actions\passport\Login',
                'mobileSection' => Net::post('mobile_section'),
                'mobile' => Net::post('mobile'),
                'email' => Net::post('email'),
                'password' => Net::post('password'),
                'userName' => Net::post('user_name'),
                'way' => Net::post('way', 'mobile'),
                'verificationCode' => Net::post('verificationCode'),
                'rememberPassword' => Net::post('remember_password'),
                'rememberPhone' => Net::post('rememberPhone'),
                'source' => Net::post('source'),
                'inviteCode' => Net::post('invite_code'),
            ],
            // 退出登录
            'logout' => [
                'class' => 'frontend\actions\passport\Logout'
            ],
            // 重置密码
            'reset_password' => 'frontend\actions\passport\ResetPassword',
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
            'web_login' => [
                'class' => 'frontend\actions\passport\Weblogin'
            ],
            'web_register' => [
                'class' => 'frontend\actions\passport\Webregister'
            ],
            'web_reset_password' => [
                'class' => 'frontend\actions\passport\Webresetpassword'
            ],
        ]);
    }

    public function successCallback($client)
    {
        $id = $client->getId(); // qq | sina | weixin
        $attributes = $client->getUserAttributes(); // basic info
        $openid = $client->getOpenid(); //user openid
        $userInfo = $client->getUserInfo(); // user extend info
        var_dump($id, $attributes, $openid, $userInfo);
    }
}