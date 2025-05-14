$(function () {
    //设置显示最小高度
    var winH=parseInt($(window).height());
    var h=winH-38;//减去header和Footer的高度
    $(".page-content-box").css("min-height",h);

    $(window).resize(function() { //当浏览器大小变化时
        var winH=parseInt($(window).height());
        var h=winH-38;//减去header和Footer的高度
        h>500?'':h=500;
        $(".page-content-box").css("min-height",h);
    });


    //判断猎企状态
    var userStatusControlModalContent2 = function userStatusControlModalContent2(){
        var status=parseInt($("input[name='companyStatus']").val());
        // const STATUS_CON_COMPANY_NORMAL         = 0;
        // const STATUS_CON_COMPANY_WAIT_PLATEFORM = 1;
        // const STATUS_CON_COMPANY_WAIT_ADMIN     = 2;

        switch (status){
            case 1:
                $("#userStatus-1").modal({
                    show:true
                })
                console.log("用户状态："+status)
                break;
            case 2:
                $("#userStatus-2").modal({
                    show:true
                })
                console.log("用户状态："+status)
                break;
            case 3:
                $("#userStatus-3 #search-company-name").val('').removeClass('is-invalid');

                $("#userStatus-3").modal({
                    show:true
                })
                console.log("用户状态："+status)
                break;
            default:
                console.log("用户状态："+status)
                break;
        }
    }
    //判断猎企状态
    var userStatusControlModalContent3 = function userStatusControlModalContent3(){
        var me=$(this);
        var dataId=me.attr('data-id');

        var status=parseInt($("input[name='companyStatus']").val());
        // const STATUS_CON_COMPANY_NORMAL         = 0;
        // const STATUS_CON_COMPANY_WAIT_PLATEFORM = 1;
        // const STATUS_CON_COMPANY_WAIT_ADMIN     = 2;

        switch (status){
            case 1:
            case 2:
                $.ajax({
                    type: "GET", //用POST方式传输
                    dataType: "json", //数据格式:JSON
                    url:'/fav/add?job_id='+dataId,
                    data:{},
                    success: function (result, status){
                        if(result.code==200){
                            $("#favorite-company-under-review").modal({
                                show:true
                            });
                            $(".favorite[data-type='4']").addClass('favorite-select');
                            $(".favorite[data-type='4'] img").attr('src','../../static/image/h5/job/like-hover.png');
                            $(".favorite[data-type='4'] span").empty().text('已收藏').addClass('favorite-select');
                        }
                        //新消息获取失败
                        else{
                            $.alert(result.msg);
                        }
                        me.removeClass('disable');

                    },
                    error : function(res){
                        $.alert('请检查网络');
                        me.removeClass('disable');
                    }
                });
                console.log("用户状态："+status)
                break;
            case 3:
                $.ajax({
                    type: "GET", //用POST方式传输
                    dataType: "json", //数据格式:JSON
                    url:'/fav/add?job_id='+dataId,
                    data:{},
                    success: function (result, status){
                        if(result.code==200){
                            $("#favorite-company-unbind").modal({
                                show:true
                            });
                            $(".favorite[data-type='4']").addClass('favorite-select');
                            $(".favorite[data-type='4'] img").attr('src','../../static/image/h5/job/like-hover.png');
                            $(".favorite[data-type='4'] span").empty().text('已收藏').addClass('favorite-select');


                        }
                        //新消息获取失败
                        else{
                            $.alert(result.msg);
                        }
                        me.removeClass('disable');

                    },
                    error : function(res){
                        $.alert('请检查网络');
                        me.removeClass('disable');
                    }
                });



                console.log("用户状态："+status)
                break;
            default:
                console.log("用户状态："+status)
                break;
        }
    }

    //判断邀请状态
    var userInviteStatusControlModalContent = function userInviteStatusControlModalContent(){

        var invite_total=parseInt($("input[name='invite_total']").val());
        var headhunter_total=parseInt($("input[name='headhunter_total']").val());
        invite_total>headhunter_total?'':invite_total=headhunter_total;
        var member_type=parseInt($("input[name='member_type']").val());
        refreshInviteLink()

        if(member_type==10){
            switch (invite_total){
                case 0:
                    $("#invite-step-1").removeClass('d-none');
                    $("#invite-step-0,#invite-step-2,#invite-step-3").addClass('d-none');
                    $("#invite-step-modal").modal('show');
                   // $(".invite-right-btn").addClass('d-none')
                    break;
                case 1:
                    $("#invite-step-2").removeClass('d-none');
                    $("#invite-step-0,#invite-step-1,#invite-step-3").addClass('d-none');
                    $("#invite-step-modal").modal('show');
                   // $(".invite-right-btn").removeClass('d-none')
                    break;
            }
        }
        else{
            switch (headhunter_total){
                case 1:
                    $("#invite-person-not-enough").modal('show');
                    break;
                default:
                    $.alert("绑定猎企需至少邀请2名猎头");
                    break;
            }
        }
    }


    var status=parseInt($("input[name='companyStatus']").val());
    var invite_total=parseInt($("input[name='invite_total']").val());
    var headhunter_total=parseInt($("input[name='headhunter_total']").val());
    var member_type=parseInt($("input[name='member_type']").val());
    var member_created_at=$("input[name='member_created_at']").val();//注册时间搓
    var created_limit=Date.parse('1900-08-21 00:00:00')/1000;

    console.log("用户状态："+status)

    if(status!=0){
        if(status!=0){
            //PC 职位详情处理
            $("#login-unbind-company").removeClass('d-none');
            $(".company-unbind-vague2").attr("href",'javascript:void(0);').attr("target",'');
            $(".company-unbind-vague").addClass("text-vague-66").attr("href",'javascript:void(0);').attr('data-toggle','').attr("data-original-title",'').attr("target",'').attr('title','');
            $(".company-unbind-vague").each(function(){
                var text=$(this).text().trim().replace(/./g,"*X");
                $(this).text(text)
            });



                $(".btn-tab-change[href='#job-detail-2']").addClass('d-none');
            $("#job-detail-tab-box .tab-content .tab-pane").removeClass("active");
            $("#job-detail-1").addClass("active show");
            $(".company-info-l-box").addClass('d-none');

        }
        //header的发单
        $("a.userStatusControlBtn,a.userInviteControlBtn").attr("href",'javascript:void(0);').removeAttr("target").unbind().click(userStatusControlModalContent2);

        $("a.userInviteControlPickBtn").attr("href",'javascript:void(0);').removeAttr("target").unbind().click(userStatusControlModalContent3);
        $(".loginBtn").unbind().on("click",function () {
            $("#login").modal('show');
        })

    }
    else if(created_limit<member_created_at){
        if(invite_total<2&&headhunter_total<2){
            $("a.userInviteControlBtn").attr("href",'javascript:void(0);').removeAttr("target").unbind().click(userInviteStatusControlModalContent);
        }
    }

    function refreshInviteLink(){

        //获取邀请码
        $.ajax({
            type: "GET", //用POST方式传输
            dataType: "json", //数据格式:JSON
            url: '/account/create_invite_code',
            data:{},
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                /*alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);*/
                console.log("/account/create_invite_code: error");
            },
            success: function (result, status){
                if(result.code==200){
                    var invite_code=result.data.new_code.invite_code;
                    var invite_link=window.location.protocol+'//'+window.location.host+'/passport/web_login?invite_code='+invite_code
                    $("input[name='invite_code']").val(invite_code);
                    $("input[name='invite_ct']").val(result.data.invite_code.total);
                    $(".btn-paste").attr('data-clipboard-text',invite_link);
                    $(".invite-link").empty().text(invite_link);
                }
                //新消息获取失败
                else{
                    $.alert(result.msg)
                }
            }
        });

    }

    // <div class="d-flex align-items-center justify-content-around mt-2">
    //     <div class="medium mr-2 w-30">
    //         国际机票
    //     </div>
    //     <div class="btn btn-danger medium mr-2 fs-14 contact" data-tooken="1">联系客服</div>
    // </div>

    //分类下拉
/*
<div class="d-flex align-items-center justify-content-around mt-2">
        <div class="medium mr-2 w-30">
        客服
        </div>
        <div class="btn btn-danger medium mr-2 fs-14 contact" data-tooken="0">联系客服</div>
        </div>
        <div class="d-flex align-items-center justify-content-around mt-2">
        <div class="medium mr-2 w-80">
        <div class="fs-14 text-66">英国时间 9:00AM - 17:00PM </div>
    </div>
    </div>

    <div class="d-flex align-items-center justify-content-around my-2">
        <div class="medium mr-2 w-80">
        <div class="fs-14 text-66">中国时间 早上9点 - 下午5点 </div>
    </div>
    </div>*/
    var contactConfig = {
        trigger: 'click',
        html: true,
        placement : 'bottom',
        content : `
            <div class="text-EB fs-14">
               
                
                <div class="d-flex align-items-center justify-content-around mt-2 my-2">
                    <div class="medium mr-2 w-60" style="line-height: 30px;">
                       英国时间 : 9:00AM - 17:00PM<br>
                        中国时间 : 早上9点 - 下午5点
                    </div>
                    <div class="btn btn-danger medium mr-2 fs-14 contact" data-tooken="0">联系客服</div>
                </div>
                
            </div>
        `,
        template :  '<div class="popover  border-F6 moreWidth kindSelect border-0 bg-FB" role="tooltip" style="width: 400px;max-width: 400px;"><h3 class="popover-header"></h3><div class="popover-body"></div></div>'

    }
    /*var contactConfig = {
        trigger: 'click',
        html: true,
        placement : 'bottom',
        content : `
            <div class="text-EB fs-14">
                <div class="d-flex align-items-center justify-content-around mt-2">
                    <div class="medium mr-2 w-30">
                        机场接送
                    </div>
                    <div class="btn btn-danger medium mr-2 fs-14 contact" data-tooken="0">联系客服</div>
                </div>
                <div class="d-flex align-items-center justify-content-around mt-2">
                    <div class="medium mr-2 w-30">
                        高定旅游
                    </div>
                    <div class="btn btn-danger medium mr-2 fs-14 contact" data-tooken="2">联系客服</div>
                </div>
                <div class="d-flex align-items-center justify-content-around my-2">
                    <div class="medium mr-2 w-30">
                        婚纱摄影
                    </div>
                    <div class="btn btn-danger medium mr-2 fs-14 contact" data-tooken="3">联系客服</div>
                </div>
            </div>
        `, 
        template :  '<div class="popover w-20 border-F6 moreWidth kindSelect border-0 bg-FB" role="tooltip"><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    
    }*/

    //开启下拉 并且mouseover不会消失
    $('header .login >div >a:nth-of-type(1).online').popover(contactConfig)
    .on("mouseenter", function () {
        var _this = this;   // 这里的this触发的dom,需要存起来 否则在下面 .popover的逻辑中this会变为弹出的dom
        $(this).popover("show");
        bindEvent()
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide'); 
        });
    })
    .on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 200);
    });

    var wechatConfig = {
        trigger: 'click',
        html: true, 
        placement : 'bottom',
        content : `
            <div class="m-2">
                <img class="qrcode" src="/static/img/qrcode_wx.png">
                <div class="text-center my-3">
                    扫一扫 关注我们
                </div>
            </div>
        `, 
        template :  '<div class="popover border-F6 moreWidth kindSelect border-0 bg-FB" role="tooltip"><h3 class="popover-header"></h3><div class="popover-body" ></div></div>'
    
    }
    $('header .login >.d-flex:nth-of-type(2) >a:nth-of-type(2):not(.logout)').popover(wechatConfig)
    .on("mouseenter", function () {
        var _this = this;   // 这里的this触发的dom,需要存起来 否则在下面 .popover的逻辑中this会变为弹出的dom
        changeWeather();
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide'); 
        });
    })
    .on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 200);
    });




    $('header .logout').on('click',function(){
        $.ajax({
            url: "/passport/logout",
            type: "GET",
            success: function(result) {
                window.location.pathname == '/account/information' ? window.location.href='/':window.location.reload()
            },
            error(xhr,status,error){
                alert('退出失败')
            }
            
        })
    })

    //搜索
    $('.search-F6,.search-FF').on('click',function(){
        $('#searchModal').removeClass('d-none')
        $('body').addClass('overflow-hidden')
        tip()
    })
    $('#searchModal [data-dismiss="modal"]').on('click',function(){
        $('#searchModal').addClass('d-none')
        $('body').removeClass('overflow-hidden')
    })
    tip()
    function tip() {
        //文字展示全
        $('div[data-toggle="tooltip"]:not([tip="tip"])').each(function(ind,ele){
            if($(ele).find('span').width()>$(ele).innerWidth()){
                var container = ''
                $(ele).parents('#searchModal').length > 0 ? container = $('#searchModal')[0] : container = $('body')[0]
                $(ele).tooltip({
                    container : container,
                    template : '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                    delay: { "show": 200, "hide": 200 }
                })
            }
        })

        //tip贴示
        $('div[data-toggle="tooltip"][tip="tip"]').each(function(ind,ele){
            var container = ''
            $(ele).parents('#searchModal').length > 0 ? container = $('#searchModal')[0] : container = $('body')[0]
            $(ele).tooltip({
                container : container,
                html : true,
                template : '<div class="tooltip" role="tooltip"><div class="tooltip-inner bg-FFF6F3 text-33 text-left p-3"></div></div>',
                delay: { "show": 200, "hide": 200 },
                placement : 'bottom'
            })
        })
    }
    $.extend({
        bindTip : tip
    });


/*    (function(m, ei, q, i, a, j, s) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        j = ei.createElement(q),
            s = ei.getElementsByTagName(q)[0];
        j.async = true;
        j.charset = 'UTF-8';
        j.src = 'https://static.meiqia.com/dist/meiqia.js?_=t';
        s.parentNode.insertBefore(j, s);
    })(window, document, 'script', '_MEIQIA');
    _MEIQIA('entId', '142672');
    _MEIQIA('fallback', 1);
    window.uniqueid ? _MEIQIA('clientId', uniqueid) : ''
    // 在这里开启手动模式（必须紧跟美洽的嵌入代码）
    _MEIQIA('manualInit');
    
    // _MEIQIA('withoutBtn');
    _MEIQIA('init');
    _MEIQIA('allSet', function(online){
        online ? window.online = true : window.online = false;
        console.log(online)
        bindEvent()
        changeWeather()
    })*/



    function bindEvent(){
        $('.contact').on('click',function(e){
            //接送
            // ce2858ecff8b2b67ba593a8f96df2364
            //机票
            // 1f4519b0c113293ae991b261b9eb7783
            //旅行
            // e84a38b6e3e9c855460618a4b2d6e581
            //婚纱
            // 422eb2da65d966570bf29fb83e8c9688
            var arr =[
                // '1f4519b0c113293ae991b261b9eb7783',
                'ce2858ecff8b2b67ba593a8f96df2364',
                'e84a38b6e3e9c855460618a4b2d6e581',
                '422eb2da65d966570bf29fb83e8c9688'
            ]
            
            var tooken = arr[parseInt($(e.target).attr('data-tooken'))]

            _MEIQIA('assign', {
                agentToken: tooken
            });
            _MEIQIA('metadata', {
                'source': $(e.target).prev().html()
            });
            _MEIQIA('showPanel') 
        })
    }
    function changeWeather() {
        $('.btn-change-weahter').on('click',function(e){
            var city="北京";
            var content='晴  20° / 31°'
            var weather='<span class="m-r-20">'+city+'当地天气</span><span>'+content +'</span>'
            $(".weather-text").empty().html(weather);

        })
    }
});



function toBase64(data,onlyP=false){
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
    var data_clone = $.extend(true,{},data) 
    data_clone.p = Base64.encode(JSON.stringify(data_clone.p))
    if(onlyP){
        return data_clone.p
    }
    var q = '';
    for (var key in data_clone) {
        if (q != "") {
            q += "&";
        }
        q += key + "=" + encodeURIComponent(data_clone[key]);
    }
    return q
}
