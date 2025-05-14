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
class RegisterAsset extends AssetBundle
{
    public $sourcePath = '@runtime/../../template';

    public $css = [
       'css/bootstrap/bootstrap.min.css',
        'js/bootoast/bootoast.css',
        'css/theme.css',
        'css/dropdown/dropdowm.css',
        'css/iconfont.css',
        'css/datePicker/bootstrap-datepicker.css',
        '//at.alicdn.com/t/font_1506168_db4q1wh14tk.css',
        'css/iconfont.css',
        'css/animate.css',
        'js/select2/dist/css/select2.css',
        'html/login/login.css'
    ];

    public $js = [
        'js/jquery/jQuery-2.1.3.min.js',
        'js/jquery/jquery.json.min.js',
        'js/jquery.scrollto.js',
        'js/jquery.cookie.js',
        'js/Popper/Popper.js',
        'js/bootstrap/bootstrap.min.js',
        'js/bootoast/bootoast.js',
        'js/CheckForm.js',
        'js/vcode.js',
        'js/alert.js',
        'js/myslideup.js',
        'js/dropdown/dropdown.js',
        'js/datePicker/bootstrap-datepicker.min.js',
        'js/datePicker/bootstrap-datepicker.zh-CN.min.js',
        'js/datePicker.js',
        'js/header.js',
        'js/login.js',
        'js/getOptions.js',
        'js/jquery.SuperSlide.2.1.3.js',
        'js/fileupload/jquery.ui.widget.js',
        'js/fileupload/jquery.iframe-transport.js',
        'js/fileupload/jquery.fileupload.js',
        'js/fileupload/jquery.fileupload-process.js',
        'js/fileupload/jquery.fileupload-validate.js',
        'js/select2/dist/js/select2.full.js',
        'html/index/searchSuggestion.js',
        'js/region2.js',
        'js/select2/dist/js/select2.full.js',
        'html/login/register.js'
    ];

}