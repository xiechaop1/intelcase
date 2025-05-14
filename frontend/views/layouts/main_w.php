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
    <meta name="keywords" content=",猎头做单平台|猎头发单平台|猎头交易平台|猎头顾问|寻找合作猎头|猎头|猎头公司">
    <meta name="description" content="禾蛙是专注链接猎企之间职位交付能力与职位空缺的撮合交易平台,可以直接在线发单接单，解决猎企职位多，来不及做，找不到匹配的候选人，解决顾问候选人多，无处可推荐，让简历不浪费，职位不白费">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
     <?php
      require('zhuge_config.php');
     ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container-fluid">
    <header class="text-66 header-right-1">
        <div class="d-flex-wrap justify-content-center bg-black">
            <div class="w-1200">
                <div class="fs-16">
                    <a href="<?= Yii::$app->common->showLoginBtnHref('/') ?>" class="<?= Yii::$app->common->showLoginBtnClass() ?>">
                         <img class="logo" src="/static/image/login/logo-blue.png">
                    </a>

                    <ul class="nav align-items-center">
                        <?php
                        if ( !empty(Yii::$app->request->pathInfo)) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link  header-zhuge <?= Yii::$app->common->showLoginBtnClass() ?>" href="<?= Yii::$app->common->showLoginBtnHref('/') ?>" data-name="首页">
                                <span >首页</span>
                            </a>
                        </li>
                        <?php
                        }
                        ?>

                        <li class="nav-item">
                            <a class="nav-link <?= Yii::$app->common->showLoginBtnClass() ?>"
                               data-toggle="modal" data-target="#myPostModal"
                               data-name="我的工作台">
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
                            <a class="nav-link<?= Yii::$app->common->showLoginBtnClass() ?>" href="<?= Yii::$app->common->showLoginBtnHref('/company/account') ?>">
                                <span >猎企管理</span>
                            </a>
                        </li>
                        <?php
                        }
                        ?>



                    </ul>



                    <div class="float-right m-t-15 ">
                        <a class=" relative<?= Yii::$app->common->showLoginBtnClass() ?>" href="<?= Yii::$app->common->showLoginBtnHref('/account/information') ?>">
                            <img src="<?= !empty(Yii::$app->user->identity->avatar) ? Yii::$app->common->showUploadFilePath(Yii::$app->user->identity->avatar) : '../../static/image/index/user-head.png' ?>" class="header-user m-t-3"/>
                            <span class="text-FF fs-16 m-r-5 user-name"><?= !empty(Yii::$app->user->identity->true_name) ? Yii::$app->user->identity->true_name : '登录 | 注册' ?></span>
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
                        <a class="m-r-40 relative<?= Yii::$app->common->showLoginBtnClass() ?>" href="<?= Yii::$app->common->showLoginBtnHref('#') ?>">
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
                            <a class="<?= Yii::$app->common->showLoginBtnClass() ?> nav-link  userStatusControlBtn m-t-10 userInviteControlBtn" href="javascript:void(0);" name="header-btn-publish">

                                <label class="btn-green-mid2  m-r-30">我要发单</label>

                            </a>
                       </li>


                             <?php
                                       }
                            else{
                             ?>
                            <!--屏蔽20201014 yangzialu-->
                      <!--   <li class="nav-item">
                            <a class="<?= Yii::$app->common->showLoginBtnClass() ?> nav-link  userStatusControlBtn userInviteControlBtn m-t-10" href="<?= Yii::$app->common->showLoginBtnHref('/publish/publish') ?>">
                                 <label class="btn-green-mid2  m-r-30">我要发单</label>
                            </a>
                        </li> -->
                        <!--liujunlin 1014-->
                                <?php
                                $sum = 0;
                                if (!empty(Yii::$app->user->identity->inviteCode)) {
                                    foreach (Yii::$app->user->identity->inviteCode as $inv) {
                                        $sum += $inv->invite_ct;
                                    }
                                }
                                ?>
                                <li class="nav-item">
                                    <a class="<?= Yii::$app->common->showLoginBtnClass() ?> nav-link  userStatusControlBtn userInviteControlBtn m-t-10"
                                        <?php if ($sum > 1 || count(Yii::$app->user->identity->consultantCompany->userList) > 1) { ?>
                                            onclick="removeLocalstorage()"
                                        <?php } ?>
                                    >
                                        <label class="btn-green-mid2  m-r-30">我要发单</label>
                                    </a>
                                </li>
                                <script>
                                    function removeLocalstorage() {
                                        //刘骏霖添加  start
                                        localStorage.removeItem('step');
                                        localStorage.removeItem('jobData');
                                        localStorage.removeItem('jobCompanyId');
                                        localStorage.removeItem('jobPayInfo');
                                        //刘骏霖添加  end
                                        window.location.href = "/new-version/release-post"
                                    }
                                </script>


                            <?php }
                         ?>

                    </div>
                </div>
            </div>
        </div>
    </header>




    <div class="justify-content-center pb-5 bg-FF  row page-content-box" style="margin-top: 64px;">
        <input type="hidden" name="login" value="<?= strpos(Yii::$app->request->referrer, '/passport/web_login') !== false ? 0 : 1 ?>"/>
        <input type="hidden" name="referrer" value="<?= Yii::$app->request->referrer ?>">
    <input type="hidden" name="invite_total" value="<?php
        $sum = 0;
        if (!empty(Yii::$app->user->identity->inviteCode)) {
            foreach (Yii::$app->user->identity->inviteCode as $inv) {
                $sum += $inv->invite_ct;
            }
        }
        echo $sum;
    ?>"/>
    <input type="hidden" name="headhunter_total" value="<?= !empty(Yii::$app->user->identity->consultantCompany) ? count(Yii::$app->user->identity->consultantCompany->userList) : 0 ?>"/>
    <input type="hidden" name="member_type" value="<?= !empty(Yii::$app->user->identity->type) ? Yii::$app->user->identity->type : '' ?>"/>
        <input type="hidden" name="member_created_at" value="<?= !empty(Yii::$app->user->identity) ? Yii::$app->user->identity->created_at : 0 ?>">
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
<!--        刘骏霖添加的给vue准备的user_id-->
        <input type="hidden" name="user_id" id="user_id" value="<?=Yii::$app->user->id?>">
        <?= $content ?>
    </div>



    <?php
    if (Yii::$app->controller->id != 'marry'):
        ?>
    <footer class="row justify-content-center  bg-black fixed">
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
     <?php
      require('modal_login.php');
     ?>

     <?php
      require('modal_company_statue.php');
     ?>
     <?php
      require('modalInvite.php');
     ?>
     <?php
      require('rightBtn.php');
     ?>
    <?php
    require('modal_mypost.php');
    ?>
<?php $this->endBody() ?>
<?php if( !YII_DEBUG): ?>
    <div style="display: none;">
        <script style="display: none;" type="text/javascript" src="https://s23.cnzz.com/z_stat.php?id=1277640552&web_id=1277640552"></script>
    </div>
<?php endif ?>
</body>
</html>
<?php $this->endPage() ?>
