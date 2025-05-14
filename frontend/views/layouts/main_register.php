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
    <header class="text-66">
        <div class="d-flex-wrap justify-content-center bg-black">
            <div class="w-1200">
                <div class="fs-16">
                    <a href="/">
                         <img class="logo" src="/static/image/login/logo-blue.png">
                    </a>
                </div>
            </div>
        </div>
    </header>





     <?= $content ?>




    <?php
    if (Yii::$app->controller->id != 'marry'):
        ?>
    <footer class="row justify-content-center  bg-black">
        <div class="w-1200  align-items-center p-10-0 text-center" >
            <!--<span class="text-B5 fs-14 m-r-10">增值电信业务经营许可证：沪B2-20150145</span>-->

            <a target="_blank" href="http://www.beian.miit.gov.cn/" class="text-B5 fs-14 " >苏ICP备14059286号-12</a>
            <span class="text-B5 fs-14 m-r-10 m-l-10 m-r-10">荐客极聘网络技术（苏州）有限公司</span>
            <!--<a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=31011302004261" target="_blank">
                <img src="../../static/image/gwab-icon.png" class="footer-guohui">
                <span class="text-B5 fs-14 m-r-10">沪公网安备31011302004261号</span>
            </a>-->
            <!--<a href="www.hunteron.com" class="text-B5 fs-14">Copyright© 2012-2020 www.hunteron.com</a>-->
            <a href="www.hewa.cn" class="text-B5 fs-14">Copyright© 2020-2022 www.hewa.cn</a>
        </div>
    </footer>
    <?php endif; ?>
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
