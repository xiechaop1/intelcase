<?php
/**
 * Created by PhpStorm.
 * User: yifei
 * Date: 2019/3/17
 * Time: 22:40
 */

namespace frontend\controllers;


use globepay\lib\data\GlobePayUnifiedOrder;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $input = new GlobePayUnifiedOrder();

        print_r($input);
    }
}