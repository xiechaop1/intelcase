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
class IndexNotLoginAsset extends AssetBundle
{
    public $sourcePath = '@runtime/../../template';

    public $css = [
         'js/jqueryToast/css/toast.style.css',
         'html/index/index.css',
         'html/index/more-0617.css'
    ];

    public $js = [
        'html/index/searchSuggestion.js',
        'js/jqueryToast/js/toast.script.js',
        'js/clipboard/clipboard.js',
        'html/index/index-0630.js',
        'js/paste.js',
        'js/favorite.js',
        //'js/toTop2.js'
    ];

    public $depends = [
        'frontend\assets\AppAsset'
    ];
}