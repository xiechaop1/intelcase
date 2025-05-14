<?php
use common\models\Member;
$pathInfo = Yii::$app->request->pathInfo;
?>
<div class="w-190 d-inline-block border-EA overflow-hidden pb-3 tree bg-fa p-10-0">
    <ul class="left-190 nav nav-F6 nav-tabs flex-column border-0 justify-content-center align-items-center text-center text-66 fs-18">
        <li class="w-100 pb-3 ">
            <a class="d-block h-100  <?= strpos($pathInfo,'user-order-list') !== false ? 'active' : '' ?> " href="/new-version/user-order-list" >
                <i class="iconfont iconicon-wodefadan-sel fs-14 m-r-5"></i>
                <span>我的客户</span>
            </a>
        </li>
        <li class="w-100 pb-3 ">
            <a class="d-block h-100 <?= strpos($pathInfo,'job-manage') !== false ? 'active' : '' ?> " href="/new-version/job-manage-list" >
                <i class="iconfont iconicon-wodefadan-sel fs-14 m-r-5"></i>
                <span>职位管理</span>
            </a>
        </li>
        <li class="w-100 pb-3">
            <a class="d-block h-100 <?= strpos($pathInfo,'order-consultant') !== false ? 'active' : '' ?>" href="/new-version/order-consultant-list" >
                <i class="iconfont iconicon-woderencaiku-nor fs-14 m-r-5"></i>
                <span>接单顾问</span>
            </a>
        </li>
        <li class="w-100 pb-3">
            <a class="d-block h-100  <?= strpos($pathInfo,'candidate-order') !== false ? 'active' : '' ?>" href="/new-version/candidate-order" >
                <i class="iconfont iconweixinzhifu fs-14 m-r-5"></i>
                <span>候选人订单</span>
            </a>
        </li>
    </ul>
</div>