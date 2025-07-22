<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class VisitController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'add' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'add',
            ],
            'get_by_id' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'get_by_id',
            ],
            'get_by_project_id' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'get_by_project_id',
            ],
            'update' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'update',
            ],
            'info_confirm' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'info_confirm',
            ],
            'confirm' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'confirm',
            ],
            'rechange_visit' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'rechange_visit',
            ],
            'rechange_visit_confirm' => [
                'class'     => 'frontend\actions\flow\VisitApi',
                'action'    => 'rechange_visit_confirm',
            ],
        ];
    }
}