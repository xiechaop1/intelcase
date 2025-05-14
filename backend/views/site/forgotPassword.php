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
      <form method="post" name="forgotPassword" class="m-b-60">
    <div class="login-box-body">
        <!-- <p class="login-box-msg">Sign in to start your session</p>-->
        <h2 class="text-center">找回密码</h1>

        <p></p>
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
                                                <input name="phone" class=" require    form-control" type="text" autocomplete="off" placeholder="请填写手机号">
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
    <!-- /.login-box-body -->
</div><!-- /.login-box -->




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
                                                <input name="phone" class=" require    form-control" type="text" autocomplete="off" placeholder="请填写手机号">
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
