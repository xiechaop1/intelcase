
var toTop=`<label class="header-btn-big toTop">
                                    <img src="../../static/image/login/top.png" class="img-no-hover img-50">
                                    <img src="../../static/image/login/top-hover.png" class="img-hover img-50">
                                </label>`;
var toKefu=`<label class="header-btn-big toKefu">
                                    <img src="../../static/image/login/kefu.png" class="img-no-hover img-50">
                                    <img src="../../static/image/login/kefu-hover.png" class="img-hover img-50">
                                    <div class="to-hover-note img-hover">
                                    <div class="fs-12 text-F6">客服官方电话</div>
                                        <div class="fs-16 text-33">18012608053</div>
                                        <div class="fs-12 text-F6 m-t-10">工作日</div>
                                        <div class="fs-12 text-99">09:00-18:00</div>
                                    </div>
                                    </div>
                                    
                                </label>`;
var toErweima=`<label class="header-btn-big toErweima">
                                    <img src="../../static/image/login/weixin.png" class="img-no-hover img-50">
                                    <img src="../../static/image/login/weixin-hover.png" class="img-hover img-50">
                                    <img src="../../static/image/login/erweima.png" class="img-hover to-hover-note2">
                                </label>`;
/*$('body').append(
    $(`
        <div class="toTop rounded-circle opacity-0 bg-FF">
            <img src="../../static/img/toTop.png">
        </div>`)
)*/

$("body").append(toTop).append(toKefu).append(toErweima);


$('.toTop').on('click',function(){
    // $('html').scrollTop(0)
    if (document.documentElement.scrollTop){
        document.documentElement.scrollTop=0;
    }
    if (document.body.scrollTop){
        document.body.scrollTop=0 ;
    }
    zhuge.identify('登录页-侧边栏“回顶部”按钮-点击', {
        id:'loginpage_c_rightbutton',
        right_button:6,
        userid: userId,//预定义属性
        time:time(),//时间
    });
})

window.onscroll = function(){
    showTop();
}

function showTop(){
    const el = document.scrollingElement || document.documentElement;
    el.scrollTop > 10 ? $('.toTop').css({
        opacity: 1
    }) : $('.toTop').css({
        opacity: 0
    });
}
var userId=uniqueid;
$(".toTop").hover(function () {

})
$(".toKefu").hover(function () {
    zhuge.track('登录页-侧边栏“客服”与“二维码”按钮-悬停', {
        id:'loginpage_h_rightbutton',
        right_button:4,
        userid: userId,//预定义属性
        time:time()//时间
    });
})
$(".toErweima").hover(function () {
    zhuge.track('l登录页-侧边栏“客服”与“二维码”按钮-悬停', {
        id:'loginpage_h_rightbutton',
        userid: userId,//预定义属性
        right_button:5,
        time:time()//时间
    });
})

//公用函数获取时间
function time(){
    var myDate = new Date();
    //获取当前年
    var year = myDate.getFullYear();
    //获取当前月
    var month = myDate.getMonth() + 1;
    //获取当前日
    var date = myDate.getDate();
    var h = myDate.getHours(); //获取当前小时数(0-23)
    var m = myDate.getMinutes(); //获取当前分钟数(0-59)
    var s = myDate.getSeconds();
    //获取当前时间
    var now = year+'- '+ conver(month)+"-"+conver(date) +" "+conver(h)+':'+conver(m)+ ":"+ conver(s);
    return now;
}
//日期时间处理
function conver(s) {
    return s < 10 ? '0' + s : s;
}