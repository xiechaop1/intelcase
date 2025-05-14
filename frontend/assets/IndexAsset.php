<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/4
 * Time: 1:33 PM
 */

namespace frontend\assets;


use yii\web\AssetBundle;

/**
 * @desc 首页静态资源
 * Class IndexAsset
 * @package frontend\assets
 */
class IndexAsset extends AssetBundle
{
    public $sourcePath = '@runtime/../../template';

    public $css = [

         'html/index/index.css',
         'html/index/more-0617.css'
    ];

    public $js = [
        'html/index/searchSuggestion.js',

        'html/index/index-0630.js',
        'html/index/favorite.js',
        'js/favorite.js',
        //'js/toTop2.js'
    ];

    public $depends = [
        'frontend\assets\AppAsset'
    ];
}