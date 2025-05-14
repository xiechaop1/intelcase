(function(){
    //修改成输入密码
    $(".change-to-password").on("click",function () {
        $("form[name='accountRemember']").addClass("d-none");
        $("form[name='phone']").removeClass("d-none");

    })



    var loginStr = `
           <div class="p-20-40 relative h5 m-t-30" name="loginStr" style="width: 600px;">
                  
                     <div>
                                                      <!--有邀请码不显示start-->
                             <ul class="nav-style-4 nav nav-tabs fs-16 bottomLine d-flex m-auto">
                                 <li>
                                     <a class="d-block  py-3  fs-28  active" href="#phoneCode" data-toggle="tab">验证码登录</a>
                                 </li>
                                 <li>
                                     <a class="d-block  fs-28 py-3" href="#account" data-toggle="tab">密码登录</a>
                                 </li>
                             </ul>
                             <!--有邀请码不显示end-->
                             
                         <div class="tab-content m-t-30">
                             <div class="tab-pane fade show active" id="phoneCode">
                                 <form name="phoneCode">
                                    <div class="d-flex align-items-center m-t-10 bg-F5 border-radius-r-5 border-radius-l-5">
                                         <select class=" h-45 w-25 p-2 border-no bg-F5 border-radius-l-5 border-no-right border-radius-none inputIconDown" name="sections"><option value="+86">+86</option><option value="+44">+44</option><option value="+852">+852</option><option value="+853">+853</option><option value="+886">+886</option></select>
                                         <div class="w-80 h-45 p-2 d-flex align-items-center relative">
                                             <input name="phone" value="" class="w-80 ml-2" type="text" autocomplete="on" placeholder="请输入手机号">
                                             <div class="invalid-feedback">手机号错误</div>
                                         </div>
                                     </div>
                                     <div class="fs-24 text-99 m-t-20 text-center">
                                         未注册的手机号验证后自动创建禾蛙账号
                                     </div>


                                     <div class="d-flex justify-content-between m-t-40 align-items-center bg-F5 border-radius-r-5 border-radius-l-5">
                                         <div class="h-45 bg-ff  w-60  border-no-right border-radius-none d-flex relative align-items-center p-l-40">
                                             <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="输入验证码">
                                             <div class="invalid-feedback">请输入验证码</div>
                                         </div>
                                         <div class=" vcode-phone vcode h5-btn-green-big-3 m-r-10">发送验证码</div>
                                     </div>
                                     <div class="text-center m-t-40">
                                         <div class="submit btn btn-danger w-100  h-45 border-radius-30 fs-34" style="line-height: 70px;">登录</div>
                                     </div>
                                     <div class="m-t-40 m-b-40 fs-22 text-center text-F6 m-b-10 d-none">
                                        注：已绑定猎企的用户登录后可直接解锁马赛克，未绑定猎企用户需登录后先进行猎企绑定/注册
                                     </div>
                                     <div class="m-t-20 fs-24 text-center text-99 m-b-10">
                                              登录即代表你已同意
                                              <a href="http://file.hewa.cn/hewa_test//template/20200608/83bdc6ed82586fcbb6388658d2894f55.pdf" target="_blank"><span class="text-F6">《用户服务协议》</span></a>
                                          </div>
                                 </form>
                             </div>

                             <div class="tab-pane fade " id="account">
                                 <form name="phone" method="get">
                                     <div class="d-flex align-items-center m-t-10 bg-F5 border-radius-r-5 border-radius-l-5">
                                         <select class=" h-45 w-25 p-2 border-no bg-F5 border-radius-l-5 border-no-right border-radius-none inputIconDown" name="sections"><option value="+86">+86</option><option value="+44">+44</option><option value="+852">+852</option><option value="+853">+853</option><option value="+886">+886</option></select>
                                         <div class="w-80 h-45 p-2 d-flex align-items-center relative">
                                             <input name="phone" value="" class="w-80 ml-2" type="text" autocomplete="on" placeholder="请输入手机号">
                                             <div class="invalid-feedback">手机号错误</div>
                                         </div>
                                     </div>
                                     <div class="d-flex justify-content-between m-t-40 align-items-center bg-F5 border-radius-r-5 border-radius-l-5">
                                         <div class="h-45 bg-ff  w-100  border-no-right border-radius-none d-flex relative align-items-center p-l-40">
                                             <input class="w-100" name="password" type="password" placeholder="请输入密码">
                                             <div class="invalid-feedback">请输入密码</div>
                                         </div>
                                       
                                     </div>
                                     <div class="text-center m-t-40">
                                         <div class="submit btn btn-danger w-100  h-45 border-radius-30 fs-34" style="line-height: 70px;">登录</div>
                                     </div>
                                     <div class="m-t-40 m-b-40 fs-22 text-center text-F6 m-b-10 d-none">
                                        注：已绑定猎企的用户登录后可直接解锁马赛克，未绑定猎企用户需登录后先进行猎企绑定/注册
                                     </div>
                                     <div class="m-t-20 fs-24 text-center text-99 m-b-10">
                                              登录即代表你已同意
                                              <a href="http://file.hewa.cn/hewa_test//template/20200608/83bdc6ed82586fcbb6388658d2894f55.pdf" target="_blank"><span class="text-F6">《用户服务协议》</span></a>
                                          </div>
                                 </form>
                           
                             </div>
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
                               <span class="close delete-note  m-t-15 m-r-20  fs-24 absolute  z-9999 iconfont iconbtn-guanbi" data-dismiss="modal" style="top: 15px;right: 15px;"></span>
                                ${loginStr}
                            </div>
                        </div>
                    </div>`


        $('body').append(final)
        bindEvent()
        $.bindCheck()
    }
    function hideAll(){
        $('div[name="loginStr"],div[name="registStr"],div[name="registPhone"],div[name="registEmail"],div[name="findPassWord"]').each(function(ind,ele){
            $(ele).hasClass('d-none') ? '':$(ele).addClass('d-none')
        })
    }

    function  reloadPage(company) {
        console.log(company)
        if(company==3){
            $("#login").modal('hide');
            $("#h5-login-success").modal({show:true,backdrop: 'static', keyboard: false});

        }else{
            window.location.reload();
        }

        /*setTimeout(function () {
            window.location.reload()
        },5000)*/
    }

    function bindEvent(){
        $('.registBtn').on('click',function(){
            render('registStr')
        })
        $('.loginBtn').on('click',function(){

            $("#login").modal('show')
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
        $('.submit').on('click',submitHandle)
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

    //日期时间处理
    function conver(s) {
        return s < 10 ? '0' + s : s;
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
            if(!target_url||target_url.split("passport/web_register").length>1){
                target_url='/';
            }
            var source=$("[name='source']").val();

            switch(name){
                case "phoneCode" :
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
                                email:'',
                                verificationCode: self.find("input[name='vcode']").val(),
                                rememberPhone:self.find("input[name='rememberPhone']").is(':checked'),
                                source:source,
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                                me.removeClass("disabled");
                            },
                            success: function(result) {
                                if(result.status){
                                    var consultant_company_status=result.data.consultant_company_status;
                                    // alert(company_statue)

                                    if(result.status&&result.data.type==1&&result.data.member_status==10){// window.location.href='/passport/web_register?type=1&member_status=10&id='+result.data.id

                                        reloadPage(consultant_company_status)
                                    }
                                    else if(result.status&&result.data.type==1&&result.data.member_status==0){
                                        reloadPage(consultant_company_status)
                                    }
                                    else if(result.status&&result.data.type==10&&result.data.member_status==10){
                                        reloadPage(consultant_company_status)
                                    }
                                    else if(result.status&&result.data.type==10&&result.data.member_status==0){
                                        window.location.href='/passport/web_register?type=2&member_status=0&id='+result.data.id;
                                    }
                                    else if(result.status){
                                        reloadPage(consultant_company_status)
                                    }
                                }
                                else{
                                    $.alert(result.msg)
                                }

                                me.removeClass("disabled");


                            }
                        })

                    }

                    break;
                case 'phone' :
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
                                if(result.status){
                                    var consultant_company_status=result.data.consultant_company_status;
                                    //alert(company_statue)
                                    if(result.status&&result.data.type==1&&result.data.member_status==10){
                                        reloadPage(consultant_company_status)
                                    }
                                    else if(result.status&&result.data.type==1&&result.data.member_status==0){
                                        reloadPage(consultant_company_status)
                                    }
                                    else if(result.status&&result.data.type==10&&result.data.member_status==10){
                                        reloadPage(consultant_company_status)
                                    }
                                    else if(result.status&&result.data.type==10&&result.data.member_status==0){
                                        window.location.href='/passport/web_register?type=2&member_status=0&id='+result.data.id;
                                    }
                                    else if(result.status){
                                        reloadPage(consultant_company_status)
                                    }
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
                                                    company_position_province:self.find("input[name='company_position[provinceOfChina]']").val(),
                                                    company_position_city:self.find("input[name='company_position[cityOfChina]']").val(),
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
                                url: '/company/get_consultant_company_list_by_name?company_name='+companyName,
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
                                        if(id){
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

        })

    }

})();




