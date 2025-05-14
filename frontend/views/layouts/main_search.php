<?php $this->beginPage() ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="keywords" content=",猎头做单平台|猎头发单平台|猎头交易平台|猎头顾问|寻找合作猎头|猎头|猎头公司">
        <meta name="description" content="禾蛙是专注链接猎企之间职位交付能力与职位空缺的撮合交易平台,可以直接在线发单接单，解决猎企职位多，来不及做，找不到匹配的候选人，解决顾问候选人多，无处可推荐，让简历不浪费，职位不白费">

        <?php $this->registerCsrfMetaTags() ?>
        <title><?= \yii\bootstrap\Html::encode($this->title) ?></title>
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
    <body>
    <?php $this->beginBody() ?>
    <div class="container-fluid m-height-100 bg-00-80 d-flex justify-content-center pt-5 p-0">
        <div class="w-1200 text-FF">
            <?= $content ?>

        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>

<?php $this->endPage() ?>