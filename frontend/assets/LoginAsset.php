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
class LoginAsset extends AssetBundle
{
    public $sourcePath = '@runtime/../../template';

    public $css = [
         'js/barrager/css/barrager.css',
         'html/login/login.css'
    ];

    public $js = [
          //'js/imageRound/jquery.easing.1.3.js',
          //'js/imageRound/jquery.roundabout.min.js',
          //'js/jquery.slider/jquery.slider.min.js',
          //'html/login/slider.js'
          'js/toTop.js',
          'js/barrager/js/jquery.barrager.js',
          'html/login/login.js',


    ];

    public $depends = [
        'frontend\assets\AppAsset'
    ];
}