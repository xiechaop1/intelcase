<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class OpController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'view' => [
                'class'     => 'frontend\actions\music\OpApi',
                'action'    => 'view',
            ],
            'lock' => [
                'class'     => 'frontend\actions\music\OpApi',
                'action'    => 'lock',
            ],
            'fav' => [
                'class'     => 'frontend\actions\music\OpApi',
                'action'    => 'fav',
            ],
            'unview' => [
                'class'     => 'frontend\actions\music\OpApi',
                'action'    => 'unview',
            ],
            'unlock' => [
                'class'     => 'frontend\actions\music\OpApi',
                'action'    => 'unlock',
            ],
            'unfav' => [
                'class'     => 'frontend\actions\music\OpApi',
                'action'    => 'unfav',
            ],
        ];
    }
}