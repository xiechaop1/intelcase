$(function () {
    //分类下拉
    var contactConfig = {
        trigger: 'click',
        html: true,
        placement : 'bottom',
        content : `
            <div class="text-EB">
                <div class="d-flex align-items-center justify-content-around mt-2">
                    <div class="medium mr-2 w-50">
                        机票在线客服：
                    </div>
                    <div class="btn btn-danger medium mr-2">联系客服</div>
                </div>
                <div class="d-flex align-items-center justify-content-around mt-2">
                    <div class="medium mr-2 w-50">
                        接送机客服：
                    </div>
                    <div class="btn btn-danger medium mr-2">联系客服</div>
                </div>
                <div class="d-flex align-items-center justify-content-around mt-2">
                    <div class="medium mr-2 w-50">
                        婚纱摄影客服：
                    </div>
                    <div class="btn btn-danger medium mr-2">联系客服</div>
                </div>
                <div class="d-flex align-items-center justify-content-around my-2">
                    <div class="medium mr-2 w-50">
                        定制旅行客服：
                    </div>
                    <div class="btn btn-danger medium mr-2">联系客服</div>
                </div>
            </div>
        `, 
        template :  '<div class="popover w-100 border-F6 moreWidth kindSelect border-0 bg-FB" role="tooltip"><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    
    }
    //开启下拉 并且mouseover不会消失
    $('header .login >div >a:nth-of-type(1).online').popover(contactConfig)
    .on("mouseenter", function () {
        var _this = this;   // 这里的this触发的dom,需要存起来 否则在下面 .popover的逻辑中this会变为弹出的dom
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide'); 
        });
    })
    .on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 300);
    });

    var wechatConfig = {
        trigger: 'click',
        html: true,
        placement : 'bottom',
        content : `
            <div class="m-2">
                <img class="qrcode" src="../../img/qrcode_wx.png">
                <div class="text-center my-3">
                    扫一扫 关注我们
                </div>
            </div>
        `, 
        template :  '<div class="popover border-F6 moreWidth kindSelect border-0 bg-FB" role="tooltip"><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    
    }
    $('header .login >.d-flex >a:nth-of-type(2)').popover(wechatConfig)
    .on("mouseenter", function () {
        var _this = this;   // 这里的this触发的dom,需要存起来 否则在下面 .popover的逻辑中this会变为弹出的dom
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide'); 
        });
    })
    .on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 300);
    });

    $('header .logout').on('click',function(){
        $.ajax({
            url: "/passport/logout",
            type: "GET",
            success: function(result) {
                window.location.reload()
            },
            error(xhr,status,error){
                $.alert('network')
            }
            
        })
    })

})