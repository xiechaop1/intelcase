<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class DataController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'get_data' => [
                'class'     => 'frontend\actions\flow\DataApi',
                'action'    => 'get_data',
            ],
            'guest_list' => [
                'class'     => 'frontend\actions\flow\DataApi',
                'action'    => 'guest_list',
            ],
            'get_report_list' => [
                'class'     => 'frontend\actions\flow\DataApi',
                'action'    => 'get_report_list',
            ],
            'export_guest_list' => [
                'class'     => 'frontend\actions\flow\DataApi',
                'action'    => 'export_guest_list',
            ],
            
        ];
    }
}