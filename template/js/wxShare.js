$(function () {
    console.log(wx_config.appId,wx_config.timestamp,wx_config.nonceStr,wx_config.signature)
    wx.config({
        debug : false,
        appId : wx_config.appId,
        timestamp : wx_config.timestamp,
        nonceStr : wx_config.nonceStr,
        signature : wx_config.signature,
        jsApiList : [
            'onMenuShareTimeline',
            'onMenuShareQQ',
            'onMenuShareAppMessage'
        ]
    });
    wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: config.title,
            desc: config.desc,
            link: config.href,
            imgUrl:config.img,
            complete:function(){
                $('.sharetip').hide();
            }
        });

        wx.onMenuShareTimeline({
            title:config.title,
            desc: config.desc,
            link: config.href,
            imgUrl:config.img,
            complete:function(){
                $('.sharetip').hide();
            }
        });

        wx.onMenuShareQQ({
            title: config.title,
            desc: config.desc,
            link: config.href,
            imgUrl:config.img,
            complete:function(){
                $('.sharetip').hide();
            }
        });
    });
})