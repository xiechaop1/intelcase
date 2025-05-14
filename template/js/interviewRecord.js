$(function(){
    
    //添加访谈模态框
    var interviewRecordModal = `
<!-- 访谈记录查看模态框 -->
<div class="modal fade model-style-2" id="interview-record" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="w-100 news-header p-15-20 border-bottom bg-fa ">
                <label class="border-left-4 m-l-20 m-r-60">
                    <span class="fs-16 text-66">候选人访谈记录</span>
                </label>
                <a href="" class="edit-interview" target="_blank">
                    <span class="float-right text-F6">访谈记录修改</span>
                </a>
            </div>
            <button type="button" class="close" data-dismiss="modal"><i class="iconfont iconbtn-guanbi fs-14"></i></button>
            <!-- 模态框主体 -->
            <div class="modal-body ">
                <div  class="p-10-20 modal-recommend-candidates" style="max-height: 500px;overflow-y: auto;">
                    <form method="post">
                        <div class="modal-interview-record">
                          
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    
    `


    init()

    function init(){
        //添加消息模态框
        $('body').append(interviewRecordModal)
    }

    //
    var resultEmptyHtml=`<div class="col-12 blank-box"  >
                        <img src="/static/image/img-blank.png" class="img-no-result"/>
                        <div class="m-t-60 " align="center">
                             这里空空的，没有找到查询内容~
                        </div>
                    </div>`;

    //查看访谈记录
    $(".look-interview-record").click(function () {
        var me=$(this);
        var id=me.attr("data-id");
        var winUrl=window.location.href;
        var winUrlLen=winUrl.split("/mypost_candidate").length;
        var winUrlLen1=winUrl.split("/myorders").length;
        var winUrlLen2=winUrl.split("mypost_progress").length;


        if(winUrlLen>1||winUrlLen2>1){
          $("#interview-record .edit-interview").remove()
        }
        else {
            $("#interview-record .edit-interview").attr("href",'/workbench/myorders_interview?id='+id)
        }


        $("#interview-record").modal({
            show:true
        })



        //获取采访记录
        $.ajax({
            type: "GET", //用POST方式传输
            dataType: "json", //数据格式:JSON
            url: '/document/get_document?document_id='+id,
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

                //新消息获取成功
                if(obj["code"]==200){
                    var interview=data.data.interview
                    console.log("ajax请求成功:"+JSON.stringify(data.data.interview))
                    if(interview){

                        var interlist='';

                    /*    for(var i=0;i<interview.length;i++){
                            interlist+=`<li class="m-t-30"><div class="w-100 fs-14 text-44">`+interview[i].q+` </div><div class="w-100  fs-14 text-44 m-t-10  p-10-20 border-EA bg-fa">`
                                +nterview[i].a+` </div></li>`;
                        }*/

                        for(var q in interview){
                            interlist+=`<li class="m-t-30"><div class="w-100 fs-14 text-44">`+q+` </div><div class="w-100  fs-14 text-44 m-t-10  p-10-20 border-EA bg-fa m-h-44">`
                                +interview[q]+` </div></li>`;
                        }

                       interlist="<ul>"+interlist+'</ul>';
                        $("#interview-record .modal-interview-record").empty().html(interlist)


                    }else {
                        $("#interview-record .modal-interview-record").empty().html(resultEmptyHtml)
                    }

                }
                //新消息获取失败
                else{

                }

            }
        });
    })



});





