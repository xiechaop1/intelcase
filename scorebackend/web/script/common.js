/**
 *
 */
$(document).ready(function() {
    var close=`<i class="fa fa-close" onclick="deleteFeelist(this);" style="font-size: 20px;margin-top: 5px;"></i>`
    var herf=window.location.href;
    if(herf.split("group_fee_edit").length>1){
        $(".field-boutiqueformgroupfee-tag").append(close);
    }

})

$(document).on('click', 'a.ajax-status-btn', function (e) {
    var self = this;
    var msg = $(self).attr("request-confirm");
    if (!msg) {
        msg = "确定要修改状态吗?";
    }
    if (confirm(msg)) {
        $.ajax({
            "url": $(self).attr("request-url"),
            "type": $(self).attr("request-type"),
            "dataType": "JSON",
            "data": {
                "action": $(self).attr("data-action"),
                "id": $(self).attr("data-id"),
                "value": $(self).attr("data-value")
            },
            "success": function (data) {
                if (data["status"]) {
                    if ($(self).attr("data-callback")) {
                        var f = eval('(' + $(self).attr("data-callback") + ')');
                        f(e);
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(data["msg"]);
                }
            },
            "error": function (data) {
                alert(data.responseText);
            }
        });
    }
});

$(document).on('click', 'a.delete_single_btn,button.delete_single_btn', function () {
    var self = this;
    var confirm_mssage = $(this).attr("data-sure");
    if (!confirm_mssage) {
        confirm_mssage = "确定要删除这条数据吗?"
    }
    if (confirm(confirm_mssage)) {
        $.ajax({
            "url": $(self).attr("request-url"),
            "type": $(self).attr("request-type"),
            "dataType": "JSON",
            "data": {
                "action": $(self).attr("data-action"),
                "id": $(self).attr("data-id")
            },
            "success": function (data) {
                if (data["status"]) {
                    window.location.reload();
                } else {
                    alert(data["msg"]);
                }
            },
            "error": function (data) {
                alert(data.responseText);
            }
        })
    }
});

$(document).on('click', 'a.ajax_single_btn,button.ajax_single_btn', function () {
    var self = this;
    $.ajax({
        "url": $(self).attr("request-url"),
        "type": $(self).attr("request-type"),
        "dataType": "JSON",
        "data": {
            "action": $(self).attr("data-action"),
            "id": $(self).attr("data-id")
        },
        "success": function (data) {
            if (data["status"]) {
                window.location.reload();
            } else {
                alert(data["msg"]);
            }
        },
        "error": function (data) {
            alert(data.responseText);
        }
    })

});

$(document).on('change', 'select.status-change', function () {
    var self = this;
    var confirm_mssage = $(this).attr("data-sure");
    console.log(confirm_mssage);
    if (!confirm_mssage) {
        confirm_mssage = "确定要修改这条数据的状态吗?"
    }
    if (confirm(confirm_mssage)) {
        $.ajax({
            "url": $(self).attr("request-url"),
            "type": "POST",
            "dataType": "JSON",
            "data": {
                "action": $(self).attr("data-action"),
                "id": $(self).attr("data-id"),
                "value": $(self).val()
            },
            "success": function (data) {
                if (data["status"]) {
                    if (typeof $(self).attr("data-callback") == "function") {

                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(data["msg"]);
                }
            },
            "error": function (data) {
                alert(data.responseText);
            }
        })
    }
});

$("select.status-change").change(function (e) {

});

$(".batch-operate").click(function () {
    var containerId = '#' + $(this).attr("data-container");
    var dataIds = $(containerId).yiiGridView('getSelectedRows');
    if (dataIds.length == 0) {
        alert("请选择数据");
    } else {
        if (confirm("确定要批量操作这些数据吗")) {
            var self = this;
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: $(self).attr('request-url'),
                data: {
                    action: $(self).attr('data-action'),
                    ids: dataIds
                },
                success: function (data) {
                    if (data["status"]) {
                        window.location.reload(true);
                    } else {
                        alert(data["msg"]);
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });
        }

    }
});


function deleteFeelist(e){
    var me=e;
    $(me).closest(".form-group").next(".form-group").remove();
    $(me).closest(".form-group").remove();
    console.log(" deleteFeelist()");
}

$(document).on('click', '#addFeeButton', function () {
    // console.log("test");
    // var len=$(".field-boutiqueformgroupfee-fee").length
    //
    // var select=$(".field-boutiqueformgroupfee-tag:first").clone(true);
    // var selectId="boutiqueformgroupfee-tag"+len;
    // select.find("input").attr("id",selectId)


    var zhichu=`<div class="form-group field-boutiqueformgroupfee-tag">
                <label class="control-label col-sm-3" for="boutiqueformgroupfee-tag">支出科目</label>
                <div class="col-sm-6">
                <input type="text" id="boutiqueformgroupfee-tag" class="form-control" name="BoutiqueFormGroupFee[tag][]">
                <p class="help-block help-block-error "></p>
                </div>
                <i class="fa fa-close" onclick="deleteFeelist(this);" style="font-size: 20px;margin-top: 5px;"></i>
                </div>`

    var price=`<div class="form-group field-boutiqueformgroupfee-fee">
                <label class="control-label col-sm-3" for="boutiqueformgroupfee-fee">费用</label>
                <div class="col-sm-6">
                <input type="text" id="boutiqueformgroupfee-fee" class="form-control" name="BoutiqueFormGroupFee[fee][]">
                <p class="help-block help-block-error "></p>
                </div>
                </div>`

   // var input=$(".field-boutiqueformgroupfee-fee:first").clone(true);
   //  var inputId="boutiqueformgroupfee-fee"+len;
    //    //  input.find("input").attr("id",inputId);
    //    //
    //    //  var btn=$(".addFeeButton");
    //    //  console.log("addFeeButton",select,input)

  //  $("#w1").append(select).append(input);
    $(".addFeeButton").before(zhichu).before(price);


  //  selectBind(selectId,inputId)
});

$(document).on('click', '#addFeeSubmitButton', function () {

    var datarray=[];

    var select=$("input[name='BoutiqueFormGroupFee[tag]']")

    select.each(function () {
        var item=[]
        var itemName=$(this).val();
        var itemId=$(this).attr('id');
        console.log(itemId,select.length,itemName);
        var id=itemId.replace("boutiqueformgroupfee-tag",'')
        var itemPrice=$("#boutiqueformgroupfee-fee"+id).val()
        item.push(itemName,itemPrice);
        datarray.push(item)

    })

    $("input[name='boutiqueformgroupfee']").val(datarray);

    console.log(datarray)

    $("#addFeeSubmitButton").closest('form').submit();

    //  selectBind(selectId,inputId)
});





selectBind=function selectBind(select,inputId) {
    $.ajaxSetup({
        data: {"_csrf-backend": "2hWQmQ4PrmtxuHxvifKHThbKUQW5KoSHqC1SnYeEl47tfeG0OXXiIEbZH1nww81jXLMiXfBAytXrSQH7_dzE5A=="},
        cache:false
    });
    jQuery&&jQuery.pjax&&(jQuery.pjax.defaults.maxCacheLength=0);
    if (jQuery('#'+select).data('select2')) { jQuery('#'+select).select2('destroy'); }
    jQuery.when(jQuery('#'+select).select2(select2_50f2809b)).done(initS2Loading(select,'s2options_d6851687'));

    jQuery('#w1').yiiActiveForm([{"id":"boutiqueformgroupfee-group_id","name":"group_id","container":".field-boutiqueformgroupfee-group_id","input":"#boutiqueformgroupfee-group_id","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Group Id cannot be blank."});}},{"id":select,"name":"tag_id","container":"."+select,"input":"#"+select,"error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Tag Id cannot be blank."});}},{"id":inputId,"name":"fee","container":"."+inputId,"input":"#b"+inputId,"error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Fee cannot be blank."});}}], []);

}
