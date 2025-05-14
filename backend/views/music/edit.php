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
            <?php
            $form = \yii\bootstrap\ActiveForm::begin([
                'layout' => 'horizontal',
                'enableClientValidation' => true,
            ]);
            echo $form->field($musicModel, 'cover_image')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                'multiple' => false,
                'isImage' => true,
                'ossHost' => Yii::$app->params['oss.host'],
                'signatureAction' => ['/site/oss-signature?dir=cover/' . Date('Y/m/')],
                'clientOptions' => ['autoUpload' => true],
                'options' => ['value' => $musicModel->cover_image],
//                'directory' => 'cover/' . Date('Y/m/')
            ])->label('封面图');
            //            echo $form->field($musicModel, 'cover_thumbnail')->widget('\liyifei\uploadOSS\FileUploadOSS', [
            //                'multiple' => false,
            //                'isImage' => true,
            //                'ossHost' => Yii::$app->params['oss.host'],
            //                'signatureAction' => ['/site/oss-signature?dir=thumb/' . Date('Y/m/')],
            //                'clientOptions' => ['autoUpload' => true],
            //                'options' => ['value' => $musicModel->cover_thumbnail],
            ////                'directory' => 'cover_thumb/' . Date('Y/m/')
            //            ])->label('缩略图');
            echo $form->field($musicModel, 'background_image')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                'multiple' => false,
                'isImage' => true,
                'ossHost' => Yii::$app->params['oss.host'],
                'signatureAction' => ['/site/oss-signature?dir=background/' . Date('Y/m/')],
                'clientOptions' => ['autoUpload' => true],
                'options' => ['value' => $musicModel->background_image],
//                'directory' => 'cover/' . Date('Y/m/')
            ])->label('背景图');
            echo $form->field($musicModel, 'title')->textInput(['value' => $musicModel->title])->label('歌曲名称');
            echo $form->field($musicModel, 'comment')->textInput(['value' => $musicModel->comment])->label('副标题');
            echo $form->field($musicModel, 'singer')->textInput(['value' => $musicModel->singer])->label('歌手');
            echo $form->field($musicModel, 'lyricist')->textInput(['value' => $musicModel->lyricist])->label('词作者');
            echo $form->field($musicModel, 'composer')->textInput(['value' => $musicModel->composer])->label('曲作者');
            echo $form->field($musicModel, 'category_ids')->widget('\kartik\select2\Select2', [
                'data' => $categories,
                'options' => [
                    'multiple' => true
                ],
            ])->label('分类');
            echo $form->field($musicModel, 'music_type')->widget('\kartik\select2\Select2', [
                'data' => $musicTypes,
                'options' => [
                    'multiple' => false
                ],
            ])->label('歌曲类型');
            echo $form->field($musicModel, 'lyric_url')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                'multiple' => false,
                'isImage' => false,
                'ossHost' => Yii::$app->params['oss.host'],
                'signatureAction' => ['/site/oss-signature?dir=lyric/' . Date('Y/m/')],
                'clientOptions' => ['autoUpload' => true],
                'options' => ['value' => $musicModel->lyric_url],
//                'directory' => 'verse_music/' . Date('Y/m/')
            ])->label('歌词文件');
            echo $form->field($musicModel, 'lyric')->textarea()->label('歌词文本');
            echo $form->field($musicModel, 'verse_url')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                'multiple' => false,
                'isImage' => false,
                'ossHost' => Yii::$app->params['oss.host'],
                'signatureAction' => ['/site/oss-signature?dir=verse/' . Date('Y/m/')],
                'clientOptions' => ['autoUpload' => true],
                'options' => ['value' => $musicModel->verse_url],
//                'directory' => 'verse_music/' . Date('Y/m/')
            ])->label('音频文件');
//            echo $form->field($musicModel, 'chorus_url')->widget('\liyifei\uploadOSS\FileUploadOSS', [
//                'multiple' => false,
//                'isImage' => false,
//                'ossHost' => Yii::$app->params['oss.host'],
//                'signatureAction' => ['/site/oss-signature?dir=chorus/' . Date('Y/m/')],
//                'clientOptions' => ['autoUpload' => true],
//                'options' => ['value' => $musicModel->chorus_url],
////                'directory' => 'chorus_music/' . Date('Y/m/')
//            ])->label('副歌');
//            echo $form->field($musicModel, 'chorus_start_time')->textInput(['value' => $musicModel->chorus_start_time])->label('副歌起始时间（秒）');
//            echo $form->field($musicModel, 'chorus_end_time')->textInput(['value' => $musicModel->chorus_end_time])->label('副歌结束时间（秒）');

//            echo $form->field($musicModel, 'price')->textInput(['value' => $musicModel->price])->label('价格');
//            echo $form->field($musicModel, 'duration')->textInput(['value' => $musicModel->duration])->label('歌曲长度');
//            echo $form->field($musicModel, 'music_rate')->textInput(['value' => $musicModel->music_rate])->label('歌曲采样率');
//            echo $form->field($musicModel, 'singer_id')->widget('\kartik\select2\Select2', [
//                'data'  => $singers,
//                'options' => [
//                    'id'    => 'singer_id',
//                    'multiple' => false,
//                    'value' => $musicModel->singer_id,
//                ],
//            ])->label('歌手');

//            echo $form->field($musicModel, 'resource_download_url')->textInput(['value' => $musicModel->resource_download_url])->label('资源下载链接');
            echo $form->field($musicModel, 'resource_download_url')->textInput(['value' => $musicModel->resource_download_url])->label('物料下载链接');
            echo $form->field($musicModel, 'resource_download_file')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                'multiple' => false,
                'isImage' => false,
                'ossHost' => Yii::$app->params['oss.host'],
                'signatureAction' => ['/site/oss-signature?dir=resource/' . Date('Y/m/')],
                'clientOptions' => ['autoUpload' => true],
                'options' => ['value' => $musicModel->resource_download_file],
//                'directory' => 'verse_music/' . Date('Y/m/')
            ])->label('物料链接');
            echo $form->field($musicModel, 'remarks')->textarea()->label('备注');
            //            echo $form->field($musicModel, 'minAmount')->textInput(['value' => $musicModel->minAmount])->label('最低价格');
//            echo $form->field($musicModel, 'visa_fee')->textInput(['value' => $musicModel->visa_fee])->label('签证费用');
//            echo $form->field($musicModel, 'days_of_travel')->textInput(['value' => $musicModel->days_of_travel])->label('行程天数');
//            echo $form->field($musicModel, 'level')->dropDownList($levels)->label('星级');
//            ['value' => $musicModel->level])->label('星级');
//            echo $form->field($musicModel, 'introduce')->widget('kucha\ueditor\UEditor', [
//                'clientOptions' => [
//                    'serverUrl' => '/article/ueditor-upload'
//                ]
//            ])->label('行程简介');
//            echo $form->field($musicModel, 'introduce')->textarea()->label('产品简介');
//            echo $form->field($musicModel, 'introduce_more')->widget('kucha\ueditor\UEditor', [
//                'clientOptions' => [
//                    'serverUrl' => '/article/ueditor-upload'
//                ]
//            ])->label('行程');
//            echo $form->field($musicModel, 'trait')->textarea()->label('特色');
//            echo $form->field($musicModel, 'product_status')->dropDownList(\common\models\BoutiqueProduct::$productStatus2Name)->label('显示状态');
//            echo $form->field($musicModel, 'discount')->textInput(['value' => $musicModel->discount])->label('折扣');
//            echo $form->field($musicModel, 'sort')->textInput(['value' => $musicModel->sort])->label('排序');
            ?>

            <div class="form-group">
                <label class="control-label col-sm-3">试听</label>
                <div class="col-sm-6">
                    <audio controls>
                       <source src="<?= \common\helpers\Attachment::completeUrl($musicModel->verse_url, false) ?>" type="audio/ogg">
                       <source src="<?= \common\helpers\Attachment::completeUrl($musicModel->verse_url, false) ?>" type="audio/mpeg">
                       <source src="<?= \common\helpers\Attachment::completeUrl($musicModel->verse_url, false) ?>" type="audio/wav">
                       您的浏览器不支持 audio 元素。
                    </audio>
                </div>
            </div>

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


<script>
    $(document).ready(function () {
        console.log($('#view_city'));
        $("#view_city").select2({
            //tags: true
        })
        ;

        $("#view_city").on("select2:select", function (evt) {
            console.log($(this));
            var element = evt.params.data.element;
            var $element = $(element);

            window.setTimeout(function () {
                console.log($("select#view_city"));
                console.log($("select#view_city").find(":selected"));
                console.log($("select#view_city").find(":selected").length);
                if ($("select#view_city").find(":selected").length > 1) {
                    var $second = $("select#view_city").find(":selected").eq(-1);
                    console.log($second);
                    console.log($element);
                    if ($second.val() != $element.val()) {
                        $element.detach();
                        $second.after($element);
                    }
                } else {
                    $element.detach();
                    $("select#view_city").prepend($element);
                }

                $("select#view_city").trigger("change");
            }, 1);
        });

//        $("select#view_city").on("select2:unselect", function (evt) {
//            if ($("select#view_city").find(":selected").length) {
//                var element = evt.params.data.element;
//                var $element = $(element);
//                $
//                ("select#view_city").find(":selected").after($element);
//            }
//        });
    });
</script>