<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/12
 * Time: 下午10:25
 */

namespace backend\controllers;


use liyifei\base\controllers\ViewController;
use yii\helpers\ArrayHelper;
use yii;

class BaseController extends ViewController
{
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'categories' => [
                'class' => 'backend\actions\base\Categories',
            ],
            'category_edit' => [
                'class' => 'backend\actions\base\CategoryEdit',
            ],
            'singers' => [
                'class' => 'backend\actions\base\Singers',
            ],
            'singer_edit' => [
                'class' => 'backend\actions\base\SingerEdit',
            ],
        ]);
    }
}