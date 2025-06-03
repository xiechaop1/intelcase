<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ReportController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'add', 'get_by_id', 'update'
                        ],
                        'allow' => true,
                        'roles' => ['?']
                    ],
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
        return [
            'add' => [
                'class'     => 'frontend\actions\flow\ReportApi',
                'action'    => 'add',
            ],
            'get_by_id' => [
                'class'     => 'frontend\actions\flow\ReportApi',
                'action'    => 'get_by_id',
            ],
            'update' => [
                'class'     => 'frontend\actions\flow\ReportApi',
                'action'    => 'update',
            ],
            'confirm' => [
                'class'     => 'frontend\actions\flow\ReportApi',
                'action'    => 'confirm',
            ],
        ];
    }
}