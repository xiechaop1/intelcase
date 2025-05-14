<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/4
 * Time: 下午9:45
 */

$this->title = '编辑标签';

if (!empty($data['special_type'])
    && in_array(\common\definitions\Tag::TYPE_BOUTIQUE, $data['special_type'])) {
    $this->title = '主题管理';
}

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">请填写标签</h3>
    </div>
    <div class="box-body">
        <?php

        $form = \yii\bootstrap\ActiveForm::begin([
            'layout' => 'horizontal',
        ]);

        echo $form->field($model, 'name')->label('名称');
//        echo $form->field($model, 'special_type')->inline(true)->radioList([
//            \common\definitions\Tag::TYPE_COMMON => '普通标签',
//            \common\definitions\Tag::TYPE_GEO => '位置标签'
//        ])->label('类型');
        echo $form->field($model, 'special_type')->dropDownList($tag2Names, ['value' => $model->special_type]);
        ?>
        <div class="form-group">
            <label class="control-label col-sm-3"></label>
            <div class="col-sm-5">
                <?= \yii\bootstrap\Html::submitButton('提交', ['class' => 'center-block btn btn-primary btn-w-m']) ?>
            </div>
        </div>
        <?php
        \yii\bootstrap\ActiveForm::end();
        ?>

    </div>
</div>