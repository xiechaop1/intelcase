<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class SubscribedController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'add' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'add',
            ],
            'get_by_id' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'get_by_id',
            ],
            'get_by_project_id' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'get_by_project_id',
            ],
            'get_with_payments_by_id' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'get_with_payments_by_id',
            ],
            'get_with_payments_by_room_no' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'get_with_payments_by_room_no',
            ],
            'update' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'update',
            ],
            'confirm_info' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'confirm_info',
            ],
            'confirm_sign' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'confirm_sign',
            ],
            'confirm_deal' => [
                'class'     => 'frontend\actions\flow\SubscribedApi',
                'action'    => 'confirm_deal',
            ],
        ];
    }
}