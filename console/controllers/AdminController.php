<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 10:18 PM
 */

namespace console\controllers;


use backend\models\AdminIdentity;
use common\models\Admin;
use yii\console\Controller;
use yii;

class AdminController extends Controller
{
    public function actionInit()
    {
//        Admin::updateAll([
//            'password' => Yii::$app->security->generatePasswordHash('123456')
//        ], ['>', 'role', 0]);

//        $admin = new AdminIdentity([
//            'name' => 'fangxue',
//            'password' => Yii::$app->security->generatePasswordHash('654321'),
//            'mobile' => '+86 111111111',
//            'email' => 'test@qq.com',
//        ]);
//
//        $admin->save();
    }
}