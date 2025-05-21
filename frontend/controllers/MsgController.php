<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class MsgController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
//            'add' => [
//                'class'     => 'frontend\actions\flow\MsgApi',
//                'action'    => 'add',
//            ],
            'get_by_recv_id' => [
                'class'     => 'frontend\actions\flow\MsgApi',
                'action'    => 'get_by_recv_id',
            ],
            'read' => [
                'class'     => 'frontend\actions\flow\MsgApi',
                'action'    => 'read',
            ],
            
        ];
    }
}