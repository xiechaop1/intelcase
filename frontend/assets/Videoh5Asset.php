<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/4
 * Time: 2:12 PM
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class Videoh5Asset extends AssetBundle
{
    public $sourcePath = '@runtime/../../template';

//    CONST HOST = 'https://s.nowkey.net/zspiritx/';
    CONST HOST = '';

    public $css = [
    	self::HOST . 'css/bootstrap/bootstrap.min.css',
        self::HOST . 'js/bootoast/bootoast.css',
        self::HOST . 'css/theme.css',
        self::HOST . 'css/dropdown/dropdowm.css',
        self::HOST . 'css/iconfont.css',
        self::HOST . 'css/datePicker/bootstrap-datepicker.css',
        self::HOST . 'css/bootstrap-clockpicker.css',
        self::HOST . 'js/jqueryToast/css/toast.styleH5.css',
        self::HOST . 'js/owl.carousel/owl.carousel.css',
        self::HOST . 'css/iconfont.css',
        self::HOST . 'css/animate.css',
        'css/video.css',
    ];
    public $js = [
        self::HOST . 'js/jquery/jQuery-2.1.3.min.js',
        self::HOST . 'js/jquery/jquery.json.min.js',
        self::HOST . 'js/Popper/Popper.js',
        self::HOST . 'js/bootstrap/bootstrap.min.js',
        self::HOST . 'js/bootoast/bootoast.js',
        self::HOST . 'js/alert.js',
        self::HOST . 'js/myslideup.js',
        self::HOST . 'js/dropdown/dropdown.js',
        self::HOST . 'js/datePicker/moment.js',
        self::HOST . 'js/jqueryToast/js/toast.script.js',
        self::HOST . 'js/owl.carousel/owl.carousel.js',
        self::HOST . 'js/jquery.SuperSlide.2.1.3.js',
        'js/video.js',
        ];


}