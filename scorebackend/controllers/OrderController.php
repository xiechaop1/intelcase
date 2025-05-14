<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/8
 * Time: 10:49 AM
 */

namespace backend\controllers;


use liyifei\base\controllers\ViewController;
use liyifei\base\helpers\Net;
use yii;

class OrderController extends ViewController
{
    public function behaviors()
    {
        return yii\helpers\ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => ['orders', 'edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return yii\helpers\ArrayHelper::merge(parent::actions(), [
            'orders' => [
                'class' => 'backend\actions\order\Orders',
            ],
            'edit' => [
                'class' => 'backend\actions\order\Edit',
            ]
        ]);
    }
}