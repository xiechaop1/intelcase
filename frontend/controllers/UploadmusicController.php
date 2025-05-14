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

class UploadmusicController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'get_file_list', 'get_version_list', 'get_group_list', 'get_file_detail'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['create', 'get_file_list', 'get_version_list', 'get_group_list', 'get_file_detail'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST', 'GET'],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'create' => [
                'class'     => 'frontend\actions\music\UploadInfoApi',
                'action'    => 'create',
            ],
            'get_file_list' => [
                'class'     => 'frontend\actions\music\UploadInfoApi',
                'action'    => 'get_file_list',
            ],
            'get_version_list' => [
                'class'     => 'frontend\actions\music\UploadInfoApi',
                'action'    => 'get_version_list',
            ],
            'get_group_list' => [
                'class'     => 'frontend\actions\music\UploadInfoApi',
                'action'    => 'get_group_list',
            ],
            'get_file_detail' => [
                'class'     => 'frontend\actions\music\UploadInfoApi',
                'action'    => 'get_file_detail',
            ],
        ];
    }

    public function beforeAction($action) {
        return true;
    }
}