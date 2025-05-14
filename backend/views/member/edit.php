<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/26
 * Time: 10:58 AM
 */

$this->title = '用户编辑';

echo \dmstr\widgets\Alert::widget();
?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">用户信息</h3>
    </div>
    <div class="box-body">
        <?php
        $form = \yii\bootstrap\ActiveForm::begin([
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,
        ]);
        echo $form->field($model, 'username')->textInput()->label('用户名');
        echo $form->field($model, 'mobileSection')->dropDownList(Yii::$app->params['mobile.section'])->label('手机区号');
        echo $form->field($model, 'mobile')->textInput()->label('手机号');
        echo $form->field($model, 'email')->textInput()->label('邮箱');
        ?>
        <div class="form-group">
            <label class="control-label col-sm-3"></label>
            <div class="col-sm-6">
                <?php
                echo \yii\bootstrap\Html::activeHiddenInput($model, 'id', ['value' => $model->id]);
                echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'center-block btn btn-success']);
                ?>
            </div>
        </div>
        <?php
        \yii\bootstrap\ActiveForm::end();
        ?>

    </div>
</div>


