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
    <script type = "text/javascript">
      (function() {
        if (window.zhuge) return;
        window.zhuge = [];
        window.zhuge.methods = "_init identify track trackRevenue getDid getSid getKey setSuperProperty setUserProperties setWxProperties setPlatform".split(" ");
        window.zhuge.factory = function(b) {
          return function() {
            var a = Array.prototype.slice.call(arguments);
            a.unshift(b);
            window.zhuge.push(a);
            return window.zhuge;
          }
        };
        for (var i = 0; i < window.zhuge.methods.length; i++) {
          var key = window.zhuge.methods[i];
          window.zhuge[key] = window.zhuge.factory(key);
        }
        window.zhuge.load = function(b, x) {
          if (!document.getElementById("zhuge-js")) {
            var a = document.createElement("script");
            var verDate = new Date();
            var verStr = verDate.getFullYear().toString() + verDate.getMonth().toString() + verDate.getDate().toString();

            a.type = "text/javascript";
            a.id = "zhuge-js";
            a.async = !0;
            a.src = 'https://zgsdk.zhugeio.com/zhuge.min.js?v=' + verStr;
            a.onerror = function() {
              window.zhuge.identify = window.zhuge.track = function(ename, props, callback) {
                if(callback && Object.prototype.toString.call(callback) === '[object Function]') {
                  callback();
                } else if (Object.prototype.toString.call(props) === '[object Function]') {
                  props();
                }
              };
            };
            var c = document.getElementsByTagName("script")[0];
            c.parentNode.insertBefore(a, c);
            window.zhuge._init(b, x)
          }
        };
        window.zhuge.load('74a43f2b04bd4d018212d5e15dd0c2ce', { //配置应用的AppKey
          superProperty: { //全局的事件属性(选填)
            '应用名称': '禾蛙'

          },
          adTrack: false,//广告监测开关，默认为false
          zgsee: false,//视屏采集开关， 默认为false
          autoTrack: false,
          //启用全埋点采集（选填，默认false）
          singlePage: false //是否是单页面应用（SPA），启用autoTrack后生效（选填，默认false）
        });
      })();
    </script>
</head>
<body class="min-h-691">
<?php $this->beginBody() ?>
<div class="container-fluid">

        <?= $content ?>


     <footer class="row justify-content-center  bg-black">
       <div class"w-1200"=>
         <div class=" m-t-10  align-items-center m-auto"  style="width: 800px;">
            <div class="d-flex m-t-15">
                <div class="w-35 p-0-30">
                    <div class="fs-16 text-FF">关于禾蛙</div>
                    <div class="fs-12 m-t-15 text-99">致力于连接更多招聘专家，最大化利用优质活跃的大数据人才库、促成更多职位成功交付的职位共享平台。</div>
                </div>
                <div class="w-28 p-0-20">
                    <div class="fs-16 text-FF">联系禾蛙</div>
                    <div class="fs-12 m-t-15 text-99">禾蛙官方客服</div>
                    <div class="fs-12 m-t-5 text-FF">18012608053</div>
                </div>
                <div class="w-37 p-0-20 d-flex border-left">
                    <div class="w-50 text-center p-0-20">
                        <img src="../../static/image/login/gongzhonghao.png" class="w-80px m-auto" data-toggle="modal" data-target="#qr-wx">
                        <div class="fs-12 m-t-10 text-99 text-center">禾蛙微信公众号</div>
                    </div>
                    <div class="w-50 text-center p-0-20">
                        <img src="../../static/image/login/weixin-erweima.png" class="w-80px m-auto" data-toggle="modal" data-target="#qr-console">
                        <div class="fs-12 m-t-10 text-99 text-center">合作与咨询</div>
                    </div>
                </div>
            </div>
         </div>
         <div class="w-1200  align-items-center p-10-0 text-center" >
             <a target="_blank" href="http://www.beian.miit.gov.cn/" class="text-B5 fs-14 " >苏ICP备14059286号-12</a>
             <span class="text-B5 fs-14 m-r-10 m-l-10 m-r-10">荐客极聘网络技术（苏州）有限公司</span>
             <a href="/" class="text-B5 fs-14">Copyright© 2020-2022 www.hewa.cn</a>
         </div>
         </div>
     </footer>

</div>
<!--禾蛙微信公众号示意图-->
<div class="modal fade" id="qr-wx" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- 模态框头部 -->
            <span class="close delete-note  m-t-15 m-r-20  absolute top-0 right-0 z-9999" data-dismiss="modal">×</span>
            <!-- 模态框主体 -->
            <div class="modal-body  m-t-10">

                <div class="w-100">
                    <div class="text-center m-t-20 m-b-20">
                        <img src="../../static/image/login/gongzhonghao.png" style="width: 300px;">
                        <div class="fs-14 m-t-10 text-99 text-center">禾蛙微信公众号</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--合作与咨询示意图-->
<div class="modal fade" id="qr-console" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- 模态框头部 -->
            <span class="close delete-note  m-t-15 m-r-20  absolute top-0 right-0 z-9999" data-dismiss="modal">×</span>
            <!-- 模态框主体 -->
            <div class="modal-body  m-t-10">

                <div class="w-100">
                    <div class="text-center m-t-20 m-b-20">
                        <img src="../../static/image/login/weixin-erweima.png" style="width: 300px;">
                        <div class="fs-14 m-t-10 text-99 text-center">合作与咨询</div>
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
