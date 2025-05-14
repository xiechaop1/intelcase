$(function () {
    var loadingHtml=`
        <div class="col-12 blank-box loading-box"  >
        <!--<i class="iconfont iconicon-shuaxin fs-60 text-44" id="loading"></i>-->
        
        <div class="m-t-60  m-b-60" align="center">
           <img src="../../static/image/loading-1.gif" class="m-auto w-100px m-b-20"/><br>
            数据加载中~
        </div>
    </div>`;


    var netErrorHtml=`<div class="col-12 m-b-20 text-center"  >
                            <img src="../../static/image/500.png" style="width:280px;" class="m-t-80 m-b-150"/>
                        </div>`;
    getProductAjax=function getProductAjax(page,pageSize,time){
        $("#index-job-list-box").append(loadingHtml)
        //ajax数据请求项
        var filter;
        if(time){
            filter="time="+time;
        }
        else{
            filter=$("#recentBrowse").serialize();
        }

        if(page||page==0){
            filter+="&page="+page;
        }
        if(pageSize){
            filter+="&pageSize="+pageSize;
        }

        $.ajax({
            type: "GET", //用POST方式传输
            dataType: "html", //数据格式:JSON
            url: '/site/get_latest_view_api?'+filter,
            data:{},
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                /*alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);*/
                console.log("ajax请求失败:"+XMLHttpRequest,textStatus,errorThrown);
                console.log(XMLHttpRequest.status,XMLHttpRequest.responseText,XMLHttpRequest.readyState,XMLHttpRequest.statusText);
                if(XMLHttpRequest.status==403){
                    window.location.reload();
                }

                $("#index-job-list-box").empty().html(netErrorHtml)
            },
            success: function (data, status){



                $("#index-job-list-box  .loading-box").remove();
                $("#index-job-list-box .load-page").closest('div').remove();
                $("#index-job-list-box").empty().append(data);

                $(".page_link").unbind().click(function (event) {
                    console.log("绑定翻页时间:"+status);
                    event.preventDefault();
                    var page= $(this).attr("data-page");
                    var pageSize=$("input[name='pageSize']").val()
                    getProductAjax(page,pageSize)
                })

                /*收藏类型说明：
1：h5列表页收藏功能；
2：h5职位详情页收藏功能，关联页面内type=3收藏；
3：h5职位详情页收藏功能，关联页面内type=2收藏；
4：pc 职位详情页收藏
5：pc 已登录首页
6：pc 推荐职位收藏
*/
                $(".favorite").unbind().on('click',function () {
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
                                            $("#h5-like-success").modal('show');
                                            break;
                                        case '4':
                                            $(".favorite[data-type='4']").addClass('favorite-select');
                                            $(".favorite[data-type='4'] img").attr('src','../../static/image/h5/job/like-hover.png');
                                            $(".favorite[data-type='4'] span").empty().text('已收藏').addClass('favorite-select');
                                            var company_status=parseInt($("input[name='companyStatus']").val());

                                            if(company_status==3){
                                                $("#favorite-company-unbind").modal('show')
                                            }
                                            break;
                                        case '5':
                                            me.addClass('favorite-select');
                                            me.find("img").attr('src','../../static/image/h5/job/like-hover.png');
                                            me.find("span").empty().text('已收藏');
                                            break;
                                        case '6':
                                            me.addClass('favorite-select');
                                            me.find("img").attr('src','../../static/image/h5/job/like-hover.png');
                                            me.find("span").empty().text('已收藏');
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
                                            me.attr('src','../../static/image/h5/job/like-hover.png');
                                            $(".favorite[data-type='2']").empty().text('收藏').removeClass('favorite-select');
                                            break;
                                        case '4':
                                            $(".favorite[data-type='4']").removeClass('favorite-select');
                                            $(".favorite[data-type='4'] img").attr('src','../../static/image/h5/job/like.png');
                                            $(".favorite[data-type='4'] span").empty().text('收藏').removeClass('favorite-select');
                                            break;
                                        case '5':
                                            me.removeClass('favorite-select');
                                            me.find("img").attr('src','../../static/image/h5/job/like-white.png');
                                            me.find("span").empty().text('收藏');
                                            break;
                                        case '6':
                                            me.removeClass('favorite-select');
                                            me.find("img").attr('src','../../static/image/h5/job/like.png');
                                            me.find("span").empty().text('收藏');
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

            }
        });

    }

    var winArgs = new Object();
    winArgs = GetUrlParms();
    var time=winArgs.time

    getProductAjax(0,0,time)

    //获取url中的关键词
    function GetUrlParms(){

        var args=new Object();
        var url=window.location.href
        url?url=url:url="?";

        var query=url.split("?")[1];//获取查询串
        if(query){
            var pairs=query.split("&");//在逗号处断开

            for(var   i=0;i<pairs.length;i++)

            {

                var pos=pairs[i].indexOf('=');//查找name=value

                if(pos==-1)   continue;//如果没有找到就跳过

                var argname=pairs[i].substring(0,pos);//提取name

                var value=pairs[i].substring(pos+1);//提取value

                args[argname]=unescape(value);//存为属性

            }
        }

        return args;
        console.log(JSON.stringify(args))

    }
})