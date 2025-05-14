<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use liyifei\base\controllers\ViewController;
use yii\web\Controller;

class UserController extends Controller
{
    public $layout = '@frontend/views/layouts/main_w.php';

    public function actions()
    {
        $request = \Yii::$app->request;

        return [
            'get_user' => [
                'class' => 'frontend\actions\user\UserApi',
                'action' => 'get_user',
            ],
            'get_token' => [
                'class' => 'frontend\actions\user\UserApi',
                'action' => 'get_token',
            ],
            'get_session' => [
                'class' => 'frontend\actions\user\UserApi',
                'action' => 'get_session',
            ],
            'get_mobile' => [
                'class' => 'frontend\actions\user\UserApi',
                'action' => 'get_mobile',
            ],
            'update_user' => [
                'class' => 'frontend\actions\user\UserApi',
                'action' => 'update_user',
            ],
            'login' => [
                'class' => 'frontend\actions\user\UserApi',
                'action' => 'login',
            ],

        ];
    }
}