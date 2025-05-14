<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/5
 * Time: 3:10 PM
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class SearchAsset extends AssetBundle
{
    public $sourcePath = '@runtime/../../template';

    public $css = [
        'html/search/search.css'
    ];

    public $js = [
        'html/search/search.js'
    ];

    public $depends = [
        'frontend\assets\AppAsset'
    ];
}