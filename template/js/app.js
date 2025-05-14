$(function () {
    //页面重新加载
    $(".window-reload").on('click',function () {
        window.location.reload()
    })
    //返回
    $(".go-back").on('click',function () {
        window.history.back(-1);
    })
    //打开模态框
    $(".open-modal").on('click',function () {
        var me=$(this);
        var id=me.attr('data-id');
        $("#"+id).modal({
            show:true
        });
    })

    //收起展开
    $(".show-more").on('click',function () {
        var me=$(this);
        var more_content=me.closest('.more-box').find('.more-content');
        if(more_content.hasClass('h-m-auto')){
            more_content.removeClass('h-m-auto');
            me.closest('.more-box').find('.more-text').empty().text('展开全部');
            me.closest('.more-box').find('.show-more-icon').attr('src','../../static/image/job/icon_down.png');

        }else{
            more_content.addClass('h-m-auto');
            me.closest('.more-box').find('.more-text').empty().text('收起');
            me.closest('.more-box').find('.show-more-icon').attr('src','../../static/image/job/icon_up.png');
        }
    })

    //联系方式隐藏
    $(".contact-show").on('click',function () {
        $(".contact-item-hide").addClass('d-none');
        $(".contact-item-show").removeClass('d-none');
    })
    //联系方式显示
    $(".contact-hide").on('click',function () {
        $(".contact-item-hide").removeClass('d-none');
        $(".contact-item-show").addClass('d-none');
    })

})