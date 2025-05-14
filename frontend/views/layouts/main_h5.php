<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

//AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=750,user-scalable=no, target-densitydpi=device-dpi">
    <meta name="keywords" content=",猎头做单平台|猎头发单平台|猎头交易平台|猎头顾问|寻找合作猎头|猎头|猎头公司">
    <meta name="description" content="禾蛙是专注链接猎企之间职位交付能力与职位空缺的撮合交易平台,可以直接在线发单接单，解决猎企职位多，来不及做，找不到匹配的候选人，解决顾问候选人多，无处可推荐，让简历不浪费，职位不白费">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
     <?php
      require('zhuge_config.php');
     ?>
</head>
<body class="bg-F5 relative p-b-150" >
<?php $this->beginBody() ?>
<div class="container-fluid bg-F5">

    <div class="justify-content-center pb-5 bg-F5  row page-content-box">
    <input type="hidden" name="memberStatus" value=""/>
    <input type="hidden" name="companyStatus" value="<?php
    if (!empty(Yii::$app->user->identity->consultantCompany)) {
        // 判断公司状态
        switch (Yii::$app->user->identity->consultantCompany->company_status) {
            case \common\models\ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT:
                $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_PLATEFORM;
                break;
            default:
                $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NORMAL;
                break;
        }
    } else {
        $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NO_BIND;
    }

    // 公司状态通过，看看个人状态
        if ($r == \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NORMAL) {

            switch (Yii::$app->user->identity->member_status) {
                case \common\models\Member::MEMBER_STATUS_WAIT_AUDIT:
                    $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_ADMIN;
                    break;
                case \common\models\Member::MEMBER_STATUS_FAIL:
                    $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NO_BIND;
                    break;
            }
        }

        echo $r;
    ?>"/>

        <?= $content ?>
    </div>



</div>


<!-- 绑定猎企 -->
<div class="modal fade" id="userStatus-3" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- 模态框头部 -->
            <span class="close delete-note  m-t-15 m-r-20  absolute top-0 right-0 z-9999" data-dismiss="modal">×</span>
            <!-- 模态框主体 -->
            <div class="modal-body  m-t-20">
                <form name="form-bind-company">
                    <div class="m-b-20 text-center">
                        <span class="text-44 fs-18 title">绑定猎企</span>
                    </div>
                    <div class="p-0-20">
                        <div class="text-center m-t-30">
                            <section class="pass text-left">
                                <div class="m-b-20">
                                    <div class="d-inline-block w-20 text-right">
                                        <span class="text-red">*</span>
                                        <span class="text-33">公司名称：</span>
                                    </div>
                                    <div class="d-inline-block w-50 relative">
                                        <input class="form-control input-group-m  form-w-m  w-100 m-r-10" value="" id="search-company-name" name="company_name" placeholder="请输入公司名称" autocomplete="off"/>
                                        <div class="invalid-feedback" name="company_name_invalid" style="left: 0;text-align: left;">请输入公司名称</div>

                                    </div>
                                    <div class="d-inline-block w-25  ">
                                        <span class="text-33 fs-12">搜索不到企业？</span><a href="/passport/web_register?type=2<?= !empty(\Yii::$app->user->identity->source) ? '&source=' . \Yii::$app->user->identity->source : '' ?>" class="text-F6 fs-12">申请企业入驻</a>
                                    </div>
                                    <input type="hidden" name="company_id" value=""  />
                                </div>
                                <div class="m-b-20">
                                    <div class="d-inline-block w-20 text-right">
                                        <span class="text-red">*</span>
                                        <span class="text-33">姓名：</span>
                                    </div>
                                    <input class="form-control input-group-m d-inline-block form-w-m w-50 m-r-10 required" name="true_name" placeholder="请输入姓名"  />

                                </div>
                                <div class="m-b-20">
                                    <div class="d-inline-block w-20 text-right">
                                        <span class="text-red">*</span>
                                        <span class="text-33">邮箱：</span>
                                    </div>
                                    <input class="form-control input-group-m d-inline-block form-w-m w-50 m-r-10" name="mail" placeholder="请输入邮箱"  />

                                </div>
                                <!--<div class="m-b-20">
                                    <div class="d-inline-block w-20 text-right">

                                    </div>
                                    <div class=" w-50 d-inline-block relative text-center">
                                        <input class="" type="checkbox" name="mustCheck"  id="checkAgreement">
                                        <label class="mb-0" for="checkAgreement"><a href="<?= Yii::$app->contract->getPath('CODE002') ?>" target="_blank">同意接单协议</a></label>
                                        <div class="invalid-feedback" style="left: 22px;text-align: left;">请同意接单协议</div>
                                    </div>

                                </div>-->



                                <div class="text-center m-t-30 m-b-20 ">
                                    <!--  <span class="btn btn-white-border m-r-20 delete-note w-90px">取消</span>-->
                                    <span class="btn btn-danger w-90px submit" >提交</span>

                                    <input type="hidden" name="<?= \Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>"/>
                                </div>
                            </section>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<!-- 猎企正在申请中 -->
<div class="modal fade" id="userStatus-1" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- 模态框头部 -->
            <span class="close delete-note  m-t-15 m-r-20  absolute top-0 right-0 z-9999" data-dismiss="modal">×</span>
            <!-- 模态框主体 -->
            <div class="modal-body  m-t-10">

                <div class="w-100">
                    <div class="text-center ">
                        <div class="m-b-20 d-none">
                            <span class="text-44 fs-18 title">提示</span>
                        </div>
                        <div class="text-center m-t-20 p-10">
                            <img src="../../static/image/icon-danger.png" class="img-48 m-auto border-no ">
                            <p class="fs-16 text-66 m-t-20">您注册的猎企正在申请中，请耐心等待平台审核</p>
                            <p class="fs-16 text-66 ">如需帮助请联系禾蛙管理员：18012608053</p>
                        </div>
                        <div class="text-center m-t-20 m-b-20">
                            <span class="btn  btn-white-border" data-dismiss="modal">关闭</span>


                        <?php
                        if (!empty(Yii::$app->user->identity) && Yii::$app->user->identity->type == \common\models\Member::MEMBER_TYPE_ADMIN
                            && in_array(Yii::$app->user->identity->member_status,
                            [
                                \common\models\Member::MEMBER_STATUS_NORMAL,
                                \common\models\Member::MEMBER_STATUS_WAIT_AUDIT
                            ])
                        ) {
                        ?>
                            <!--个人账户不显示"去开通企业账户"按钮 start-->
                            <a href="/company/account" name="open-company" >
                                <span class="btn  btn-danger   delete-note m-l-20">去开通企业账户</span>
                            </a>
                            <!--个人账户不显示"去开通企业账户"按钮 end-->
                        <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 猎企正在申请中 -->
<div class="modal fade" id="userStatus-2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- 模态框头部 -->
            <span class="close delete-note  m-t-15 m-r-20  absolute top-0 right-0 z-9999" data-dismiss="modal">×</span>
            <!-- 模态框主体 -->
            <div class="modal-body  m-t-10">

                <div class="w-100">
                    <div class="text-center ">
                        <div class="m-b-20 d-none">
                            <span class="text-44 fs-18 title">提示</span>
                        </div>
                        <div class="text-center m-t-20 p-10">
                            <img src="../../static/image/icon-danger.png" class="img-48 m-auto border-no">
                            <p class="fs-16 text-66 m-t-20">您的猎企绑定正在申请中，请耐心等待猎企审核</p>
                            <p class="fs-16 text-66 ">如需帮助请联系禾蛙管理员：18012608053</p>
                        </div>
                        <div class="text-center m-t-20 m-b-20">
                            <span class="btn  btn-white-border  m-r-20" data-dismiss="modal">关闭</span>
                            <a href="/account/information" name="data-href">
                                <span class="btn  btn-danger   delete-note ">去完善个人信息</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
<?php if( !YII_DEBUG): ?>
    <div style="display: none;">
        <script style="display: none;" type="text/javascript" src="https://s23.cnzz.com/z_stat.php?id=1277640552&web_id=1277640552"></script>
    </div>
<?php endif ?>
</body>
</html>
<?php $this->endPage() ?>
