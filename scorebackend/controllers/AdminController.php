<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午4:32
 */

namespace backend\controllers;


use yii\helpers\ArrayHelper;

class AdminController extends BaseController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => ['index', 'edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => 'backend\actions\admin\Index',
            'edit' => 'backend\actions\admin\Edit'
        ]);
    }
}