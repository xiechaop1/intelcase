<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/8
 * Time: 10:49 AM
 */

namespace backend\controllers;


use common\models\Music;
use liyifei\base\controllers\ViewController;
use liyifei\base\helpers\Net;
use yii;

class MusicController extends ViewController
{
    public function behaviors()
    {
        return yii\helpers\ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => ['music', 'edit', 'detail', 's_music', 's_edit'],
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
            'music' => [
                'class' => 'backend\actions\music\Music',
//                'musicType' => Music::MUSIC_TYPE_NORMAL,
            ],
            'edit' => [
                'class' => 'backend\actions\music\Edit',
                'musicType' => Music::MUSIC_TYPE_NORMAL,
            ],
            'detail' => [
                'class' => 'backend\actions\music\Detail',
//                'musicType' => Music::MUSIC_TYPE_NORMAL,
            ],
            's_music' => [
                'class' => 'backend\actions\music\Smusic',
                'musicType' => Music::MUSIC_TYPE_STATIC,
            ],
            's_edit' => [
                'class' => 'backend\actions\music\Edit',
                'musicType' => Music::MUSIC_TYPE_STATIC,
            ],
        ]);
    }
}