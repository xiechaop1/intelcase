$(function(){

    //刷新消息信息
    setInterval(getNewsCount,30000);

    //刷新消息信息
    setInterval(getNews,30000);

    //获取消息的数字
    function getNewsCount(){
        $.ajax({
            type: "GET", //用POST方式传输
            dataType: "json", //数据格式:JSON
            url: '/messages/messages_count',
            data:{},
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                /*alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);*/
                console.log("ajax请求失败:"+XMLHttpRequest,textStatus,errorThrown);
            },
            success: function (data, status){
                var dataContent=data;
                var dataCon=$.toJSON(dataContent);
                var obj = eval( "(" + dataCon + ")" );//转换后的JSON对象
                console.log("ajax请求成功:"+data.toString())
                //新消息获取成功
                if(obj["code"]==200){
                    console.log("unread_total:",obj.data.unread_total)
                    if(obj.data.unread_total>0){
                        //设置消息小红点显示
                        $(".open-news .red-dot").removeClass("d-none");

                        console.log("unread_total>0",parseInt(obj.data.unread_total))
                    }
                    if(obj.data.unread_total==0){
                        //设置消息小红点隐藏
                        $(".open-news .red-dot").addClass("d-none");
                        console.log("unread_total=0",parseInt(obj.data.unread_total))
                    }
                }
                //新消息获取失败
                else{

                }

            }
        });


    }

    //后去最新消息内容
    function getNews(){
        $.ajax({
            type: "GET", //用POST方式传输
            dataType: "json", //数据格式:JSON
            url: '/messages/messages_new',
            data:{},
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                /*alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);*/
                console.log("ajax请求失败:"+XMLHttpRequest,textStatus,errorThrown);

            },
            success: function (data, status){
                var dataContent=data;
                var dataCon=$.toJSON(dataContent);
                var obj = eval( "(" + dataCon + ")" );//转换后的JSON对象
                console.log("ajax请求成功:"+data.toString())
                //新消息获取成功
                if(obj["code"]==200){
                   console.log("getNews:",obj.data.is_new)


                 /*   //新消息大于5个
                    if(dataLen>4){
                        var newsHtml=''
                        var n=dataLen-5;
                        for(var i=n;i<dataLen;i++){
                            newsHtml+=`
                              <li>
                                <div class="news-list">
                                    <div class="circle-tag-30 bg-blue">
                                        <i class="iconfont iconcanyin fs-14"></i>
                                    </div>
                                    <div class=" fs-12 text-44 news-list-content">`+
                                `接单小伙伴彭于晏的候选人王帅催单啦，需要亲急速处理哦！`
                                +`
                                    </div>
                                </div>
                            </li>
                           `
                        }
                        $("#news-list-box").empty().html(newsHtml);
                    }
                    //新消息>0,<4
                    else if(dataLen>0){
                        var newsHtml=''
                        for(var i=0;i<dataLen;i++){
                            newsHtml+=`
                              <li>
                                <div class="news-list">
                                    <div class="circle-tag-30 bg-blue">
                                        <i class="iconfont iconcanyin fs-14"></i>
                                    </div>
                                    <div class=" fs-12 text-44 news-list-content">`+
                                `接单小伙伴彭于晏的候选人王帅催单啦，需要亲急速处理哦！`
                                +`
                                    </div>
                                </div>
                            </li> `
                        }

                        var oldNews=$("#news-list-box li");

                        for(var j=0;j<dataLen;j++){
                            oldNews[j].remove()
                        }


                        $("#news-list-box").append(newsHtml);

                    }*/
                }
                //新消息获取失败
                else{

                }
            }
        });


    }


    var news = `
    <!-- 消息模态框 -->
    <div class="modal fade model-style-2" id="newsModal" tabindex="-1">
       <div class="modal-dialog modal-dialog-centered modal-lg">

          <div class="modal-content">
           <button type="button" class="close" data-dismiss="modal"><i class="iconfont iconbtn-guanbi fs-14"></i></button>
            <!-- 模态框头部 -->
             <!--<div class="modal-header">
                <h4 class="modal-title">消息提醒</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>-->
            <!-- 模态框主体 -->
            <div style="height: 500px;">
              <div class="w-100">

        <div class="news">
        <div class="news-header bg-fa fixed" style="width: 948px !important;">
            <label class="border-left-4 m-l-20 m-r-60">
                <span class="fs-16 text-66">最近浏览：</span>
                <!--<a href="" class="text-99 fs-12">更多<i class="iconfont iconicon-riliyoujiantou fs-12"></i></a>-->
            </label>
            <label class="text-F6 btn-page-read m-r-40 fs-14">
                当前页设为已读
            </label>
            <label class="text-F6 btn-all-read fs-14">
                全部设为已读
            </label>
            <input class="form-control datepicker input-group-sm d-inline-block  new-datepicker" style="width: 200px!important;" type="text" readonly="" name="date" value="" data-start-time="" placeholder="请选择日期">
        </div>
        <div class="w-100 new-list-box">
            <div class="news-left fixed">
                <ul class="nav nav-F6 nav-tabs flex-column border-0 justify-content-center align-items-center text-center text-66 fs-18">
                    <li class="w-80 pt-4 pb-3 ">
                        <a class="d-block fs-14 active " href="?type=unread">
                            未读(0)
                        </a>
                    </li>
                    <li class="w-80 pt-3 pb-3 ">
                        <a class="d-block fs-14 " href="?type=all">
                            全部(8)
                        </a>
                    </li>
                    <li class="w-80 pt-3 pb-3 ">
                        <a class="d-block fs-14  " href="?type=10">
                            工作消息(8)
                        </a>
                    </li>
                    <li class="w-80 pt-3 pb-3 ">
                        <a class="d-block fs-14 " href="?type=1">
                            系统消息(0)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="news-right">
                <div class="w-100 p-0 mb-0 relative tab-content main-content ">
                        <div class="tab-pane fade  show  active bg-white">
                            <div>
                                <ul style="height:390px !important;overflow-y: auto;">
                                    <!--未读的消息有is-unread
                                    系统消息图标是 <img src="../../static/image/icon-message.png"/>
                                    工作消息图标是 <img src="../../static/image/icon-notice.png"/>-->
                                                                            <li class="news-list-detail " data-id=" 41">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                您投递的Job1113Job职位候选人孙润泽已经进入了已阅阶段，请亲爱的小伙伴继续关注推荐进展啊！                                            </span>

                                            </div>
                                        </li>
                                                                                <li class="news-list-detail " data-id=" 40">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                您投递的Job1113Job职位候选人宋振已经进入了已阅阶段，请亲爱的小伙伴继续关注推荐进展啊！                                            </span>

                                            </div>
                                        </li>
                                                                                <li class="news-list-detail " data-id=" 36">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                亲爱的小伙伴，您发布的test12346-Job职位已经被顾问test111113接单了。是否需要联系一下他？                                            </span>

                                            </div>
                                        </li>
                                                                                <li class="news-list-detail " data-id=" 35">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                亲爱的小伙伴，您发布的test12346-Job职位已经被顾问test111113接单了。是否需要联系一下他？                                            </span>

                                            </div>
                                        </li>
                                                                                <li class="news-list-detail " data-id=" 34">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                亲爱的小伙伴，您发布的test12346-Job职位已经被顾问test111113接单了。是否需要联系一下他？                                            </span>

                                            </div>
                                        </li>
                                                                                <li class="news-list-detail " data-id=" 33">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                亲爱的小伙伴，您发布的test12346-Job职位已经被顾问test111113接单了。是否需要联系一下他？                                            </span>

                                            </div>
                                        </li>
                                                                                <li class="news-list-detail " data-id=" 32">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                亲爱的小伙伴，您发布的test12346-Job职位已经被顾问test111113接单了。是否需要联系一下他？                                            </span>

                                            </div>
                                        </li>
                                                                                <li class="news-list-detail " data-id=" 25">
                                            <div class="read-status">
                                            </div>
                                            <div class="news-icon">
                                                                                                    <img src="../../static/image/icon-message.png">
                                                                                                </div>
                                            <div class=" fs-12 text-44 news-text">
                                            <span>
                                                亲爱的小伙伴，您发布的test12346-Job职位已经被顾问test12345接单了。是否需要联系一下他？                                            </span>

                                            </div>
                                        </li>
                                                                        </ul>
                            </div>
                            <div class="  m-t-10">
                                <div class="page-num m-r-40">
                                    <span class="fs-14 text-44 m-r-5">共8条/每页 </span>

                                    <div class="page-size">
                                        <input type="text" name="page-size" onkeyup="value=value.replace(/^(0+)|[^\\d]+/g,'')">
                                    </div>

                                    <span class="fs-14 text-44">条</span>
                                </div>

                                
                            </div>


                        </div>



                    </div>
                </div>

            </div>


        </div>




    </div>
            </div>


        </div>
       </div>
    </div>
    
    `


    init()

    function init(){
        //添加消息模态框
        $('body').append(news)
    }


    //点击打开消息弹框
    $('.open-news').click(function (){
        $("#newsModal iframe").attr("src","/messages/news");

        $('#newsModal').modal({
            backdrop:false,
            show:true
        })
    })


    //设置选择的日期
    var winUrl=window.location.href;

    let regex = /\d{4}\-\d{1,2}\-\d{1,2}/;
    var oldDate=winUrl.match(regex);
    if(oldDate){
        $(".new-datepicker").val(oldDate)
    }

    //绑定日历事件
    $(".new-datepicker").datepicker({
        // initialDate: 0,
        // startDate: time,
        language: "zh-CN",
        forceParse: false,
        autoclose: true,//选中之后自动隐藏日期选择框
        todayHighlight: true,
        clearBtn: true,//清除按钮
        format: "yyyy-mm-dd",//日期格式，详见 http://bootstrap-datepicker.readthedocs.org/en/release/options.html#format
        // startDate:startDate,
        // endDate:endDate,
        startDate:"",
        endDate:"",
    })
    //日期选择变化，刷新页面
    $(".new-datepicker").change(function () {


        var val=$(this).val();
        var winUrl=window.location.href;

        if(val){
            //检测是否有搜索条件（无）
            if(winUrl.split("?").length<2){
                window.location.href = winUrl+"?"+"message_date="+val;//页面跳转并传参
            }
            //检测是否有排序条件(无)
            else if(winUrl.split("message_date=").length<2){
                window.location.href = winUrl+"&"+"message_date="+val;//页面跳转并传参
            }
            //检测排序是否在最后一位（是）
            else if(winUrl.split("message_date=")[1].split("&").length<2){
                window.location.href = winUrl.split("message_date=")[0]+"message_date="+val;//页面跳转并传参
            }
            else{

                var oldValue=winUrl.split("message_date=")[1].split("&")[0]
                window.location.href = winUrl.replace("message_date="+oldValue,"message_date="+val);
            }
        }
        else{
            //检测是否有搜索条件（无）
            if(winUrl.split("?").length<2){
                window.location.href = winUrl;//页面跳转并传参
            }
            //检测是否有排序条件(无)
            else if(winUrl.split("message_date=").length<2){
                window.location.href = winUrl;//页面跳转并传参
            }
            //检测排序是否在最后一位（是）
            else if(winUrl.split("message_date=")[1].split("&").length<2){
                window.location.href = winUrl.split("message_date=")[0];//页面跳转并传参
            }
            else{

                var oldValue=winUrl.split("message_date=")[1].split("&")[0]
                window.location.href = winUrl.replace("message_date="+oldValue,'');
            }
        }

    })


    $(".btn-close-news").unbind().click(function () {
        var parenWin=window.parent;
        parenWin.$("#newsModal").modal("hiden")
    })


    let regexSize=/limit=\d{1,4}/;

    var oldSize=winUrl.match(regexSize);

    if(oldSize){
        oldSize=oldSize.toString().replace(/limit=/,'');
        $("input[name='page-size']").val(oldSize)
    }


    //搜索框输入回车
    $("input[name='page-size']").unbind().bind('keydown',function(event){
        if(event.keyCode == "13"){
            winUrl()
        }
    })

    $(".page_link").click(function (event) {
        event.preventDefault();
        var page=$(this).attr("data-page")
        winUrl(page);
    })


    winUrl=function winUrl(page) {

        var data='';
        var winUrl=window.location.href;
        var url=winUrl.split("/messages/news")[0]+"/messages/news";
        var data=url;

        let regex = /message_date=\d{4}\-\d{1,2}\-\d{1,2}/;
        var oldDate=winUrl.match(regex);



        var pageSize=10;
        $("input[name='page-size']").val()>10?pageSize=$("input[name='page-size']").val():pageSize=10;

        var read=$(".news-left  a.active").attr("href");


        data+=read;

        if(oldDate){
            data+="&"+oldDate;
        }
        data+="&limit="+pageSize;
        if(page){
            data=data+"&page="+page;
        }

        window.location.href=data
    }


    //设置为已读
    $(".is-unread").click(function () {
        var me=$(this);
        var id=me.attr("data-id");

        $.ajax({
            type: "get", //用get方式传输
            dataType: "json", //数据格式:JSON
            url: '/messages/message_detail?message_id='+id,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
            },
            success: function (data, status){
                console.log("/messages/message_detail?message_id"+JSON.stringify(data));

                me.removeClass("is-unread")
                //新消息获取成功
                if(data.code==200){
                    //console.log("code=200");
                    getNewsCount();
                   // alert("提交成功")
                    window.location.reload();
                }
                //新消息获取失败
                else{
                    console.log("code!=200");
                    alert("提交失败")

                }

            }
        });
    })

    //当前页设为已读
    $(".btn-page-read").click(function () {
        var list=$(".news-list-detail");
        var id='';
        list.each(function () {
            var dataId=$(this).attr("data-id");
            if(dataId && $(this).hasClass("is-unread")){
                // id.push(dataId);
                id+="&ids[]="+parseInt(dataId)
            }

        })

        var tab=$(".news-left a.active").attr("href");
        var type;
        switch (tab){
            case '?type=unread':
                type='';
                break;
            case '?type=all':
                type='';
                break;
            case '?type=10':
                type=10;
                break;
            case '?type=1':
                type=1;
                break;
        }

        $.ajax({
            type: "get", //用get方式传输
            dataType: "json", //数据格式:JSON
            url: '/messages/set_messages_api?message_type='+type+id,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown,id);
            },
            success: function (data, status){
                console.log('/messages/set_messages_api?message_type='+type+id+"："+JSON.stringify(data));

                list.removeClass("is-unread")
                //新消息获取成功
                if(data.code==200){
                    getNewsCount();
                    window.location.reload();
                }
                //新消息获取失败
                else{
                    console.log("code!=200");
                    alert("提交失败")

                }

            }
        });
    })
    //当前页设为已读
    $(".btn-all-read").click(function () {

        $.ajax({
            type: "get", //用get方式传输
            dataType: "json", //数据格式:JSON
            url: '/messages/set_messages_api?ids[]=0',
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
            },
            success: function (data, status){
                console.log("/messages/set_messages_api?ids[]=0："+JSON.stringify(data));

                //新消息获取成功
                if(data.code==200){
                    getNewsCount();
                    window.location.reload();
                }
                //新消息获取失败
                else{
                    console.log("code!=200");
                    alert("提交失败")

                }

            }
        });
    })




});





