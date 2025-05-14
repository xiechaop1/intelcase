<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/theme.css',
        'css/bootstrap-datepicker.css',
        'css/AdminLTE.min1.0.css'
    ];
    public $js = [
        'script/common.js',
        'script/form-check.js',
        'script/bootstrap-datepicker.js',
        'script/app.js'
    ];
    
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
