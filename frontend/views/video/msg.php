<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/4
 * Time: 3:14 PM
 */

/**
 * @var \yii\web\View $this ;
 */

/**
 * @var \common\models\QA $qa
 */

\frontend\assets\Videoh5Asset::register($this);

$this->registerMetaTag([
    'name' => 'referrer',
    'content' => 'no-referrer',
]);
//$this->registerMetaTag([
//    'name' => 'viewport',
//    'content' => 'width=device-width; initial-scale=1.0',
//]);

$this->title = '消息';

?>
<div class="header">
  <div class="container">
    <img src="../../img/logo_yicun.png" class="img-logo">
  </div>
</div>
<div class="video">
  <div class="container" style="margin-top: 0px">
    <div class="row">
      <div class="col-md-12">
              <div class="video-player text-center">
                <h2 class="m-b-20 m-t-20">消息提醒</h2>
                <div class="m-b-20 fs-16">
                  <?= $msg ?>
                </div>
              </div>
      </div>
    </div>

  </div>
</div>

<!-- <div class="w-100 m-auto">

    <div class="p-20 bg-black">
        <div class="w-100 p-30  m-b-10">
            <div class="w-1-0 d-flex">
                <div class="fs-30 bold w-100 text-FF title-box-border">
                    <div class="npc-name">
                        消息
                    </div>
                    <?= $msg ?>
                </div>
            </div>
        </div>
        </div>

    </div>

</div> -->
