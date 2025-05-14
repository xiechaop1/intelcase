<?php
use common\models\Member;
$pathInfo = Yii::$app->request->pathInfo;
?>
<div class="w-190 d-inline-block border-EA overflow-hidden pb-3 tree bg-fa p-10-0">
    <ul class="left-190 nav nav-F6 nav-tabs flex-column border-0 justify-content-center align-items-center text-center text-66 fs-18">
        <li class="w-100 pb-3 ">
            <a class="d-block h-100 <?= strpos($pathInfo,'document') !== false ? 'active' : '' ?>" href="/new-version/document-list" >
                <i class="iconfont iconicon-woderencaiku-nor fs-14 m-r-5"></i>
                <span>我的候选人</span>
            </a>
        </li>
        <li class="w-100 pb-3 ">
            <a class="d-block h-100 <?= strpos($pathInfo,'receiving-orders') !== false ? 'active' : '' ?>" href="/new-version/receiving-orderslist" >
                <i class="iconfont iconicon-wodejiedan-nor fs-14 m-r-5"></i>
                <span>我接的职位</span>
            </a>
        </li>

        <li class="w-100 pb-3">
            <a class="d-block h-100  <?= strpos($pathInfo,'pull-candidate-orders') !== false ? 'active' : '' ?>" href="/new-version/pull-candidate-orders-list" >
                <i class="iconfont iconweixinzhifu fs-14 m-r-5"></i>
                <span>候选人订单</span>
            </a>
        </li>
    </ul>
</div>