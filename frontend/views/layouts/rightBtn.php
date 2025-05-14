 <!--右侧悬浮按钮-->
 <div class="right-fixed">
         <div class="right-fixed-top">


    <?php
    if (empty(Yii::$app->user->id)) {
        ?>
        <!--未登录 显示-->
             <label class="text-center w-100  loginBtn">
                 <div class="red-dot fs-12 d-none"></div>
                 <img src="../../static/image/icon/xiaoxi.png" class="img-22 m-auto normal-show">
                 <img src="../../static/image/icon/xiaoxi-hover.png" class="img-22 m-auto hover-show">
                 <div class="fs-12 text-99 m-t-5 title">消息</div>
             </label>
        <?php
    } else {
            ?>
            <!--已登录 显示-->
             <label class="text-center w-100 open-news">
                 <div class="red-dot fs-12 d-none"></div>
                 <img src="../../static/image/icon/xiaoxi.png" class="img-22 m-auto normal-show">
                 <img src="../../static/image/icon/xiaoxi-hover.png" class="img-22 m-auto hover-show">
                 <div class="fs-12 text-99 m-t-5 title">消息</div>
             </label>
            <?php
    }
    ?>
           <a href="<?= Yii::$app->common->showLoginBtnHref('/site/fav') ?>" class='<?= Yii::$app->common->showLoginBtnClass() ?>'>
                 <label class="text-center w-100 m-t-10">
                     <img src="../../static/image/icon/shoucang.png" class="img-22 m-auto normal-show">
                     <img src="../../static/image/icon/shoucang-hover.png" class="img-22 m-auto hover-show">
                     <div class="fs-12 text-99 m-t-5 title">收藏</div>
                 </label>
             </a>
           <!--    <a href="">
                 <label class="text-center w-100 m-t-10">
                     <img src="../../static/image/icon/clock.png" class="img-22 m-auto normal-show">
                     <img src="../../static/image/icon/clock-hover.png" class="img-22 m-auto hover-show">
                     <div class="fs-12 text-99 m-t-5 title">历史</div>
                 </label>
             </a>-->

         </div>
         <div class="right-fixed-bottom">
             <label class="text-center w-100 relative d-none invite-right-btn  <?= Yii::$app->common->showLoginBtnClass() ?>" data-id="0">
                 <img src="../../static/image/invite/yaoqing.png" class="img-22 m-auto normal-show">
                 <img src="../../static/image/invite/yaoqing-hover.png" class="img-22 m-auto hover-show">
                 <img src="../../static/image/invite/invite-note-hover.png" class="hover-show to-hover-note4 show-invite-modal" data-id="2" >
                 <div class="fs-12 text-99 m-t-5 title">邀请</div>
             </label>
             <label class="text-center w-100 m-t-10 relative">
                 <img src="../../static/image/icon/kefu.png" class="img-22 m-auto normal-show">
                 <img src="../../static/image/icon/kefu-hover.png" class="img-22 m-auto hover-show">
                 <div class="fs-12 text-99 m-t-5 title">客服</div>
                 <div class="to-hover-note hover-show text-left">
                     <div class="fs-12 text-F6">客服官方电话</div>
                     <div class="fs-16 text-33">18012608053</div>
                     <div class="fs-12 text-F6 m-t-10">工作日</div>
                     <div class="fs-12 text-99">09:00-18:00</div>
                 </div>
             </label>
             <label class="text-center w-100 m-t-10 relative">
                 <img src="../../static/image/icon/erweima.png" class="img-22 m-auto normal-show">
                 <img src="../../static/image/icon/erweima-hover.png" class="img-22 m-auto hover-show">
                 <img src="../../static/image/index/weixin-qr.png" class="hover-show to-hover-note3">
                 <div class="fs-12 text-99 m-t-5 title">咨询</div>
             </label>
             <label class="text-center w-100 m-t-10 toTop2">
                 <img src="../../static/image/icon/top.png" class="img-22 m-auto normal-show">
                 <img src="../../static/image/icon/top-hover.png" class="img-22 m-auto hover-show">
                 <div class="fs-12 text-99 m-t-5 title">顶部</div>
             </label>
         </div>
     </div>