$(function(){
    var company_status=parseInt($("input[name='companyStatus']").val());
    // const STATUS_CON_COMPANY_NORMAL         = 0;
    // const STATUS_CON_COMPANY_WAIT_PLATEFORM = 1;
    // const STATUS_CON_COMPANY_WAIT_ADMIN     = 2;
    var member_type=parseInt($("input[name='member_type']").val());
    //memver_type=10是管理员
    var member_created_at=$("input[name='member_created_at']").val();//注册时间搓
    var created_limit=Date.parse('2020-08-21 00:00:00')/1000;
    var invite_total=parseInt($("input[name='invite_total']").val());
    var headhunter_total=parseInt($("input[name='headhunter_total']").val());
    var member_type=parseInt($("input[name='member_type']").val());
    var invite_msgbox=$("input[name='invite_total']").attr('msgbox');
    invite_total>=headhunter_total? $(".invite-right-btn").attr('data-id',invite_total): $(".invite-right-btn").attr('data-id',headhunter_total);

    //右侧邀请按钮显示
    if(member_type==10&&company_status==0&&created_limit<member_created_at){
        if(invite_total==1){
            $(".invite-right-btn").removeClass('d-none').attr('data-id',invite_total);
        }
        else{
            $(".invite-right-btn").removeClass('d-none').attr('data-id',invite_total);
            $(".invite-right-btn .show-invite-modal").addClass('d-none')
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