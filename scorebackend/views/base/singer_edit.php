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
    'label' => '歌手管理',
];
use yii\web\JsExpression;

$this->title = '歌手管理';
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
        echo $form->field($singerModel, 'singer_name')->textInput(['value' => $singerModel->singer_name])->label('歌手名称');
        echo $form->field($singerModel, 'singer_category_id')->widget('\kartik\select2\Select2', [
            'data'  => $categories,
            'options' => [
                'id'    => 'singer_id',
                'multiple' => false,
                'value' => $singerModel->singer_category_id,
            ],
        ])->label('歌手分类');

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
