<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:03 PM
 */

namespace backend\controllers;


use liyifei\base\helpers\Net;
use yii\helpers\ArrayHelper;
use yii;

class DataController extends BaseController
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => ['data'],
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
            'data' => [
                'class' => 'backend\actions\data\Data',
            ],
            'members' => 'backend\actions\audit\AuditMembers',
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