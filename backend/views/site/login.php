<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
\backend\assets\loginAsset::register($this);
?>

<div class="login-box">
   <!-- <div class="login-logo ">
        <a href="#"><b>Admin</b>LTE</a>
    </div>-->
    <!-- /.login-logo -->
    <div class="login-box-body">
        <!-- <p class="login-box-msg">Sign in to start your session</p>-->
        <h2 class="text-center">野蛮流行管理系统</h2>

        <p></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>

        <!-- /.social-auth-links -->
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->


<div class="modal fade " id="contact-admin" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">联系相关人员</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-sm-3" >联系方：</label>
                        <div class="col-sm-6  m-t-7">
                            刘方正
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-3" >电话/微信：</label>
                        <div class="col-sm-6  m-t-7">
                            15102174017
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-3" >邮箱：</label>
                        <div class="col-sm-6  m-t-7">
                            576745238@qq.com
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-6">


                            <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
                        </div>
                    </div>


            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade " id="forgot-password" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">找回密码</h4>
            </div>
            <form method="post" name="forgotPassword" class="m-b-60">
            <div class="modal-body">
                    <div class="form-group row">
                        <label class="control-label col-sm-2 m-t-7" >登录名：</label>
                        <div class="col-sm-6  ">
                                        <div class="form-group">
                                            <input name="userName" class="w-100 ml-2 required  form-control" type="text" placeholder="请输入用户名">
                                            <div class="invalid-feedback">请输入用户名</div>
                                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2 m-t-7" >新密码：</label>
                        <div class="col-sm-6  ">
                                        <div class="form-group">
                                            <input name="password" class="w-100 ml-2 require  form-control" type="password" placeholder="密码为不少于6位数">
                                            <div class="invalid-feedback">密码为不少于6位数</div>
                                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2 m-t-7" >再次确认：</label>
                        <div class="col-sm-6 ">
                                        <div class="form-group">
                                            <input name="confirmPassword" class="w-100 ml-2 require  form-control" type="password" placeholder="请再输入一遍">
                                            <div class="invalid-feedback">两次密码不一致</div>
                                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2 m-t-7" >手机号：</label>
                        <div class="col-sm-6  ">
                                        <div class="d-flex align-items-center">
                                            <select class=" form-control w-20 d-inline-block" name="sections">
                                                <option value="+86">+86</option>
                                            </select>
                                            <div class="form-group w-78 d-inline-block">
                                                <input name="mobile" class=" require    form-control" type="text" autocomplete="off" placeholder="请填写手机号">
                                                <div class="invalid-feedback">请输入正确手机号</div>
                                            </div>
                                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2 m-t-7" >验证码：</label>
                        <div class="col-sm-3 ">
                                        <div class="d-flex  align-items-center">
                                            <div class="form-group">
                                                <input class="w-100 require  form-control" name="vcode" type="text" autocomplete="off" placeholder="请输入验证码">
                                                <div class="invalid-feedback">请输入验证码</div>
                                            </div>
                                        </div>
                        </div>
                        <div class="col-sm-3 ">
                              <div class="form-group  btn btn-block btn-default vcode-phone vcode">发送验证码</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-6">
                            <label  class="btn btn-primary submit-form" >提交</label>
                        </div>
                    </div>
            </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
