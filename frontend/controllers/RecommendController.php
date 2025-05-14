<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use liyifei\base\controllers\ViewController;
use yii\web\Controller;

class RecommendController extends Controller
{
    public $layout = '@frontend/views/layouts/main_w.php';

    public function actions()
    {
        return [
            'change_status' => [
                'class' => 'frontend\actions\recommend\ChangeStatus'
            ],
            'create' => [
                'class' => 'frontend\actions\recommend\Create'
            ],
            'detail' => [
                'class' => 'frontend\actions\recommend\Detail'
            ],
        ];
    }
}