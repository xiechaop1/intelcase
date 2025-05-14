<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午5:20
 */

$this->params['breadcrumbs'][] = [
    'label' => '管理员管理',
];

$this->params['breadcrumbs'][] = [
    'label' => '编辑'
];

$this->title = '管理员编辑';

echo \dmstr\widgets\Alert::widget();

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">编辑商家信息</h3>
    </div>
    <div class="box-body">
        <?php
        $form = \yii\bootstrap\ActiveForm::begin([
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,
        ]);

        echo $form->field($model, 'name')->textInput(['value' => $admin->name])->label('名称');
//        echo $form->field($model, 'mobileSection')->dropDownList(Yii::$app->params['mobile.section'], ['value' => $business->mobile_section])->label('号码区号');
//        echo $form->field($model, 'mobile')->textInput(['value' => $business->mobile])->label('公司联系方式');
//        echo $form->field($model, 'email')->textInput(['value' => $business->email])->label('公司联系邮箱');
        echo $form->field($model, 'password')->passwordInput(['value' => $admin->password])->label('密码');
//        echo $form->field($model, 'ownerName')->textInput(['value' => $business->owner_name])->label('负责人');
//        echo $form->field($model, 'ownerMobileSection')->dropDownList(Yii::$app->params['mobile.section'], ['value' => $business->owner_mobile_section])->label('负责人号码区号');
//        echo $form->field($model, 'ownerMobile')->textInput(['value' => $business->owner_mobile])->label('负责人手机号');
//        echo $form->field($model, 'ownerEmail')->textInput(['value' => $business->owner_email])->label('负责人邮箱');
        echo $form->field($model, 'role')->dropDownList(\common\definitions\Admin::$adminRoleEdit2Name,
            ['value' => $admin->role]
        )->label('角色');


        echo \yii\bootstrap\Html::activeHiddenInput($model, 'id', ['value' => $admin->id]);
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

