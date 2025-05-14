$(function () {

/*
首页交互内容
*/
    //提交按钮绑定
    $('.submit').on('click',submitHandle);

    $(".clear-form").on('click',clearForm);
    
    //翻页按钮绑定交互事件

    $("#messageBox .pagination a").attr("href",'javascript:void(0);')
    $("#messageBox .pagination a").bind("click",function (event) {
        event.preventDefault();
        var page = parseInt($(this).attr("data-page"))+0;
        messsage(page)
    })

    //表单校验公用函数
    function submitHandle(e){

        var $form = $(e.target).closest('form')
        $.checkForm($form,function(){
            var name = this.attr('name').toString()
            var self = this;

            switch(name){
                case "messagePublish" :
                    console.log('messagePublish');
                    var val=self.serialize();
                    $.ajax({
                        url: "messages/send_messages",
                        type: "POST",
                        data: val,
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                        },
                        success: function(result) {
                            if(result.status){
                                messsage(1)
                                alert("消息发布成功")
                            }
                            else {
                                alert(result.msg)
                            }
                        }
                    })

                    break;


            }

        })

    };

    //表单校验公用函数
    function clearForm(e){

        var $form = $(e.target).closest('form');
        $form.find("input").val("").removeClass("has-error").removeClass("has-success");
        $form.find("textarea").val("").removeClass("has-error").removeClass("has-success");

    };


    //消息内容

    $("input[name='select-all']").change(function () {
        var me=$(this);
        var val=me.prop("checked");
        console.log("select-all",val)
        if(val){
            $("input[name='check-group']").prop("checked",true)
        }
        else{
            $("input[name='check-group']").prop("checked",false)
        }
    })


    $(".filter-box select,.filter-box input[name='end_date']").bind("change",function () {
        var $form=$(this).closest("form")
        var filter=$form.serialize();
        var formName=$form.attr("name");
        var statusName,status;
        var win=window.location.href

        if(formName=="audit-company"){
            statusName="company_status"
        }
        else if(formName=="audit-members"){
            statusName="member_status"
        }
        else {
            statusName=''
        }


        if(statusName){
            var args = new Object();
            args = GetUrlParms();

            status=args[statusName];
            status?status=status:status=0
            window.location.href=win.split("?")[0]+"?"+statusName+"="+status+"&"+filter;

        }
        else {
            window.location.href=win.split("?")[0]+"?"+filter;

        }









    })

    $(".filter-box .search").bind("click",function () {
        var me=$(this);
        var search=$(".filter-box input[name='search']").val()

        if(search){
            var $form=me.closest("form")
            var filter=$form.serialize();
            var formName=$form.attr("name");
            var statusName,status;
            var win=window.location.href

            if(formName=="audit-company"){
                statusName="company_status"
            }
            else if(formName=="audit-members"){
                statusName="member_status"
            }
            else {
                statusName=''
            }


            if(statusName){
                var args = new Object();
                args = GetUrlParms();

                status=args[statusName];
                status?status=status:status=0
                window.location.href=win.split("?")[0]+"?"+statusName+"="+status+"&"+filter;

            }
            else {
                window.location.href=win.split("?")[0]+"?"+filter;

            }
        }
        else {
            alert("请输入搜索内容")
        }

    })

    $(".pass_single_btn").bind("click",function () {
        var me=$(this);
        var id=me.attr("data-id");
        $("input[name='company-id']").val(id)

    })

    $(".fail_single_btn").bind("click",function () {
        var me=$(this);
        var id=me.attr("data-id");
        var company=me.attr("data-company");
        $("input[name='data-id']").val(id);
        $("div[name='company-name']").empty().text(company);

        $("#check-fail").modal({
            show:true
        })
    })

    $(".pass_single_btn").bind("click",function () {
        var me=$(this);
        var id=me.attr("data-id");
        $("input[name='data-id']").val(id);

        $("#check-pass .note").empty().text("确认通过此用户的申请？")

        $("#check-pass").modal({
            show:true
        })

    })



    $(".audit-all-pass").bind("click",function () {
        var items=$("input[name='check-group']:checked")
        var len=items.length
        if(len>0){
            var id=[]
            items.each(function () {
                id.push($(this).attr("data-id"))
            })
            $("input[name='data-id']").val(id);

            console.log($("#check-pass input[name='data-id']").val())
            $("#check-pass .note").empty().text("确认通过这"+len+"个用户的申请？")

            $("#check-pass").modal({
                show:true
            })

        }
        else {
            alert("选择内容不能为空")
        }

    })
    $(".audit-all-fail").bind("click",function () {
        var items=$("input[name='check-group']:checked")
        var len=items.length
        if(len>0){
            var id=[];
            var html=''

            items.each(function () {
                id.push($(this).attr("data-id"));
                html+=$(this).attr("data-name")+"<br>"
            })
            $("input[name='data-id']").val(id);

            console.log($("#check-fail input[name='data-name']").val())

            //$("div[name='company-name']").empty().text("已选择"+len+"项");
            $("div[name='company-name']").empty().html(html);

            $("#check-fail").modal({
                show:true
            })

        }
        else {
            alert("选择内容不能为空")
        }

    })

    //显示退款协议
    $(".show-info").on('click',function () {
        var me=$(this);
        $("#showInfo .modal-title").empty().text(me.attr("data-title"));
        $("#showInfo .modal-body").empty().text(me.attr("data-info"));


        $("#showInfo").modal({
            show:true
        })
    })

    $(".max-text-1").on('click',function () {
        var me=$(this);
        if(me.hasClass("open")){
            me.removeClass("open")
        }
        else {
            me.addClass("open")
        }


    })



    $(".datepicker").datepicker({
        // initialDate: 0,
        // startDate: time,
        language: "zh-CN",
        forceParse: false,
        autoclose: true,//选中之后自动隐藏日期选择框
        clearBtn: false,//清除按钮
        todayHighlight: true,
        format: "yyyy-mm-dd",//日期格式，详见 http://bootstrap-datepicker.readthedocs.org/en/release/options.html#format
        startDate:$(this).attr('data-start-time')

    }).on('changeDate',function(){

        $('.datepicker').eq(0).datepicker('hide');
    })

    $('.datepicker[name="start_date"]').on('change',function(e){
        console.log(e.target.value)
        console.log($(e.target).parents('form').find('.datepicker[name="end_date"]').attr('data-start-time'))
        new Date(e.target.value) > new Date($(e.target).parents('form').find('.datepicker[name="end_date"]').attr('data-start-time'))?
            $(e.target).parents('form').find('.datepicker[name="end_date"]').attr({'data-start-time':e.target.value,'value':e.target.value}).val(e.target.value).datepicker('setStartDate',e.target.value) : ''
        console.log($(e.target).parents('form').find('.datepicker[name="end_date"]').attr('data-start-time'));
        console.log($(e.target).parents('form').find('.datepicker[name="end_date"]').val())

    })

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