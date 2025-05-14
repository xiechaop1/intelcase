<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/7
 * Time: 下午9:20
 */

namespace frontend\controllers;


use frontend\actions\passport\Login;
use liyifei\base\helpers\Net;
use yii\helpers\ArrayHelper;
use common\helpers\Client;
use common\definitions\Member;
use yii\web\Controller;
use yii;

class HomeController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'banner' => [
                'class'     => 'frontend\actions\home\HomeApi',
                'action'    => 'banner',
            ],
        ];
    }
}