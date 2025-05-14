$(function(){
    var company_status=parseInt($("input[name='companyStatus']").val());
    // const STATUS_CON_COMPANY_NORMAL         = 0;
    // const STATUS_CON_COMPANY_WAIT_PLATEFORM = 1;
    // const STATUS_CON_COMPANY_WAIT_ADMIN     = 2;
    var member_type=parseInt($("input[name='member_type']").val());
    //memver_type=10是管理员
    var login=parseInt($("input[name='login']").val());
    var enterCt=$("input[name='enterCt']").val();
    var member_created_at=$("input[name='member_created_at']").val();//注册时间搓
    var created_limit=Date.parse('1900-08-21 00:00:00')/1000;
    var invite_total=$("input[name='invite_total']").val();
    var headhunter_total=$("input[name='headhunter_total']").val();
    var member_type=parseInt($("input[name='member_type']").val());
    var invite_msgbox=$("input[name='invite_total']").attr('msgbox');
    console.log(invite_total,headhunter_total,invite_total>=headhunter_total)
    invite_total>=headhunter_total?$(".invite-right-btn").attr('data-id',invite_total): $(".invite-right-btn").attr('data-id',headhunter_total);

    console.log(created_limit,created_limit<member_created_at,parseInt(created_limit),parseInt(member_created_at));
    //右侧邀请按钮显示
    if(member_type==10&&company_status==0&&created_limit<member_created_at){
        if(invite_total==1){
            $(".invite-right-btn").removeClass('d-none');
        }
        else{
            $(".invite-right-btn").removeClass('d-none');
            $(".invite-right-btn .show-invite-modal").addClass('d-none');
        }

    }

    //如果已邀请0或1人循环邀请结果
    if((invite_total==0||invite_total==1)&&member_type==10&&company_status==0&&created_limit<member_created_at){

        var time = setInterval(function(){
            var old_ct=parseInt($("input[name='invite_total']").val());
            $.ajax({
                type: "GET", //用POST方式传输
                dataType: "json", //数据格式:JSON
                //url: '/account/get_invite_code?invite_code='+invite_code,
                url:'/account/get_invite_code_by_user',
                data:{},
                success: function (result, status){
                    if(result.code==200){
                        var user_ct=result.data.total;
                        console.log("user_ct:"+user_ct)
                        if(user_ct>old_ct){
                            $("input[name='invite_total']").val(user_ct)
                            if(user_ct==1){
                            $.Toast("您成功邀请了一位好友注册", "快去继续邀请吧！", "success", {
                                stack: true,
                                position_class: "toast-top-center",
                                has_icon:true,
                                has_close_btn:false,
                                fullscreen:false,
                                timeout:2000,
                                sticky:false,
                                has_progress:true,
                                rtl:false,
                            });

                            }


                            user_ct>=headhunter_total?invite_num=user_ct:  invite_num=headhunter_total;

                            $(".invite-right-btn").removeClass('d-none').attr('data-id',invite_num);

                            switch (user_ct){
                                case 0:
                                    if(enterCt==0){
                                        $("#invite-step-0").removeClass('d-none');
                                        $("#invite-step-1,#invite-step-2,#invite-step-3").addClass('d-none');
                                        $(".invite-right-btn .show-invite-modal").addClass('d-none');
                                    }else{
                                        $("#invite-step-1").removeClass('d-none');
                                        $("#invite-step-0,#invite-step-2,#invite-step-3").addClass('d-none');
                                        $(".invite-right-btn .show-invite-modal").addClass('d-none');
                                    }

                                    // $("#invite-step-modal").modal('show');
                                    console.log("用户状态："+user_ct)
                                    break;
                                case 1:
                                    $("#invite-step-2").removeClass('d-none');
                                    $("#invite-step-0,#invite-step-1,#invite-step-3").addClass('d-none');
                                    $(".invite-right-btn .show-invite-modal").removeClass('d-none');
                                    //$("#invite-step-modal").modal('show');
                                    console.log("用户状态："+user_ct)
                                    break;
                                case 2:
                                    $("#invite-step-modal").modal('hide');
                                    $("#invite-step-success").modal('show');
                                    $(".invite-right-btn .show-invite-modal").addClass('d-none');
                                    clearInterval(time);
                                    console.log("用户状态："+user_ct)
                                    break;
                            }
                            if(user_ct>2){
                                clearInterval(time);
                                $("#invite-step-modal").modal('hide');
                                $("#invite-step-success").modal('show');
                                $(".invite-right-btn .show-invite-modal").addClass('d-none');
                                console.log("用户状态："+user_ct)
                            }
                        }
                    }
                    //新消息获取失败
                    else{
                        console.log(result.msg)
                    }

                },
                error : function(res){
                    console.log('请检查网络')
                }
            })
        },30000)
    }


    //判断个人信息是否完善,邀请人数大于等于2判断信息完善情况；
    var userInfo=parseInt($("input[name='userInfo']").val());

    //主动弹出邀请好友提示
    if(company_status==0&&member_type==10&&login==0&&created_limit<member_created_at){
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
                    var invite_link=window.location.protocol+'//'+window.location.host+'/passport/web_login?invite_code='+invite_code;
                    var invite_num=result.data.invite_code.total;
                    $("input[name='invite_code']").val(invite_code);
                    $("input[name='invite_ct']").val(invite_num);
                    $(".btn-paste").attr('data-clipboard-text',invite_link);
                    $(".invite-link").empty().text(invite_link);
                    if(headhunter_total<2){
                        if(invite_num>=0){

                            invite_num>=headhunter_total?'':invite_num=headhunter_total;
                            $(".invite-right-btn").removeClass('d-none').attr('data-id',invite_num);
                            switch (invite_num){
                                case 0:
                                    if(enterCt==0){
                                        $("#invite-step-0").removeClass('d-none');
                                        $("#invite-step-1,#invite-step-2,#invite-step-3").addClass('d-none');

                                        $("#invite-step-modal").modal('show');
                                    }else{
                                        $("#invite-step-1").removeClass('d-none');
                                        $("#invite-step-0,#invite-step-2,#invite-step-3").addClass('d-none');

                                        $("#invite-step-modal").modal('show');
                                    }

                                    console.log("用户状态：" + invite_num);
                                    break;
                                case 1:
                                    $("#invite-step-2").removeClass('d-none');
                                    $("#invite-step-0,#invite-step-1,#invite-step-3").addClass('d-none');

                                    $("#invite-step-modal").modal('show');
                                    $(".invite-right-btn .show-invite-modal").removeClass('d-none')
                                    console.log("用户状态："+invite_num);
                                    break;
                            }
                            if(invite_num>=2){
                                if(invite_msgbox==0){
                                    $("#invite-step-modal").modal('hide');
                                    $("#invite-step-success").modal('show');
                                    $(".invite-right-btn .show-invite-modal").addClass('d-none');
                                }
                                else{
                                    switch (userInfo){
                                        case 1:
                                            $("#fillUserInfo").modal({
                                                show:true
                                            })
                                            console.log("用户状态："+userInfo)
                                            break;
                                        default:
                                            console.log("用户状态："+userInfo)
                                    }
                                }


                                console.log("用户状态："+invite_num)
                            }


                        }
                        else{
                           // alert(invite_num)
                        }
                    }
                }
                //新消息获取失败
                else{
                    $.alert(result.msg)
                }
            }
        });
    }
    else if(company_status==0&&member_type!=10&&headhunter_total<2&&login==0&&created_limit<member_created_at){
        switch (invite_total){
            case 0:
                var time = setInterval(function(){
                    $.ajax({
                        type: "GET", //用POST方式传输
                        dataType: "json", //数据格式:JSON
                        //url: '/account/get_invite_code?invite_code='+invite_code,
                        url:'/account/get_invite_code_by_user',
                        data:{},
                        success: function (result, status){
                            if(result.code==200){
                                var user_ct=result.data.total;
                                if(user_ct==1){
                                    $("#invite-person-not-enough").modal('show');
                                    clearInterval(time);
                                }
                                else if(user_ct>1){
                                    $.alert('绑定猎企邀请人数已达2人')
                                }

                            }
                            //新消息获取失败
                            else{
                                console.log(result.msg)
                            }

                        },
                        error : function(res){
                            console.log('请检查网络')
                        }
                    })
                },10000)
                break;
            case 2:
                switch (userInfo){
                    case 1:
                        $("#fillUserInfo").modal({
                            show:true
                        })
                        break;
                    default:
                        console.log("用户状态："+userInfo)
                }
                break;
            case 1:
                if(invite_msgbox==0){
                    $("#invite-person-not-enough").modal('show');
                }
                break;
        }
        if(invite_total>2){
            switch (userInfo){
                case 1:
                    $("#fillUserInfo").modal({
                        show:true
                    })
                    break;
                default:
                    console.log("用户状态："+userInfo)
                    break;
            }
        }

    }
    else{
        switch (userInfo){
            case 1:
                $("#fillUserInfo").modal({
                    show:true
                })
                console.log("用户状态："+userInfo)
                break;
            default:
                console.log("用户状态："+userInfo)
        }
    }

    //刷牙邀请链接
    $(".btn-refresh-invite-link").on('click',function () {
        refreshInviteLink()
    })

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


    //邀请更多
    $(".invite-more-user").on('click',function () {
        refreshInviteLink();
        $("#invite-step-3").removeClass('d-none');
        $("#invite-step-2,#invite-step-1").addClass('d-none');
        $("#invite-step-modal").modal('show');
        $("#invite-step-success").modal('hide');
    })

    //展示邀请弹框
    $(".invite-right-btn").on('click',function () {
        var me=$(this);
        var dataId=me.attr('data-id');
        console.log(dataId);
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

        
        switch (dataId){
            case '0':
                $("#invite-step-1").removeClass('d-none');
                $("#invite-step-0,#invite-step-2,#invite-step-3").addClass('d-none');
                $("#invite-step-modal").modal('show');
                break;
            case '1':
                $("#invite-step-2").removeClass('d-none');
                $("#invite-step-0,#invite-step-1,#invite-step-3").addClass('d-none');
                $("#invite-step-modal").modal('show');
                break;
            case '2':
                $("#invite-step-3").removeClass('d-none');
                $("#invite-step-0,#invite-step-1,#invite-step-2").addClass('d-none');
                $("#invite-step-modal").modal('show');
                break;
        }
        if(dataId>2){
            $("#invite-step-3").removeClass('d-none');
            $("#invite-step-0,#invite-step-1,#invite-step-2").addClass('d-none');
            $("#invite-step-modal").modal('show');
        }

    })

})