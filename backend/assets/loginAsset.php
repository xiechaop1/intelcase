<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class loginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/theme.css',
        'css/bootstrap-datepicker.css',
    ];
    public $js = [
        'script/common.js',
        'script/form-check.js',
        'script/vcode.js',
        'script/login.js'
    ];
    


    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
