<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class StaffController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'add' => [
                'class'     => 'frontend\actions\flow\StaffApi',
                'action'    => 'add',
            ],
            'get_by_id' => [
                'class'     => 'frontend\actions\flow\StaffApi',
                'action'    => 'get_by_id',
            ],
            'get_by_name' => [
                'class'     => 'frontend\actions\flow\StaffApi',
                'action'    => 'get_by_name',
            ],
            'get_list' => [
                'class'     => 'frontend\actions\flow\StaffApi',
                'action'    => 'get_list',
            ],
            'update' => [
                'class'     => 'frontend\actions\flow\StaffApi',
                'action'    => 'update',
            ],
        ];
    }
}