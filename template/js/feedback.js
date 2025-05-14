$(function(){

    //添加访谈模态框
    var feedback = `
<!-- 问答反馈查看模态框 -->
<div class="modal fade model-style-2" id="replay-feedback" tabindex="-1">
    <div class="modal-dialog w-mid modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="w-100 news-header p-15-20 border-bottom bg-fa ">
                <label class="border-left-4 m-l-20 m-r-60">
                    <span class="fs-16 text-66">问答反馈</span>
                </label>
            </div>
            <button type="button" class="close" data-dismiss="modal"><i class="iconfont iconbtn-guanbi fs-14"></i></button>
            <!-- 模态框主体 -->
            <div class="modal-body ">
                <div  class="p-10-0 modal-recommend-candidates">
                    <div class="modal-interview-record">
                       
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
        $('body').append(feedback)
    }


    //查看访谈记录
    $(".replay-feedback").click(function () {
        var me=$(this);
        var id=me.attr("data-id");

        $("#replay-feedback").modal({
            show:true
        })

        $(".modal-backdrop").css('opacity','0');



        //获取采访记录
        $.ajax({
            type: "GET", //用POST方式传输
            dataType: "text", //数据格式:JSON
            url: '/qa/get_qa_list?job_id='+id,
            data:{},
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                /*alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);*/
                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                $("#replay-feedback .modal-interview-record").empty().html("网络异常")
            },
            success: function (data, status){
                $("#replay-feedback .modal-interview-record").empty().html(data);
                $(".modal-reply-feedback").unbind().click(function () {
                    var me=$(this);
                    var job_ID=parseInt(me.closest("form").find("input[name='job_id']").val());

                    var feedback=me.closest("form").find("input[name='qa']").val();
                    if(feedback&&feedback.split(" ").join("").length>0){
                        //修改发单记录
                        $.ajax({
                            type: "post", //用get方式传输
                            dataType: "json", //数据格式:JSON
                            url: '/qa/save_qa',
                            /*          data:JSON.stringify({
                                          'qa':feedback,
                                          'job_id':id
                                      }),*/
                            data:me.closest("form").serialize(),
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                /*alert(XMLHttpRequest.status);
                                alert(XMLHttpRequest.readyState);
                                alert(textStatus);*/
                                console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                            },
                            success: function (data, status){
                                var dataContent=data;
                                var obj=$.toJSON(dataContent);
                                //var obj = eval( "(" + dataCon + ")" );//转换后的JSON对象
                                console.log("ajax请求成功:"+JSON.stringify(obj))
                                //新消息获取成功
                                if(data.code==200){
                                    console.log("code=200");
                                    alert("反馈提交成功");
                                    //me.remove()
                                    //me.closest("form").find("input[name='qa']").remove();
                                   // me.closest("form").find(".replay-content']").append(feedback);

                                    var qa_list=`
                                    <div class="w-100 bg-fa border-EA p-10-20 m-t-10 replay-content qa-list">
                                                         <p class="fs-12 text-33 m-b-10">发单方回复 <span class="float-right fs-12 text-66">刚刚</span></p>
                                                       <i class="iconfont iconicon-huida fs-14 text-99 m-l-5"></i>
                                                       `+feedback+`</div>
                                    `
                                    me.closest(".row").find(".qa-list-box").prepend(qa_list)

                                    //有一个回答成功即去掉红点
                                    $(".replay-feedback[data-id='"+job_ID+"']").find(".orange-dot").remove();
                                    
                                    //剩余未回答数
                                    var unReply=$("#replay-feedback .modal-reply-feedback").length
                                    
                                    //如果剩余回答数为0，清空对红点
                                  /*  if(unReply==0){
                                        $(".replay-feedback[data-id='"+job_ID+"']").find(".orange-dot").remove()
                                    }*/
                                }
                                //新消息获取失败
                                else{
                                    console.log("code!=200");
                                    alert("反馈提交失败")

                                }

                            }
                        });

                    }
                    else{
                        alert("请输入反馈内容")
                    }
                });

                $(".replay-again").unbind().click(function () {
                    var me=$(this);
                    var replay=me.closest('form').find(".replay-content");
                    if(replay.hasClass("d-none")){
                        replay.removeClass("d-none");
                        me.empty().text("收起")
                    }else{
                        replay.addClass("d-none");
                         me.empty().text("继续回复")
                    }



                })




            }
        });
    })





});





