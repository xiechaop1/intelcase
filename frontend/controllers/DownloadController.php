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

class DownloadController extends Controller
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
                        'actions' => ['redirect', ],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['redirect',],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
        ]);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'redirect' => 'frontend\actions\download\Redirect',
        ]);
    }

}