(function(){
    //显示login页面
    $(".loginBtn").on("click",function () {
        $("#login").modal('show');

    })

    //修改成输入密码
    $(".change-to-password").on("click",function () {
        $("form[name='accountRemember']").addClass("d-none");
        $("form[name='phone']").removeClass("d-none");

    })

    var loading=`
<div class="modal fade" id="loading-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="width: 200px;">
        <div class="modal-content" style="background: #fff;box-shadow: none;box-shadow: none;border-radius: 4px;">
            <span class="close delete-note  m-t-15 m-r-20  absolute top-0 right-0 z-9999" data-dismiss="modal">×</span>
            <div class="py-2  text-center">
                <div class="px-5 py-4">
                    <img src="../../static/image/loading-1.gif" class="logo">
                    <div class="splash-percentage">加载中...</div>
                </div>
            </div>

        </div>
    </div>
</div>
`
    $('body').append(loading);



    var loginStr = `
            <div class="bg-FF py-4 px-5 d-none" name="loginStr">
                <div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="fs-24">登录留学僧</div>
                        </div>
                        <div>
                            <div class="close" data-dismiss="modal">
                                <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs fs-16 bottomLine d-flex justify-content-around medium">
                        <li>
                            <a class="d-block px-2 py-3 active" href="#phone" data-toggle="tab">手机号登录</a>
                        </li>
                        <li>
                            <a class="d-block px-2 py-3" href="#email" data-toggle="tab">邮箱登录</a>
                        </li>
                    </ul>
                    <div class="mt-3 tab-content">
                        <div class="tab-pane fade show active" id="phone">
                            <form name="phone" method="get">
                                <div class="d-flex align-items-center mt-3 justify-content-between">
                                    <select class="border-0 h-42 bg-F5 w-32 p-2" name="sections">
                                        <option value="44">+44（英国）</option>
                                        <option value="86">+86（中国大陆）</option>
                                        <option value="852">+852（香港）</option>
                                        <option value="853">+853（澳门）</option>
                                        <option value="886">+886（台湾）</option>
                                    </select>
                                    <div class="w-65 bg-F5 p-2 d-flex align-items-center relative">
                                        <span class="text-F6 iconfont iconxingzhuang"></span>
                                        <input name="phone" class="w-80 ml-2" type="text" autocomplete="off" placeholder="手机号">
                                        <div class="invalid-feedback">请输入正确手机号</div>
                                    </div>
                                </div>
                                <div class="w-100 bg-F5 mt-3 p-2 d-flex align-items-center relative">
                                    <span class="text-F6 iconfont iconmima"></span>
                                    <input name="password" class="w-100 ml-2" type="password" placeholder="密码">
                                    <div class="invalid-feedback">密码不得少于6位</div>
                                </div>
                                <div class="text-99 d-flex justify-content-between mt-2">
                                    <span class="fs-18">还没有账号?<span class="text-F6 pointer registBtn">注册</span></span>
                                    <span class="findPassWord pointer">忘记密码？<span>
                                </div>
                                <div class="submit btn btn-danger w-100 mt-4">登录</div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="email">
                            <form name="email">
                                <div class="w-100 bg-F5 mt-3 p-2 d-flex align-items-center relative">
                                    <span class="text-F6 iconfont iconxingzhuang"></span>
                                    <input name="email" class="w-100 ml-2" type="text" autocomplete="off" placeholder="邮箱">
                                    <div class="invalid-feedback">请输入正确邮箱</div>
                                </div>
                                <div class="w-100 bg-F5 mt-3 p-2 d-flex align-items-center relative">
                                    <span class="text-F6 iconfont iconmima"></span>
                                    <input name="password" class="w-100 ml-2" type="password" placeholder="密码">
                                    <div class="invalid-feedback">密码不得少于6位</div>
                                </div>
                                <div class="text-99 d-flex mt-2 justify-content-between">
                                    <span class="fs-18 text-33">还没有账号?<span class="text-F6 pointer registBtn">注册</span></span>
                                    <span class="findPassWord pointer">忘记密码？<span>
                                </div>
                                <div class="submit btn btn-danger w-100 mt-4">登录</div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-5 linetext">第三方登录</div>
                    <div class="d-flex justify-content-between mt-2 ">
                        <div class="pointer d-flex align-items-center text-99"><span class="text-99 mr-1 fs-22 iconfont iconweixindenglu"></span>微信</div>
                        <div class="pointer d-flex align-items-center text-99"><span class="text-99 mr-1 fs-22 iconfont iconqq"></span>QQ</div>
                        <div class="pointer d-flex align-items-center text-99"><span class="text-99 mr-1 fs-22 iconfont iconweibo"></span>微博</div>
                    </div>
                </div>
            </div>
    `
    var registStr = `
        <div class="bg-FF py-4 px-5 d-none" name="registStr">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="fs-24">注册留学僧</div>
                    <span class="fs-18">已有账号?<span class="text-F6 pointer loginBtn">登录</span></span>
                </div>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="btn btn-outline-danger w-100 mt-3 phoneBtn">
                    <span>手机号注册<sapn>
                </div>
                <div class="btn btn-outline-danger w-100 mt-3 emailBtn">
                    <span>邮箱注册<sapn>
                </div>
            </div>
        </div>
    `
    var registPhone = `
        <div class="bg-FF py-4 px-5 d-none" name="registPhone">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="fs-24">手机号注册</div>
                    <span class="fs-18">已有账号?<span class="text-F6 pointer loginBtn">登录</span></span>
                </div>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <form name="phoneReg">
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <select class="w-40 border-EB rounded" name="sections">
                            <option value="44">+44（英国）</option>
                            <option value="86">+86（中国大陆）</option>
                            <option value="852">+852（香港）</option>
                            <option value="853">+853（澳门）</option>
                            <option value="886">+886（台湾）</option>
                        </select>
                        <div class="p-2 ml-3 border-EB rounded w-60 d-flex relative align-items-center">
                            <input name="phone" class="w-100" type="text" autocomplete="off" placeholder="手机号">
                            <div class="invalid-feedback">请输入正确手机号</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 align-items-center">
                        <div class="p-2 w-60 border-EB rounded d-flex relative align-items-center">
                            <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                            <div class="invalid-feedback">请输入验证码</div>
                        </div>
                        <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-phone vcode">发送验证码</div>
                    </div>
                    <div>
                        <div class="p-2 mt-3 border-EB rounded d-flex relative">
                            <input name="password" class="w-100" type="password" placeholder="设置密码">
                            <div class="invalid-feedback">密码不得少于6位</div>
                        </div>
                        <div class="p-2 mt-3 border-EB rounded relative">
                            <input class="w-100" type="text" name="invite" autocomplete="off" placeholder="推荐码">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-center relative">   
                        <input class="" type="checkbox" name="mustCheck" checked id="secretCheck_phone">
                        <label class="mb-0" for="secretCheck_phone">我已阅读并同意网站的使用条件以及隐私声明</label>
                        <div class="invalid-feedback">请同意</div>
                    </div>
                    <div class="d-flex align-items-center relative">
                        <input class="" type="checkbox" name="push" checked id="receiveCheck_phone">
                        <label class="mb-0" for="receiveCheck_phone">同意接收推送订阅消息</label>
                    </div>
                </div>
                <div class="submit w-100 btn btn-danger mt-3">
                    注册
                </div>
            </form>
            <div class="mt-2 pointer text-right text-99 emailBtn">
                邮箱注册 >
            </div>
        </div>
    `
    var registEmail = `
        <div class="bg-FF py-4 px-5 d-none" name="registEmail">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="fs-24">邮箱注册</div>
                    <span class="fs-18">已有账号?<span class="text-F6 pointer loginBtn">登录</span></span>
                </div>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <form name="emailReg">
                <div class="mt-3">
                    <div class="p-2 border-EB rounded d-flex relative align-items-center">
                        <input name="email" class="w-100" type="text" autocomplete="off" placeholder="邮箱地址">
                        <div class="invalid-feedback">请输入正确邮箱</div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 align-items-center">
                        <div class="p-2 w-60 border-EB rounded d-flex relative align-items-center">
                            <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                            <div class="invalid-feedback">请输入验证码</div>
                        </div>
                        <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-mail vcode">发送验证码</div>
                    </div>
                    <div>
                        <div class="p-2 mt-3 border-EB rounded d-flex relative">
                            <input name="password" class="w-100" type="password" placeholder="设置密码">
                            <div class="invalid-feedback">密码不得少于6位</div>
                        </div>
                        <div class="p-2 mt-3 border-EB rounded relative">
                            <input class="w-100" type="text" name="invite" autocomplete="off" placeholder="推荐码">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="mt-3">
                    <div class="d-flex align-items-center">   
                        <input class="" type="checkbox" name="mustCheck" checked id="secretCheck_email">
                        <label class="mb-0" for="secretCheck_email">我已阅读并同意网站的使用条件以及隐私声明</label>
                        <div class="invalid-feedback">请同意</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <input class="" type="checkbox" name="push" checked id="receiveCheck_email">
                        <label class="mb-0" for="receiveCheck_email">同意接收推送订阅消息</label>
                    </div>
                </div>
                </div>
                <div class="submit w-100 btn btn-danger mt-3">
                    注册
                </div>
            </form>
            <div class="mt-2 pointer text-right text-99 phoneBtn">
                手机号注册 >
            </div>
        </div>
    `
    var findPassWord = `
        <div class="bg-FF py-4 px-5 d-none" name="findPassWord">
            <div class="d-flex justify-content-between">
                <ul class="nav nav-tabs findpswTab align-items-center">
                    <li>
                        <a class="nav-link active" data-toggle="tab" href="#phoneFind">手机找回</a>
                    </li>
                    <li>
                        <a class="nav-link" data-toggle="tab" href="#emailFind">邮箱找回</a>
                    </li>
                </ul>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="mt-3 tab-pane fade show active" id="phoneFind">
                    <form name="phoneFind">
                        <div class="d-flex justify-content-between">
                            <select class="w-40 border-EB rounded" name="sections">
                                <option value="44">+44（英国）</option>
                                <option value="86">+86（中国大陆）</option>
                                <option value="852">+852（香港）</option>
                                <option value="853">+853（澳门）</option>
                                <option value="886">+886（台湾）</option>
                            </select>
                            <div class="p-2 ml-3 border-EB rounded w-60 d-flex relative">
                                <input class="w-100" name="phone" type="text" autocomplete="off" placeholder="手机号">
                                <div class="invalid-feedback">请输入正确手机号</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3 align-items-center">
                            <div class="p-2 w-60 border-EB rounded d-flex relative">
                                <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                                <div class="invalid-feedback">请输入验证码</div>
                            </div>
                            <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-phone vcode">发送验证码</div>
                        </div>
                        <div>
                            <div class="p-2 mt-3 border-EB rounded d-flex relative">
                                <input name="oldPassword" class="w-100" type="password" placeholder="设置密码">
                                <div class="invalid-feedback">密码不得少于6位</div>
                            </div>
                            <div class="p-2 mt-3 border-EB rounded d-flex relative">
                                <input name="newPassword" class="w-100" type="password" placeholder="再次输入新的密码">
                                <div class="invalid-feedback">两次密码必须一致</div>
                            </div>
                        </div>
                        <div class="w-100 submit btn btn-danger mt-5">
                            保存
                        </div>
                    </form>
                </div>
                <div class="mt-3 tab-pane fade" id="emailFind">
                    <form name="emailFind">
                        <div class="p-2 border-EB rounded d-flex relative">
                            <input class="w-100" type="text" autocomplete="off" name="email" placeholder="邮箱地址">
                            <div class="invalid-feedback">请输入正确邮箱</div>
                        </div>
                        <div class="d-flex justify-content-between mt-3 align-items-center">
                            <div class="p-2 w-60 border-EB rounded d-flex relative">
                                <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                                <div class="invalid-feedback">请输入验证码</div>
                            </div>
                            <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-mail vcode">发送验证码</div>
                        </div>
                        <div>
                            <div class="p-2 mt-3 border-EB rounded d-flex relative">
                                <input name="oldPassword" class="w-100" type="password" placeholder="设置密码">
                                <div class="invalid-feedback">密码不得少于6位</div>
                            </div>
                            <div class="p-2 mt-3 border-EB rounded d-flex relative">
                                <input name="newPassword" class="w-100" type="password" placeholder="再次输入新的密码">
                                <div class="invalid-feedback">两次密码必须一致</div>
                            </div>
                        </div>
                        <div class="w-100 submit btn btn-danger mt-5">
                            保存
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `

    init()
    render('loginStr')
    
    
    function render(inner){
        hideAll()
        $(`div[name="${inner}"]`).removeClass('d-none')
        
    }
    function init(){
        var final = `<div class="modal fade" id="login" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                ${registStr}${loginStr}${registPhone}${registEmail}${findPassWord}
                            </div>
                        </div>
                    </div>`
        
        
       // $('body').append(final)
        bindEvent()
        $.bindCheck()
    }
    function hideAll(){
        $('div[name="loginStr"],div[name="registStr"],div[name="registPhone"],div[name="registEmail"],div[name="findPassWord"]').each(function(ind,ele){
            $(ele).hasClass('d-none') ? '':$(ele).addClass('d-none')
        })
    }

    function bindEvent(){
        $('.registBtn').on('click',function(){
            render('registStr')
        })
        $('.loginBtn').on('click',function(){
            render('loginStr')
        })
        $('.modal[id="login"]').on('hidden.bs.modal',function(){
            render('loginStr')
        })
        $('.phoneBtn').on('click',function(){
            render('registPhone')
        })
        $('.emailBtn').on('click',function(){
            render('registEmail')
        })
        $('.findPassWord').on('click',function(){
            render('findPassWord')
        })
        $('.submit').click(submitHandle);

        $('input[name="vcode"],input[name="password"]').on('keydown',function(e){
            if(e.keyCode === 13){
                submitHandle(e)
            }
        })
        $.vcode()
    }

    //诸葛登录按钮点击埋点
    function zhugeLoginBtnClick() {
        zhuge.track('“登录”按钮-点击', {
            id:'loginpage_c_loginbutton',
            time: time(),//时间
            source_page: pageName()  //预定义属性
        });
        zhuge.identify('loginpage_c_loginbutton',{
            '事件名称':'“登录”按钮-点击',
            'time':time(),//时间
            'source_page': pageName()  //预定义属性
        })
        console.log("loginpage_c_loginbutton:"+time(),pageName())
    }

    //诸葛页面识别
    function pageName() {
        var winUrl=window.location.pathname;
        var pageName='';

        switch(winUrl){
            case '':
            case '/':
            case '/site/index':
                pageName="官网首页";
                break;
            case '/site/index_not_login':
                pageName="小蛙推荐";
                break;
            case '/job/job_detail':
                pageName="职位详情";
                break;
            case '/h5/job_detail':
                pageName="H5职位详情";
                break;
            case '/h5/job_list':
                pageName="H5职位推荐列表";
                break;
            case '/passport/web_login':
                pageName="PC登录页面";
                break;
        }

        return pageName;

    }


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
        var now = year+'-'+ conver(month)+"-"+conver(date) +" "+conver(h)+':'+conver(m)+ ":"+ conver(s);
        return now;
    }

    function submitHandle(e){
        console.log("login.js")

        var $form = $(e.target).closest('form');

        var me=$form.find('.submit');


        //登录按钮点击埋点
        var formName = $form.attr('name');
        switch(formName){
            case "phoneCode" :
            case "phone":
                zhugeLoginBtnClick();
                break;
        }


        $.checkForm($form,function(){
            var name = this.attr('name');
            // console.log(this.attr('name').toString())
            var self = this;
            var target_url=$("input[name='target_url']").val();
            // target_url.length>0?console.log('target_url存在'):target_url='/';
            if(!target_url||target_url.split("passport/web_register").length>1||target_url.split("hewa").length==1){
                target_url='/';
            }
            var source=$("[name='source']").val();




            switch(name){
                case "phoneCode" :
                    $("#loading-modal").modal({
                        backdrop: 'static', //点击遮罩层不会被关闭
                    },'show');
                    //手机号和验证码去除非数字内容
                    var phoneNum=$form.find("input[name='phone']");
                    var vcodeNum=$form.find("input[name='vcode']");
                    phoneNum.val(phoneNum.val().replace(/[^0-9]/g,''));
                    vcodeNum.val(vcodeNum.val().replace(/[^0-9]/g,''));

                    console.log('phoneCode');
                    if(me.hasClass("disabled")){

                    }else {
                        me.empty().text("登录中...").addClass("disabled");
                        $.ajax({
                            url: "/passport/login",
                            type: "POST",
                            data: {
                                way : "verification_code",
                                mobile_section : `${self.find("select").val()}`,
                                mobile : self.find("input[name='phone']").val(),
                                user_name:self.find("input[name='user_name']").val(),
                                email:self.find("input[name='email']").val(),
                                verificationCode: self.find("input[name='vcode']").val(),
                                rememberPhone:self.find("input[name='rememberPhone']").is(':checked'),
                                source:source,
                                invite_code:self.find("input[name='invite_code']").val(),
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                me.removeClass("disabled");
                            },
                            success: function(result) {
                                if(!result.status){
                                    $.alert(result.msg)
                                }
                                else if(result.status&&result.data.invite_code_timeout==1){
                                    $("#invite-out-time").modal('show');
                                }
                                else if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==10){

                                    // window.location.href='/passport/web_register?type=1&member_status=10&id='+result.data.id
                                    window.location.href=target_url
                                    //window.location.href='/'
                                }
                                else if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==0){

                                    //window.location.href='/passport/web_register?type=1&member_status=0&id='+result.data.id;
                                    window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==10){

                                    window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id
                                    //window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==0){

                                    //window.location.href='/passport/web_register?type=2&member_status=0&id='+result.data.id;
                                    window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==20&&result.data.identity.member_status==10){
                                    //window.location.href='/passport/web_register?type=2&member_status=0&id='+result.data.id;
                                    //window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id
                                    window.location.href=target_url
                                }
                                else if(result.status){
                                    window.location.href=target_url;
                                }


                                me.removeClass("disabled").empty().text("登录");


                            }
                        })


                    }

                    break;
                case "phoneCodeModal" :
                    $("#loading-modal").modal({
                        backdrop: 'static', //点击遮罩层不会被关闭
                    },'show')
                    //手机号和验证码去除非数字内容
                    var phoneNum=$form.find("input[name='phone']");
                    var vcodeNum=$form.find("input[name='vcode']");
                    phoneNum.val(phoneNum.val().replace(/[^0-9]/g,''));
                    vcodeNum.val(vcodeNum.val().replace(/[^0-9]/g,''));

                    console.log('phoneCode');
                    if(me.hasClass("disabled")){

                    }else {
                        me.addClass("disabled");
                        $.ajax({
                            url: "/passport/login",
                            type: "POST",
                            data: {
                                way : "verification_code",
                                mobile_section : `${self.find("select").val()}`,
                                mobile : self.find("input[name='phone']").val(),
                                user_name:self.find("input[name='user_name']").val(),
                                email:self.find("input[name='email']").val(),
                                verificationCode: self.find("input[name='vcode']").val(),
                                rememberPhone:self.find("input[name='rememberPhone']").is(':checked'),
                                source:source,
                                invite_code:self.find("input[name='invite_code']").val(),
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                me.removeClass("disabled");
                            },
                            success: function(result) {

                                if(!result.status){
                                    $.alert(result.msg)
                                }
                                else if(result.data.invite_code_timeout==1){
                                    $("#invite-out-time").modal('show');
                                }
                                else if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==10){

                                   window.location.reload()
                                }
                                else if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==0){

                                    window.location.reload()
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==10){

                                    window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id
                                    //window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==0){

                                    window.location.reload()
                                }
                                else if(result.status&&result.data.identity.type==20&&result.data.identity.member_status==10){
                                    window.location.reload()
                                }
                                else if(result.status){
                                    window.location.reload()
                                }
                                me.removeClass("disabled");


                            }
                        })

                    }

                    break;
                case 'phone' :
                    //手机号和验证码去除非数字内容
                    $("#loading-modal").modal({
                        backdrop: 'static', //点击遮罩层不会被关闭
                    },'show')
                    var phoneNum=$form.find("input[name='phone']");
                    phoneNum.val(phoneNum.val().replace(/[^0-9]/g,''));
                    console.log('phone')
                    if(me.hasClass("disabled")){

                    }else {
                        me.empty().text("登录中...").addClass("disabled");
                        $.ajax({
                            url: "/passport/login",
                            type: "POST",
                            data: {
                                way : "mobile",
                                mobile : self.find("input[name='phone']").val(),
                                mobile_section : `${self.find("select[name='sections']").val()}`,
                                email:'',
                                password : self.find("input[name='password']").val(),
                                remember_password : self.find("input[name='rememberPassword']").is(':checked'),
                                source:source,
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                me.removeClass('disabled')
                            },
                            success: function(result) {

                                if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==10){
                                    // window.location.href='/passport/web_register?type=1&member_status=10&id='+result.data.id;
                                    window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==0){
                                    // window.location.href='/passport/web_register?type=1&member_status=0&id='+result.data.id;
                                    window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==10){
                                     window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id;
                                   // window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==0){
                                     //window.location.href='/passport/web_register?type=2&member_status=0&id='+result.data.id;
                                    window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==20&&result.data.identity.member_status==10){
                                    //window.location.href='/passport/web_register?type=2&member_status=0&id='+result.data.id;
                                   // window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id
                                    window.location.href=target_url
                                }
                                else if(result.status){
                                    window.location.href=target_url;
                                }
                                else{
                                    $.alert(result.msg)
                                }
                                me.removeClass('disabled').empty().text("登录")
                            }
                        })
                    }
                    break;
                case 'phoneModal' :
                    //手机号和验证码去除非数字内容
                    var phoneNum=$form.find("input[name='phone']");
                    phoneNum.val(phoneNum.val().replace(/[^0-9]/g,''));
                    console.log('phone')
                    if(me.hasClass("disabled")){

                    }else {
                        me.addClass("disabled");
                        $.ajax({
                            url: "/passport/login",
                            type: "POST",
                            data: {
                                way : "mobile",
                                mobile : self.find("input[name='phone']").val(),
                                mobile_section : `${self.find("select[name='sections']").val()}`,
                                email:'',
                                password : self.find("input[name='password']").val(),
                                remember_password : self.find("input[name='rememberPassword']").is(':checked'),
                                source:source,
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                me.removeClass('disabled')
                            },
                            success: function(result) {

                                if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==10){
                                   window.location.reload()
                                }
                                else if(result.status&&result.data.identity.type==1&&result.data.identity.member_status==0){
                                    window.location.reload()
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==10){
                                    window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id;
                                    // window.location.href=target_url
                                }
                                else if(result.status&&result.data.identity.type==10&&result.data.identity.member_status==0){
                                    window.location.reload()
                                }
                                else if(result.status&&result.data.identity.type==20&&result.data.identity.member_status==10){
                                    window.location.reload()
                                }
                                else if(result.status){
                                    window.location.reload()
                                }
                                else{
                                    $.alert(result.msg)
                                }
                                me.removeClass('disabled')
                            }
                        })
                    }
                    break;
                case 'account' :
                        console.log('account')
                    if(me.hasClass("disabled")){

                    }else {
                        me.addClass("disabled");
                        $.ajax({
                            url: "/passport/login",
                            type: "POST",
                            data: {
                                way : "user_name",
                                user_name : self.find("input[name='account']").val(),
                                password : self.find("input[name='password']").val(),
                                remember_password : self.find("input[name='rememberPassword']").val(),
                                source:source,
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                me.removeClass('disabled')
                            },
                            success: function(result) {
                                result.status ?
                                    window.location.href=target_url : $.alert(result.msg)
                                me.removeClass('disabled')
                            }
                        })

                    }

                    break;
                case 'accountRemember':

                    console.log('accountRemember')
                    if(me.hasClass("disabled")){

                    }else {
                        me.addClass("disabled");
                        $.ajax({
                            url: "/passport/login",
                            type: "POST",
                            data: {
                                way : "keep_login",
                                mobile_section : self.find("input[name='section']").val(),
                                mobile : self.find("input[name='mobile']").val(),
                                source:source,
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                me.removeClass('disabled')
                            },
                            success: function(result) {
                                result.status ?
                                    window.location.href=target_url : $.alert(result.msg)
                                me.removeClass('disabled')
                            }
                        })

                    }
                    break;

                case "email" :
                    console.log('email')
                    $.ajax({
                        url: "/passport/login",
                        type: "POST",
                        data: {
                            way : "email",
                            mobile : '',
                            mobile_section : '',
                            email:self.find("input[name='email']").val(),
                            password : self.find("input[name='password']").val()
                        },
                        success: function(result) {
                            result.status ?
                                window.location.reload() : $.alert(result.msg)
                        }
                    })
                    break;
                case "registerPerson" :
                    console.log('registerPerson');
                    var select=$("#special option:selected");
                    var good=[];//擅长内容
                    var  profession_type=[];
                    //var good=''
                    select.each(function () {
                       // good+=$(this).val()+","
                         good.push($(this).val())
                    })
                    $("input[name='industry']:checked").each(function () {
                        // good+=$(this).val()+","
                        profession_type.push($(this).val())
                    })

                    $.ajax({
                        url: "/passport/register",
                        type: "POST",
                        data: {
                            type:1,
                            way : "verification_code",
                            mobile_section : `${self.find("select").val()}`,
                            mobile : self.find("input[name='phone']").val(),
                            email:self.find("input[name='email']").val(),
                            verificationCode: self.find("input[name='vcode']").val(),
                            user_name:self.find("input[name='account']").val(),
                            true_name:self.find("input[name='userName']").val(),
                            password:self.find("input[name='password']").val(),
                            password2:self.find("input[name='confirmPassword']").val(),
                            company_id:self.find("input[name='company_id']").val(),
                            remark:self.find("textarea[name='remark']").val(),
                            member_special:good,
                            wx:self.find("input[name='wx']").val(),
                            profession_type:profession_type,
                            source:source,
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                            $.alert("服务器异常，请稍后重试")
                        },
                        success: function(result) {
                            console.log(JSON.stringify(result))
                            if(result.status){
                                // $("#register-person-3").addClass("d-none");
                                // $("#register-person-4").removeClass('d-none');

                                if(result.data.member_status==1){
                                    window.location.href='/'
                                }
                                if(result.data.member_status==0){
                                    $("#register-person-4").removeClass("d-none");
                                    $("#register-person-2,#register-person-3,#register-person-5").addClass('d-none')
                                }
                                if(result.data.member_status==10){
                                  /*  $("#register-person-5").removeClass("d-none");
                                    $("#register-person-2,#register-person-3,#register-person-4").addClass('d-none')*/
                                    window.location.href='/passport/web_register?type=1&member_status=10&id='+result.data.id;

                                }
                                else{
                                    $("#register-person-4").removeClass("d-none");
                                    $("#register-person-2,#register-person-3,#register-person-5").addClass('d-none')

                                }

                            }
                            else {
                                $.alert(result.msg)
                            }
                        }
                    })

                    break;
                case "registerCompany" :
                    console.log('registerCompany');
                    var select=$("#special option:selected");
                    var good=[]//擅长内容
                   // var good=''
                    select.each(function () {
                        //good+=$(this).val()+","
                        good.push($(this).val())
                    })
                    var  profession_type=[];
                    $("input[name='industry']:checked").each(function () {
                        // good+=$(this).val()+","
                        profession_type.push($(this).val())
                    })
                    $.ajax({
                        url: "/passport/register",
                        type: "POST",
                        data: {
                            type:10,
                            way : "verification_code",
                            mobile_section : `${self.find("select").val()}`,
                            mobile : self.find("input[name='phone']").val(),
                            email:self.find("input[name='email']").val(),
                            verificationCode: self.find("input[name='vcode']").val(),
                            license:self.find("input[name='license']").val(),
                            company_name:self.find("input[name='company_name']").val(),
                            company_position:self.find("input[name='company_position']").val(),
                            user_name:self.find("input[name='account']").val(),
                            password:self.find("input[name='password']").val(),
                            password2:self.find("input[name='confirmPassword']").val(),
                            true_name:self.find("input[name='userName']").val(),
                            identity_no:self.find("input[name='identity_no']").val(),
                            authorize:self.find("input[name='authorize']").val(),
                            member_special:good,
                            wx:self.find("input[name='wx']").val(),
                            profession_type:profession_type,
                            source:source,
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                            $.alert("服务器异常，请稍后重试")
                        },
                        success: function(result) {
                            console.log(JSON.stringify(result))
                            if(result.status){
                             /*   $("#register-company-3").addClass("d-none");
                                $("#register-company-4").removeClass('d-none');*/
                                if(result.data.member_status==1){
                                    window.location.href='/'
                                }
                                if(result.data.member_status==0){
                                    $("#register-company-4").removeClass("d-none");
                                    $("#register-company-2,#register-company-3,#register-company-5").addClass('d-none')
                                }
                                if(result.data.member_status==10){
                                   /* $("#register-company-5").removeClass("d-none");
                                    $("#register-company-2,#register-company-3,#register-company-4").addClass('d-none');*/
                                    window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id;

                                }
                                else{
                                    $("#register-company-4").removeClass("d-none");
                                    $("#register-company-2,#register-company-3,#register-company-5").addClass('d-none')

                                }
                            }
                            else {
                                $.alert(result.msg)
                            }
                            console.log(JSON.stringify(result))
                        }
                    })

                    break;
                case "registerCompanyPhone":
                    console.log('registerCompanyPhone')
                    $.ajax({
                        url: "/passport/check_mobile",
                        type: "POST",
                        data: {
                            // way : "verification_code",
                            mobile_section : self.find("select[name='sections']").val(),
                            mobile : self.find("input[name='phone']").val(),
                            verificationCode: self.find("input[name='vcode']").val(),
                            source:source,
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                        },
                        success: function(result) {
                            console.log(JSON.stringify(result))
                            var status = result.status;
                            var code=result.code;
                            if(status){
                                switch (code) {
                                    case 200:
                                        $("section").addClass("d-none");
                                        $("#register-company-3").removeClass("d-none");
                                        $("input[name='mobile_section']").val(self.find("select").val());
                                        $("input[name='mobile']").val(self.find("input[name='phone']").val())
                                        break;
                                    case 1000:
                                        $("section").addClass("d-none");
                                        $("#register-company-phone-repeat").removeClass("d-none");
                                        break;
                                    case 1008:
                                        window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id;
                                        break;
                                    default:
                                        $.alert(result.msg)
                                        break;
                                }


                            }else{
                                switch (code) {
                                    case 1003:
                                        var phoneStatus=result.data.member_status
                                        if(phoneStatus==0){
                                            $("section").addClass("d-none");
                                            $("#register-company-phone-check").removeClass("d-none");
                                        }
                                        else if(phoneStatus==1){
                                            $("section").addClass("d-none");
                                            $("#register-company-phone-repeat").removeClass("d-none");
                                        }
                                        break;
                                    case 1008:
                                        window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id;
                                        break;
                                    default:
                                        $.alert(result.msg)
                                        break;
                                }
                            }

                        }
                    })

                    break;

                case "registerCompanyInfo":
                    console.log('registerCompanyInfo');
                    var companyName=$("input[name='company_name']").val();

                    var id='';
                    var contract="";
                    var groupName='';
                    var companyStatus=''

                    if(companyName){
                        $.ajax({
                            type: "GET", //用POST方式传输
                            dataType: "json", //数据格式:JSON
                            async: false,
                            url: '/company/get_consultant_company_list_by_name?company_name='+companyName,
                            data:{},
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                /*alert(XMLHttpRequest.status);
                                alert(XMLHttpRequest.readyState);
                                alert(textStatus);*/
                                console.log("ajax请求失败:"+XMLHttpRequest,textStatus,errorThrown);
                                $.alert("网络异常，请检查网络情况");
                            },
                            success: function (data, status){
                                var dataContent=data;
                                var dataCon=$.toJSON(dataContent);
                                var obj = eval( "(" + dataCon + ")" );//转换后的JSON对象
                                //console.log("ajax请求成功:"+data.toString())
                                //新消息获取成功
                                if(obj["code"]==200){
                                    var company=obj.data;

                                    for(var i=0;i<company.length;i++){
                                        if(companyName==company[i].company_name){
                                            id=company[i].id;
                                            contract=company[i].contract;
                                            groupName=company[i].group_name;
                                            companyStatus=company[i].company_status;
                                            break;
                                        }
                                    }
                                    console.log("公司状态："+companyStatus)

                                    switch (companyStatus){
                                        case 1:
                                            $("input[name='company_name']").addClass("is-invalid");
                                            $("[name='company-status-error']").removeClass("d-none").empty().text("该公司已被注册，请联系对方管理员或禾蛙管理员");
                                           // $.alert("公司已注册");
                                            break;
                                        case 0:
                                            $("input[name='company_name']").addClass("is-invalid");
                                            $("[name='company-status-error']").removeClass("d-none").empty().text("该公司已有其他同事提交审核，请耐心等待");
                                            //$.alert("该公司已有其他同事提交审核，请耐心等待");
                                            break;
                                       /* case 10:
                                            $("input[name='company_name']").removeClass("is-invalid");
                                            $("[name='company-status-error']").addClass("d-none").empty();
                                            break;*/
                                        default:
                                            $("input[name='company_name']").removeClass("is-invalid");
                                            $("[name='company-status-error']").addClass("d-none").empty();
                                            $.ajax({
                                                url: "/passport/register",
                                                type: "POST",
                                                data: {
                                                    type:10,
                                                    way : "verification_code",
                                                    mobile_section : self.find("input[name='mobile_section']").val(),
                                                    mobile : self.find("input[name='mobile']").val(),
                                                    email:self.find("input[name='email']").val(),
                                                    // verificationCode: self.find("input[name='vcode']").val(),
                                                    license:self.find("input[name='license']").val(),
                                                    company_name:self.find("input[name='company_name']").val(),
                                                    company_position_province:self.find("[name='company_position[provinceOfChina]']").val(),
                                                    company_position_city:self.find("[name='company_position[cityOfChina]']").val(),
                                                    company_position:self.find("[name='company_position[cityOfChina]']").val(),
                                                    //user_name:self.find("input[name='account']").val(),
                                                    // password:self.find("input[name='password']").val(),
                                                    // password2:self.find("input[name='confirmPassword']").val(),
                                                    true_name:self.find("input[name='userName']").val(),
                                                    identity_no:self.find("input[name='identity_no']").val(),
                                                    authorize:self.find("input[name='authorize']").val(),
                                                    source:source,
                                                    // member_special:good,
                                                    // wx:self.find("input[name='wx']").val(),
                                                    //profession_type:profession_type,
                                                    //legal_person:self.find("input[name='legal_person']").val(),
                                                },
                                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                    console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                                    $.alert("服务器异常，请稍后重试")
                                                },
                                                success: function(result) {
                                                    console.log(JSON.stringify(result))
                                                    var status = result.status;
                                                    var code=result.code;
                                                
                                                    if(status){
                                                        switch (code) {
                                                            case 200:

                                                                if(result.data.member_status==1){
                                                                    // $("section").addClass("d-none");
                                                                    // $("#register-company-success").removeClass("d-none");
                                                                    //window.location.href='/'
                                                                    window.location.href='/'
                                                                }
                                                                else if(result.data.member_status==0){
                                                                    // $("#register-company-4").removeClass("d-none");
                                                                    // $("#register-company-2,#register-company-3,#register-company-5").addClass('d-none')

                                                                    // $("section").addClass("d-none");
                                                                    // $("#register-company-success").removeClass("d-none");
                                                                    window.location.href='/'
                                                                }
                                                                else if(result.data.member_status==10){
                                                                    /* $("#register-company-5").removeClass("d-none");
                                                                     $("#register-company-2,#register-company-3,#register-company-4").addClass('d-none');*/
                                                                    window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id;

                                                                }
                                                                else{
                                                                    window.location.href='/'
                                                                 /*   $("#register-company-4").removeClass("d-none");
                                                                    $("#register-company-2,#register-company-3,#register-company-5").addClass('d-none')*/

                                                                }
                                                                break;
                                                            default:
                                                                $.alert(result.msg)
                                                                break;
                                                        }


                                                    }else{
                                                        switch (code) {
                                                            case 1003:
                                                                $('[name="company_name"]').addClass("is-invalid");
                                                                $('[name="company-status-error"]').empty().text("该公司已被注册，请联系对方管理员或禾蛙管理员").removeClass('d-none');
                                                                $.alert(result.msg)
                                                                break;
                                                            case 200:
                                                                $('[name="company_name"]').addClass("is-invalid");
                                                                $('[name="company-status-error"]').empty().text("该公司已有其他同事提交审核，请耐心等待").removeClass('d-none');
                                                                $.alert(result.msg)
                                                                break;
                                                            default:
                                                                $.alert(result.msg)
                                                                break;
                                                        }
                                                    }

                                                    console.log(JSON.stringify(result))
                                                }
                                            })
                                            break;

                                    }

                                 /*   console.log("公司信息：",id,companyStatus)
                                    if(id||companyStatus==1||companyStatus==0){
                                        console.log("猎企信息注册未开始")
                                    }
                                    else{
                                        console.log("猎企信息注册开始")
                                        $.ajax({
                                            url: "/passport/register",
                                            type: "POST",
                                            data: {
                                                type:10,
                                                way : "verification_code",
                                                mobile_section : self.find("input[name='mobile_section']").val(),
                                                mobile : self.find("input[name='mobile']").val(),
                                                // email:self.find("input[name='email']").val(),
                                                // verificationCode: self.find("input[name='vcode']").val(),
                                                license:self.find("input[name='license']").val(),
                                                company_name:self.find("input[name='company_name']").val(),
                                                company_position_province:self.find("input[name='company_position[provinceOfChina]']").val(),
                                                company_position_city:self.find("input[name='company_position[cityOfChina]']").val(),
                                                //user_name:self.find("input[name='account']").val(),
                                                // password:self.find("input[name='password']").val(),
                                                // password2:self.find("input[name='confirmPassword']").val(),
                                                true_name:self.find("input[name='userName']").val(),
                                                identity_no:self.find("input[name='identity_no']").val(),
                                                authorize:self.find("input[name='authorize']").val(),
                                                // member_special:good,
                                                // wx:self.find("input[name='wx']").val(),
                                                //profession_type:profession_type,
                                                //legal_person:self.find("input[name='legal_person']").val(),
                                            },
                                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                                $.alert("服务器异常，请稍后重试")
                                            },
                                            success: function(result) {
                                                console.log(JSON.stringify(result))
                                                var status = result.status;
                                                var code=result.code;
                                                if(status){
                                                    switch (code) {
                                                        case 200:
                                                            if(result.data.member_status==1){
                                                                $("section").addClass("d-none");
                                                                $("#register-company-success").removeClass("d-none");
                                                                //window.location.href='/'
                                                            }
                                                            if(result.data.member_status==0){
                                                                $("#register-company-4").removeClass("d-none");
                                                                $("#register-company-2,#register-company-3,#register-company-5").addClass('d-none')
                                                            }
                                                            if(result.data.member_status==10){
                                                                /!* $("#register-company-5").removeClass("d-none");
                                                                 $("#register-company-2,#register-company-3,#register-company-4").addClass('d-none');*!/
                                                                window.location.href='/passport/web_register?type=2&member_status=10&id='+result.data.id;

                                                            }
                                                            else{
                                                                $("#register-company-4").removeClass("d-none");
                                                                $("#register-company-2,#register-company-3,#register-company-5").addClass('d-none')

                                                            }
                                                            break;
                                                        default:
                                                            $.alert(result.msg)
                                                            break;
                                                    }


                                                }else{
                                                    switch (code) {
                                                        case 1003:
                                                            $('[name="company_name"]').addClass("is-invalid");
                                                            $('[name="company-status-error"]').empty().text("该公司已被注册，请联系对方管理员或禾蛙管理员").removeClass('d-none');
                                                            $.alert(result.msg)
                                                            break;
                                                        case 200:
                                                            $('[name="company_name"]').addClass("is-invalid");
                                                            $('[name="company-status-error"]').empty().text("该公司已有其他同事提交审核，请耐心等待").removeClass('d-none');
                                                            $.alert(result.msg)
                                                            break;
                                                        default:
                                                            $.alert(result.msg)
                                                            break;
                                                    }
                                                }

                                                console.log(JSON.stringify(result))
                                            }
                                        })

                                    }*/






                                }
                                //新消息获取失败
                                else{

                                }

                            }
                        });
                    }
                    else {
                        $.alert("请输入公司注册名称")
                    }




                    break;

                case "phoneFind" :
                    console.log('phoneFind')
                    var data1={
                        mobile : self.find("input[name='phone']").val(),
                        mobile_section : `${self.find("select").val()}`,
                        verification_code: self.find("input[name='vcode']").val(),
                        password:self.find("input[name='password']").val(),
                        password2:self.find("input[name='confirmPassword']").val(),
                    };
                    console.log("/passport/reset_password?"+JSON.stringify(data1))
                    $.ajax({
                        url: "/passport/reset_password",
                        type: "POST",
                        data: {
                            mobile : self.find("input[name='phone']").val(),
                            mobile_section : `${self.find("select").val()}`,
                            verification_code: self.find("input[name='vcode']").val(),
                            password:self.find("input[name='password']").val(),
                            password2:self.find("input[name='confirmPassword']").val(),
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                        },
                        success: function(result) {
                            result.status ? window.location.href='/passport/web_login' : $.alert(result.msg)
                        }
                    })
                    break;
                case "phoneReg" :
                    console.log('phoneReg')
                    $.ajax({
                        url: "/passport/reg-click",
                        type: "GET",
                        success: function(res){
                            $.ajax({
                                url: "/passport/register",
                                type: "POST",
                                data: {
                                    way : "mobile",
                                    password : self.find("input[name='password']").val(),
                                    mobile_section : `${self.find("select").val()}`,
                                    mobile : self.find("input[name='phone']").val(),
                                    email:'',
                                    verification_code: self.find("input[name='vcode']").val(),
                                    invite_code: self.find("input[name='invite']").val(),
                                    allow_push: self.find("input[name='push']")[0].checked ? '1' : '2',
                                    tooken:res.data.tooken
                                },
                                success: function(result) {
                                    result.status ?
                                        window.location.reload() : $.alert(result.msg)
                                }
                            })
                        }
                    })

                    break;
                case "emailReg" :
                    console.log('emailReg')
                    $.ajax({
                        url: "/passport/reg-click",
                        type: "GET",
                        success:function(res){
                            $.ajax({
                                url: "/passport/register",
                                type: "POST",
                                data: {
                                    way : "email",
                                    password : self.find("input[name='password']").val(),
                                    mobile_section : '',
                                    mobile : '',
                                    email:self.find("input[name='email']").val(),
                                    verification_code: self.find("input[name='vcode']").val(),
                                    invite_code: self.find("input[name='invite']").val(),
                                    allow_push: self.find("input[name='push']")[0].checked ? '1' : '2',
                                    tooken: res.data.tooken
                                },
                                success: function(result) {
                                    result.status ?
                                    window.location.reload() : $.alert(result.msg)
                                }
                            })
                        }
                    })

                    break;
                case "emailFind" :
                    console.log('emailFind')
                    $.ajax({
                        url: "/passport/reset-password",
                        type: "POST",
                        data: {
                            way : "email",
                            mobile_section : '',
                            mobile : '',
                            email:self.find("input[name='email']").val(),
                            verification_code: self.find("input[name='vcode']").val(),
                            invite_code: self.find("input[name='invite']").val(),
                            password : self.find("input[name='newPassword']").val(),
                        },
                        success: function(result) {
                            result.status ?
                                window.location.reload() : $.alert(result.msg)
                        }
                    })
                    break;
                case 'form-bind-company':
                    console.log('form-bind-company');
                    if(me.hasClass("disabled")){

                    }else{
                        me.addClass("disabled");


                        var companyName=$("#userStatus-3 input[name='company_name']").val();

                        var id='';
                        var contract="";
                        var groupName='';
                        var companyStatus=''

                        if(companyName){
                            $.ajax({
                                type: "GET", //用POST方式传输
                                dataType: "json", //数据格式:JSON
                                async: false,
                                url: '/company/get_consultant_company_list_by_name?type=1&company_name='+companyName,
                                data:{},
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    /*alert(XMLHttpRequest.status);
                                    alert(XMLHttpRequest.readyState);
                                    alert(textStatus);*/
                                    console.log("ajax请求失败:"+XMLHttpRequest,textStatus,errorThrown);
                                    $.alert("网络异常，请检查网络情况");
                                    me.removeClass("disabled");
                                },
                                success: function (result, status){
                                    var dataContent=result;
                                    var dataCon=$.toJSON(dataContent);
                                    var obj = eval( "(" + dataCon + ")" );//转换后的JSON对象
                                    //console.log("ajax请求成功:"+data.toString())
                                    //新消息获取成功
                                    if(obj["code"]==200){
                                        var company=obj.data;

                                        for(var i=0;i<company.length;i++){
                                            if(companyName==company[i].company_name){
                                                id=company[i].id;
                                                contract=company[i].contract;
                                                groupName=company[i].group_name;
                                                companyStatus=company[i].company_status;
                                                break;
                                            }
                                        }
                                        console.log("公司ID:"+id)
                                        if(id&& companyStatus!=0){
                                            $("#userStatus-3 input[name='company_name']").addClass("is-valid").removeClass("is-invalid")
                                            $("#userStatus-3 input[name='company_id']").val(id);
                                            $("#userStatus-3 [name='company_name_invalid']").empty();
                                            var data=self.serialize();
                                            $.ajax({
                                                url: "/account/bind_consultant_company",
                                                type: "POST",
                                                data:data,
                                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                    console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                                    me.removeClass("disabled");
                                                },
                                                success: function(result) {
                                                    if(result.status){
                                                        var pageId=$("input[name='pageId']").val();
                                                        if(pageId=='index'){
                                                            $("#userStatus-3").modal('hide')


                                                            var memberstatus=result.data.member_type;
                                                            if(memberstatus==1){
                                                                $("#userStatus-1 a[name='open-company']").remove()
                                                            }

                                                            var companyStatus=result.data.companyStatus;
                                                            if(companyStatus){
                                                                $("input[name='companyStatus']").val(companyStatus).trigger('change');
                                                            }
                                                            else{
                                                                window.location.href='/'
                                                            }
                                                        }
                                                        else{
                                                            window.location.reload()
                                                        }
                                                    }
                                                    else{
                                                        $.alert(result.msg);
                                                    }

                                                    me.removeClass("disabled");

                                                }
                                            })
                                        }
                                        else{
                                            $("#userStatus-3 input[name='company_name']").removeClass("is-valid").addClass("is-invalid")
                                            $("#userStatus-3 input[name='company_id']").val('')
                                            $("#userStatus-3 [name='company_name_invalid']").empty().text("猎企尚未入驻禾蛙，先邀请企业管理员入驻吧")
                                            me.removeClass("disabled");


                                        }

                                    }
                                    //新消息获取失败
                                    else{
                                        $.alert(result.msg)
                                        me.removeClass("disabled");
                                        $("#userStatus-3 input[name='company_name_invalid']").empty().text("请输入公司名称")
                                    }

                                }
                            });
                        }
                        else{
                            $("#userStatus-3 input[name='company_name']").removeClass("is-valid").addClass("is-invalid")
                            $("#userStatus-3 input[name='company_id']").val('')
                            $("#userStatus-3 input[name='company_name_invalid']").empty().text("请输入公司名称")
                            me.removeClass("disabled");
                        }




                    }




                    break;

            }

            $("#loading-modal").modal('hide')
            
        })

    }


    $("#userStatus-3 #search-company-name").on("change blur",function () {
        var companyName=$("#search-company-name").val();

        var id='';
        var contract="";
        var groupName='';
        var companyStatus=''

        if(companyName){
            $.ajax({
                type: "GET", //用POST方式传输
                dataType: "json", //数据格式:JSON
                async: false,
                url: '/company/get_consultant_company_list_by_name?type=1&company_name='+companyName,
                data:{},
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    /*alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);*/
                    console.log("ajax请求失败:"+XMLHttpRequest,textStatus,errorThrown);
                    $.alert("网络异常，请检查网络情况");
                },
                success: function (data, status){
                    var dataContent=data;
                    var dataCon=$.toJSON(dataContent);
                    var obj = eval( "(" + dataCon + ")" );//转换后的JSON对象
                    //console.log("ajax请求成功:"+data.toString())
                    //新消息获取成功
                    if(obj["code"]==200){
                        var company=obj.data;

                        for(var i=0;i<company.length;i++){
                            if(companyName==company[i].company_name){
                                id=company[i].id;
                                contract=company[i].contract;
                                groupName=company[i].group_name;
                                companyStatus=company[i].company_status;
                                break;
                            }
                        }

                        console.log("公司ID:"+id)
                        if(id&&companyStatus!=0){
                            $("#userStatus-3 input[name='company_name']").addClass("is-valid").removeClass("is-invalid")
                            $("#userStatus-3 input[name='company_id']").val(id);
                            $("#userStatus-3 [name='company_name_invalid']").empty();

                            console.log("公司信息存在："+companyName,id)
                        }
                        else{
                            $("#userStatus-3 input[name='company_name']").removeClass("is-valid").addClass("is-invalid")
                            $("#userStatus-3 input[name='company_id']").val('')
                            $("#userStatus-3 [name='company_name_invalid']").empty().text("猎企尚未入驻禾蛙，先邀请企业管理员入驻吧")
                            console.log("公司信息不存在："+companyName,id)
                        }


                    }
                    //新消息获取失败
                    else{
                        //$.alert(data.msg)
                    }

                }
            });
        }
        else{
            console.log("公司名不存在："+companyName)
            $("#userStatus-3 input[name='company_name']").removeClass("is-valid").addClass("is-invalid")
            $("#userStatus-3 input[name='company_id']").val('')
            $("#userStatus-3 [name='company_name_invalid']").empty().text("请输入公司名称")
        }


    })
})();

function maxTextInput(obj,max) {
    obj.value = obj.value.substring(0,max);
}




