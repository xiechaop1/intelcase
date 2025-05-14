$(function(){

    var resume = `
<!-- 访谈记录修改模态框 -->
<div class="modal fade model-style-3" id="personal-resume-online" tabindex="-1">
    <header class="fixed bg-black w-100 ">
        <div class="d-inline-block vertical-mid text-FF">
            在线简历
        </div>
        <span class="fs-16 text-FF float-right" data-dismiss="modal" ><i class="iconfont iconbtn-guanbi fs-14"></i></span>
        <label class="btn-green-s open-url m-r-20 float-right  m-t-12 online-resume-submit" data-id=""  data-dismiss="modal">确认</label>
        <a href="" class="change-interview-record" target="_blank">
            <label class="btn-white-border-s open-url m-r-20 float-right m-t-12" data-id="">访谈记录修改</label>
        </a>
        <a href="" class="change-person-resume" target="_blank">
            <label class="btn-white-border-s  open-url m-r-20 float-right  m-t-12"  data-id="">简历修改</label>
        </a>
        <a href="" class="download-online-resume" target="_blank">
            <label class="btn-white-border-s  open-url m-r-20 float-right  m-t-12"  data-id="">下载简历</label>
        </a>
    </header>

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- 模态框主体 -->
            <div class="modal-body m-t-60">
                <iframe src="" class="no-border" width="100%" scrolling="yes" height="550px"></iframe>
            </div>
        </div>
    </div>
</div>
    `


    init()

    function init(){
        //添加消息模态框
        $('body').append(resume)
    }


    //点击打开消息弹框
    $('.look-online-resume').click(function (){
        var me=$(this);
        var id=me.attr("data-id");
        var winUrl=window.location.href;
        var winUrlLen=winUrl.split("/mypost_candidate").length;
        var winUrlLen1=winUrl.split("/myorders").length;
        var winUrlLen2=winUrl.split("mypost_progress").length;

        var recommend=me.attr("data-recommend");
        var filter=me.attr("data-filter");
        var resume=me.attr("data-resume");

        if(id){



            $("#personal-resume-online .online-resume-submit").attr("data-id",id);
            $("#personal-resume-online .change-interview-record").attr("href","/workbench/myorders_interview?id="+id);
            $("#personal-resume-online .change-person-resume").attr("href","/workbench/myorders_resume_edit?id="+id);

            if(resume.length>5&&resume){
                $("#personal-resume-online .download-online-resume").attr("href",resume).removeClass("d-none");
            }else{
                $("#personal-resume-online .download-online-resume").attr("href",resume).addClass("d-none");
            }

            var h=parseInt($("body").height())-60
            $("#personal-resume-online iframe").attr("height",h+"px");
            $("#personal-resume-online iframe").attr("src","/workbench/myorders_resume?id="+id+"&is_iframe=1");

            if(winUrlLen>1||winUrlLen1>1||winUrlLen2>1){
                $("#personal-resume-online .change-interview-record").addClass("d-none");
                $("#personal-resume-online .change-person-resume").addClass("d-none");
                $("#personal-resume-online .change-interview-record").remove();
                $("#personal-resume-online .change-person-resume").remove();
            }


            $('#personal-resume-online').modal({
                show:true
            })
        }
        else{
            alert("内容异常请刷新页面")
        }



        if(recommend && filter<30){
            $.ajax({
                type: "GET", //用get方式传输
                dataType: "json", //数据格式:JSON
                url: '/recommend/change_status?recommend_ids[]='+recommend+'&recommend_status=20&recommend_filter_detail=20',
                error: function (XMLHttpRequest, textStatus, errorThrown) {

                    console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown,data,JSON.stringify(data));
                },
                success: function (data, status){
                    console.log("ajax请求成功:"+JSON.stringify(data))
                    //新消息获取成功
                    if(data.code==200){
                        console.log("code=200");

                       // window.location.reload();
                    }
                    //新消息获取失败
                    else{
                        console.log("code!=200");

                    }
                }
            });
        }




    })


    //点击打开消息弹框
    $('.download-resume').click(function (){
        var me=$(this);

        var winUrl=window.location.href;
        var winUrlLen=winUrl.split("/mypost_candidate").length;

        var recommend=me.attr("data-recommend");
        var filter=me.attr("data-filter");

        if(recommend && filter<30){
            $.ajax({
                type: "GET", //用get方式传输
                dataType: "json", //数据格式:JSON
                url: '/recommend/change_status?recommend_ids[]='+recommend+'&recommend_status=20&recommend_filter_detail=20',
                error: function (XMLHttpRequest, textStatus, errorThrown) {

                    console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown,data,JSON.stringify(data));
                },
                success: function (data, status){
                    console.log("ajax请求成功:"+JSON.stringify(data))
                    //新消息获取成功
                    if(data.code==200){
                        console.log("code=200");

                    }
                    //新消息获取失败
                    else{
                        console.log("code!=200");

                    }
                }
            });
        }




    })


});





