<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class GroupController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'get_category' => [
                'class'     => 'frontend\actions\music\GroupApi',
                'action'    => 'get_category',
            ],
            'get_library' => [
                'class'     => 'frontend\actions\music\GroupApi',
                'action'    => 'get_library',
            ],
            'get_categories_list' => [
                'class'     => 'frontend\actions\music\GroupApi',
                'action'    => 'get_categories_list',
            ],
            'get_libraries_list' => [
                'class'     => 'frontend\actions\music\GroupApi',
                'action'    => 'get_libraries_list',
            ],
            'get_user_list_music' => [
                'class'     => 'frontend\actions\music\GroupApi',
                'action'    => 'get_user_list_music',
            ],
            'get_user_lists_by_type' => [
                'class'     => 'frontend\actions\music\GroupApi',
                'action'    => 'get_user_lists_by_type',
            ],
        ];
    }
}