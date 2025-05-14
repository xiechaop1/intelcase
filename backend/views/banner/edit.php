<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/3
 * Time: 下午12:42
 */

use common\helpers\Attachment;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;


$this->title = 'Banner管理';

?>


<div class="box box-primary">
    <div class="box-header">
    </div>
    <div class="box-body">
        <?php

        $form = \yii\bootstrap\ActiveForm::begin([
            'layout' => 'horizontal',
        ]);
//        echo $form->field($bannerModel, 'page')->label('页面');
        echo $form->field($bannerModel, 'image')->widget('\liyifei\uploadOSS\FileUploadOSS', [
            'multiple' => false,
            'isImage' => true,
            'ossHost' => Yii::$app->params['oss.host'],
            'signatureAction' => ['/site/oss-signature?dir=banner/'],
            'clientOptions' => ['autoUpload' => true],
            'options' => ['value' => $bannerModel->image]
        ])->label('图片');
        echo $form->field($bannerModel, 'subject')->label('Banner标题');
        echo $form->field($bannerModel, 'target')->label('链接目标');
        echo $form->field($bannerModel, 'online_time_str', ['inputOptions' => [
                'value' => Date('Y-m-d H:i:s', $bannerModel->online_time)
        ]])->label('上线时间');
        echo $form->field($bannerModel, 'offline_time_str', ['inputOptions' => [
            'value' => Date('Y-m-d H:i:s', $bannerModel->offline_time)
        ]])->label('下线时间');
        echo $form->field($bannerModel, 'sort')->label('排序');
        echo $form->field($bannerModel, 'banner_status')->dropDownList(
                \common\models\Banner::$bannerStatus2Name
        )->label('状态');

        ?>
        <div class="form-group">
            <label class="control-label col-sm-3"></label>
            <div class="col-sm-6">
                <?= \yii\bootstrap\Html::submitButton('提交', ['class' => 'center-block btn btn-success']) ?>
            </div>
        </div>
        <?php
        \yii\bootstrap\ActiveForm::end();
        ?>
    </div>
</div>

