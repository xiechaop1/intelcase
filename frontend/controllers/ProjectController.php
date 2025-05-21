<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class ProjectController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'add' => [
                'class'     => 'frontend\actions\flow\ProjectApi',
                'action'    => 'add',
            ],
            'get_by_id' => [
                'class'     => 'frontend\actions\flow\ProjectApi',
                'action'    => 'get_by_id',
            ],
            'update' => [
                'class'     => 'frontend\actions\flow\ProjectApi',
                'action'    => 'update',
            ],
        ];
    }
}