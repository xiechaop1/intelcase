<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:03 PM
 */

namespace scorebackend\controllers;


use liyifei\base\helpers\Net;
use yii\helpers\ArrayHelper;
use yii;

class FileController extends ViewController
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => ['data', 'group', 'version', 'file'],
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
            'group' => [
                'class' => 'scorebackend\actions\file\Group',
            ],
            'version' => [
                'class' => 'backend\actions\file\Version',
            ],
            'file' => [
                'class' => 'backend\actions\file\File',
            ],
//            'edit' => [
//                'class' => 'backend\actions\article\Edit',
//            ],
//            'delete' => [
//                'class' => 'backend\actions\article\Delete',
//                'articleId' => Net::get('article_id')
//            ]
        ]);
    }
}