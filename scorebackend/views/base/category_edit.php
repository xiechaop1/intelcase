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
    'label' => '分类管理',
];
use yii\web\JsExpression;

$this->title = '分类管理';
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
        echo $form->field($categoryModel, 'category_name')->textInput(['value' => $categoryModel->category_name])->label('分类名称');
//        echo $form->field($categoryModel, 'category_image')->widget('\liyifei\uploadOSS\FileUploadOSS', [
//            'multiple' => true,
//            'isImage' => false,
//            'ossHost' => Yii::$app->params['oss.host'],
//            'signatureAction' => ['/site/oss-signature?dir=category_image/'],
//            'clientOptions' => ['autoUpload' => true],
//            'options' => ['value' => $categoryModel->category_image],
//
//        ])->label('分类图片');
        echo $form->field($categoryModel, 'sort_by')->textInput(['value' => $categoryModel->sort_by])->label('排序');
        echo $form->field($categoryModel, 'tab_sort_by')->textInput(['value' => $categoryModel->tab_sort_by])->label('音乐超市Tab排序（0为不显示）');


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
