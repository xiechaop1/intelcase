<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/14
 * Time: 下午11:30
 */

namespace frontend\controllers;


use yii\web\Controller;

class MusicController extends Controller
{
    public $layout = '@frontend/views/layouts/main_n.php';

    public function actions()
    {
        return [
            'get_music' => [
                'class'     => 'frontend\actions\music\GetMusicApi',
                'action'    => 'one',
            ],
            'all_music_by_library' => [
                'class'     => 'frontend\actions\music\GetMusicApi',
                'action'    => 'all_music_by_library',
            ],
            'all_music_by_list' => [
                'class'     => 'frontend\actions\music\GetMusicApi',
                'action'    => 'all_music_by_list',
            ],
            'all_music_by_category' => [
                'class'     => 'frontend\actions\music\GetMusicApi',
                'action'    => 'all_music_by_category',
            ],
            'all_music_by_order' => [
                'class'     => 'frontend\actions\music\GetMusicApi',
                'action'    => 'all_music_by_order',
            ]
        ];
    }
}