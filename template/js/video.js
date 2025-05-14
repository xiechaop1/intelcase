$(function () {
    window.onload=function (){
        $(".video-player:first").removeClass("hide");
        $(".videolist2:first").addClass("active");
        // $("#video1").attr("autoplay",true);
    };

    $('.videolist,.videolist2').each(function(){ //遍历视频列表
        $(this).hover(function(){ //鼠标移上来后显示播放按钮
            $(this).find('.videoed').show();
        },function(){
            $(this).find('.videoed').hide();
        });
        $(this).click(function(){ //这个视频被点击后执行
            var me=$(this);
            $('.videolist,.videolist2').removeClass("active");
            me.addClass("active");
            var img = $(this).attr('vpath');//获取视频预览图
            var video = $(this).attr('ipath');//获取视频路径

            // $('.videos').html("<video class='video_player_box' id=\"video\" poster='"+img+"' src='"+video+"' preload=\"auto\" controls=\"controls\" autoplay=\"autoplay\"></video><img onClick=\"close1()\" class=\"vclose hide\" src=\"mp4/gb.png\" width=\"25\" height=\"25\"/>");
            //
            $('.videos').html("");
            $('.videos').html("<video class='video_player_box' id=\"video\" poster='"+img+"' src='"+video+"' preload=\"auto\" controls=\"controls\" autoplay=\"autoplay\"></video>");

            $('.videos').show();
        });
    });

    function close1(){
        var v = document.getElementById('video');//获取视频节点
        $('.videos').hide();//点击关闭按钮关闭暂停视频
        v.pause();
        $('.videos').html();
    }

});