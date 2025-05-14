$(function () {


    /*收藏类型说明：
    1：h5列表页收藏功能；
    2：h5职位详情页收藏功能，关联页面内type=3收藏；
    3：h5职位详情页收藏功能，关联页面内type=2收藏；
    4：pc 职位详情页收藏
    5：pc 已登录首页
    6：pc 推荐职位收藏
    */
    $(".favorite").on('click',function () {
        var me=$(this);
        var dataId=me.attr('data-id');
        var dataType=me.attr('data-type');
       // console.log(dataType,dataType==1)

        if(!me.hasClass('disable')&&!me.hasClass('favorite-select')){
            $.ajax({
                type: "GET", //用POST方式传输
                dataType: "json", //数据格式:JSON
                url:'/fav/add?job_id='+dataId,
                data:{},
                success: function (result, status){
                    if(result.code==200){
                        me.addClass('favorite-select');
                        switch (dataType){
                            case '1':
                                me.attr('src','../../static/image/h5/job/like-hover.png');
                                break;
                            case '2':
                                me.empty().text('已收藏');
                                $(".favorite[data-type='3']").attr('src','../../static/image/h5/job/like-hover.png').addClass('favorite-select');
                                $("#h5-like-success").modal('show');
                                break;
                            case '3':
                                me.attr('src','../../static/image/h5/job/like-hover.png');
                                $(".favorite[data-type='2']").empty().text('已收藏').addClass('favorite-select');
                                break;
                            case '4':
                                $(".favorite[data-type='4']").addClass('favorite-select');
                                $(".favorite[data-type='4'] img").attr('src','../../static/image/h5/job/like-hover.png');
                                $(".favorite[data-type='4'] span").empty().text('已收藏').addClass('favorite-select');
                                var company_status=parseInt($("input[name='companyStatus']").val());
                                
                                break;
                            case '5':
                                me.addClass('favorite-select');
                                me.find("img").attr('src','../../static/image/h5/job/like-hover.png');
                                me.find("span").empty().text('已收藏');
                                var like=`<img class="job-list-icon-like" src="../../static/image/h5/job/like-hover.png">`;
                                me.closest('.list-bottom').find(".job-list-icon-like").remove();
                                me.closest('.list-bottom').append(like);
                                break;
                            case '6':
                                me.addClass('favorite-select');
                                me.find("img").attr('src','../../static/image/h5/job/like-hover.png');
                                me.find("span").empty().text('已收藏');
                                var like=`<img class="job-list-icon-like2" src="../../static/image/h5/job/like-hover.png">`;
                                me.closest('.d-flex').find(".job-list-icon-like2").remove();
                                me.closest('.d-flex').append(like);
                                break;
                            default:
                                break;
                        }
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
        }
        else if(!me.hasClass('disable')&&me.hasClass('favorite-select')){
            $.ajax({
                type: "GET", //用POST方式传输
                dataType: "json", //数据格式:JSON
                url:'/fav/remove?job_id='+dataId,
                data:{},
                success: function (result, status){
                    if(result.code==200){
                        me.removeClass('favorite-select')
                        switch (dataType){
                            case '1':
                                me.attr('src','../../static/image/h5/job/like.png');
                                break;
                            case '2':
                                me.empty().text('收藏');
                                $(".favorite[data-type='3']").attr('src','../../static/image/h5/job/like.png').removeClass('favorite-select');
                                break;
                            case '3':
                                me.attr('src','../../static/image/h5/job/like.png');
                                $(".favorite[data-type='2']").empty().text('收藏').removeClass('favorite-select');
                                break;
                            case '4':
                                $(".favorite[data-type='4']").removeClass('favorite-select');
                                $(".favorite[data-type='4'] img").attr('src','../../static/image/h5/job/like-hover-2.png');
                                $(".favorite[data-type='4'] span").empty().text('收藏').removeClass('favorite-select');
                                break;
                            case '5':
                                me.removeClass('favorite-select');
                                me.find("img").attr('src','../../static/image/h5/job/like-white.png');
                                me.find("span").empty().text('收藏');
                                me.closest('.list-bottom').find(".job-list-icon-like").remove();
                                break;
                            case '6':
                                me.removeClass('favorite-select');
                                me.find("img").attr('src','../../static/image/h5/job/like-hover-2.png');
                                me.find("span").empty().text('收藏').addClass('text-F6');
                                me.closest('.d-flex').find(".job-list-icon-like2").remove();
                                break;
                            default:
                                break;
                        }
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
        };
    });


    $(".show-company-bind").on('click',function () {

        $("#favorite-company-unbind").modal('hide');
        $("#company-unbind-note").modal('hide');

        $("#userStatus-3 #search-company-name").val('').removeClass('is-invalid');

        $("#userStatus-3").modal('show');
    });

});
