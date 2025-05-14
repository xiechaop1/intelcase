<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/29
 * Time: 下午8:32
 */

use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => '歌曲管理',
];
use yii\web\JsExpression;

$this->title = '歌曲管理';
echo \dmstr\widgets\Alert::widget();

?>


    <div class="box box-primary">
        <div class="box-header">
        </div>
        <div class="box-body">
            <div class="form-group">
                <!-- <h2>试听</h2> -->
                <div class="col-sm-12">
                    <audio controls>
                        <source src="<?= \common\helpers\Attachment::completeUrl($musicModel->verse_url, false) ?>" type="audio/ogg">
                        <source src="<?= \common\helpers\Attachment::completeUrl($musicModel->verse_url, false) ?>" type="audio/mpeg">
                        <source src="<?= \common\helpers\Attachment::completeUrl($musicModel->verse_url, false) ?>" type="audio/wav">
                        您的浏览器不支持 audio 元素。
                    </audio>
                </div>
            </div>
            <div class="form-group ">
                <div class="col-sm-12">
                 <div class="header-box">
                    <h2><?= $musicModel->title ?></h2>
                    <p>歌手：<?= $musicModel->singer ?>&nbsp;
                    </p>
                    <p>
                        作曲：<?= $musicModel->composer ?>&nbsp;
                    </p>
                    <p>
                        作词：<?= $musicModel->lyricist ?>
                    </p>
                 </div>
                    <textarea rows="20" cols="60">
                        <?= $musicModel->lyric ?>
                    </textarea>
                </div>
            </div>

        </div>
    </div>
