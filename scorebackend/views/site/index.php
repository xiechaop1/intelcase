<?php

/* @var $this yii\web\View */

use \common\models\Log;

$this->title = '总览';
?>
<input type="hidden" value="index" name="pageName"/>
<form>
<input type="text"  name="start_date" class="input-group-sm datepicker" placeholder="请选择开始时间" value="<?= !empty($_GET['start_date']) ? $_GET['start_date'] : '' ?>"  data-start-time="2023-01-01" readonly>
<input type="text"  name="end_date" class="input-group-sm datepicker" placeholder="请选择结束时间" value="<?= !empty($_GET['end_date']) ? $_GET['end_date'] : '' ?>" data-start-time="2023-01-01" readonly>
    <input type="submit">
</form>
<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">数据</h2>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?= !empty($ret[Log::OP_CODE_LOGIN]) && !empty($monMaxDay) ? number_format($ret[Log::OP_CODE_LOGIN] / $monMaxDay, 2) : 0?></h3>

                <p>日均登录数</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3><?= !empty($userCount) ? $userCount : 0?></h3>

                <p>白名单用户数</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3><?= !empty($avgBuyMusic) && !empty($monMaxDay) ? number_format($avgBuyMusic / $monMaxDay, 2) : 0?></h3>

                <p>日均购买歌曲数</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= !empty($ret[Log::OP_CODE_COMPLETED]) && !empty($monMaxDay) ? number_format($ret[Log::OP_CODE_COMPLETED] / $monMaxDay, 2) : 0?></h3>

                <p>日均购买用户数</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
        </div>
    </div>

    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?= !empty($ret[Log::OP_CODE_LOCK]) && !empty($monMaxDay) ? number_format($ret[Log::OP_CODE_LOCK] / $monMaxDay, 2) : 0?></h3>

                <p>日均锁定数</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= !empty($ret[Log::OP_CODE_UNLOCK]) && !empty($monMaxDay) ? number_format($ret[Log::OP_CODE_UNLOCK] / $monMaxDay, 2) : 0?></h3>

                <p>日均解锁数</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= !empty($ret[Log::OP_CODE_FAVORITE]) && !empty($monMaxDay) ? number_format($ret[Log::OP_CODE_FAVORITE] / $monMaxDay, 2) : 0?></h3>

                <p>日均喜欢数</p>
            </div>
            <div class="icon">
                <i class="ion  ion-person"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= !empty($ret[Log::OP_CODE_VIEW]) && !empty($monMaxDay) ? number_format($ret[Log::OP_CODE_VIEW] / $monMaxDay, 2) : 0?></h3>

                <p>日均浏览数</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3><?= !empty($ret[Log::OP_CODE_CANCEL]) && !empty($monMaxDay) ? number_format($ret[Log::OP_CODE_CANCEL] / $monMaxDay, 2) : 0?></h3>

                <p>日均取消数</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
        </div>
    </div>
</div>
