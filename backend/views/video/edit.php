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
    'label' => '视频管理',
];
use yii\web\JsExpression;

$this->title = '视频管理';
echo \dmstr\widgets\Alert::widget();

?>


    <div class="box box-primary">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            $form = \yii\bootstrap\ActiveForm::begin([
                'layout' => 'horizontal',
                'enableClientValidation' => true,
            ]);

            echo $form->field($videoModel, 'music_title')->textInput(['value' => $videoModel->music_title])->label('歌曲名称');
//            echo $form->field($videoModel, 'video_title')->textInput(['value' => $videoModel->video_title])->label('视频名称');
//            echo $form->field($videoModel, 'link_id')->textInput(['value' => $videoModel->link_id])->label('链接ID');
            echo $form->field($videoModel, 'video_url')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                'multiple' => true,
                'isImage' => false,
                'ossHost' => Yii::$app->params['oss.host'],
                'signatureAction' => ['/site/oss-signature?dir=video/' . Date('Y/m/')],
                'clientOptions' => ['autoUpload' => true],
                'options' => ['value' => $videoModel->video_url],
//                'directory' => 'cover/' . Date('Y/m/')
            ])->label('视频URL');
//            echo $form->field($videoModel, 'filename')->textInput(['value' => $videoModel->filename])->label('文件名');
//            echo $form->field($videoModel, 'file_type')->textInput(['value' => $videoModel->file_type])->label('文件类型');

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
