

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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container-fluid">
    <header class="text-66">
        <div class="d-flex-wrap justify-content-center bg-black">
            <div class="w-1200">
                <div class="fs-16">
                    <a href="/">
                         <img class="logo" src="/static/image/logo.png">
                    </a>

                    <ul class="nav align-items-center">
                        <?php
                        if ( !empty(Yii::$app->request->pathInfo)) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link " href="/">
                                <span >首页</span>
                            </a>
                        </li>
                        <?php
                        }
                        ?>




                        <li class="nav-item">
                            <a class="nav-link " href="/workbench/mypost">
                                <span >我的工作台</span>
                            </a>
                        </li>
                        <?php
                        if (!empty(Yii::$app->user->identity->consultantCompany)
                            && Yii::$app->user->identity->type == \common\models\Member::MEMBER_TYPE_ADMIN
                            && in_array(Yii::$app->user->identity->member_status,
                            [
                                \common\models\Member::MEMBER_STATUS_NORMAL,
                                \common\models\Member::MEMBER_STATUS_WAIT_AUDIT
                            ])
                        ) {
                        ?>


                        <li class="nav-item">
                            <a class="nav-link " href="/company/account">
                                <span >猎企管理</span>
                            </a>
                        </li>
                        <?php
                        }
                        ?>



                    </ul>



                    <div class="float-right m-t-15 ">
                        <a class=" relative" href="/account/information">
                            <img src="<?= !empty(Yii::$app->user->identity->avatar) ? Yii::$app->common->showUploadFilePath(Yii::$app->user->identity->avatar) : '../../static/image/index/user-01.png' ?>" class="header-user m-t-3"/>
                            <span class="text-FF fs-16 m-r-5 user-name"><?= !empty(Yii::$app->user->identity->true_name) ? Yii::$app->user->identity->true_name : '未登录' ?></span>
                            <i class="iconfont iconxiangxia text-99 vertical-mid"></i>
                            <?php
                            if (empty(Yii::$app->user->identity->allSpecial)) {
                                ?>
                                <div class="red-dot-3 fs-12"></div>
                                <?php
                            }
                            ?>
                        </a>
                    </div>
                    <div class="float-right m-t-28 d-none">
                        <a class="m-r-40 relative" href="#">
                            <label class="open-news relative">
                                <div class="red-dot fs-12 d-none"></div>
                                <input type="hidden" name="new-last-time"/>
                                <i class="iconfont iconhead-icon-xiaoxi text-FF fs-26"></i>
                            </label>
                        </a>
                    </div>


                    <div class="float-right">
                     <?php
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


                            if ($r!=0) {
                            ?>
                       <li class="nav-item">
                            <a class="nav-link  userStatusControlBtn" href="javascript:void(0);" name="header-btn-publish">
                                <label class="btn-green-mid2 m-t-9 m-r-30">我要发单</label>
                            </a>
                        </li>


                             <?php
                                       }
                            else{
                             ?>
                        <li class="nav-item">
                            <a class="nav-link  userStatusControlBtn" href="/publish/publish">
                                 <label class="btn-green-mid2">我要发单</label>
                            </a>
                        </li>


                            <?php }
                         ?>

                    </div>
                </div>
            </div>
        </div>
    </header>




    <div class="justify-content-center pb-5 bg-FF row page-content-box">
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
//        $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_PLATEFORM;
    }

    // 公司状态通过，看看个人状态
        if ($r == \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NORMAL) {
//            if (Yii::$app->user->identity->member_status == \common\models\Member::MEMBER_CONSULTANT_COMPANY_STATUS_WAIT_AUDIT) {
//                $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_ADMIN;
//            }
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



    <?php
    if (Yii::$app->controller->id != 'marry'):
        ?>
    <footer class="row justify-content-center  bg-black">
        <div class="w-1200  align-items-center p-7-0 text-center" >
            <!--<span class="text-B5 fs-14 m-r-10">增值电信业务经营许可证：沪B2-20150145</span>-->

            <a target="_blank" href="http://www.beian.miit.gov.cn/" class="text-B5 fs-14 " >苏ICP备14059286号-12</a>
            <span class="text-B5 fs-14 m-r-10 m-l-10 m-r-10">荐客极聘网络技术（苏州）有限公司</span>
            <!--<a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=31011302004261" target="_blank">
                <img src="../../static/image/gwab-icon.png" class="footer-guohui">
                <span class="text-B5 fs-14 m-r-10">沪公网安备31011302004261号</span>
            </a>-->
            <!--<a href="www.hunteron.com" class="text-B5 fs-14">Copyright© 2012-2020 www.hunteron.com</a>-->
            <a href="/" class="text-B5 fs-14">Copyright© 2020-2022 www.hewa.cn</a>
        </div>
    </footer>
    <?php endif; ?>
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
                                        <span class="text-33 fs-12">搜索不到企业？</span><a href="/passport/web_register?type=2" class="text-F6 fs-12">申请企业入驻</a>
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
                        if (Yii::$app->user->identity->type == \common\models\Member::MEMBER_TYPE_ADMIN
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
