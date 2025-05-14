$('body').append(
    $(`
        <div class="toTop rounded-circle opacity-0">
            <img src="/static/img/toTop.png">
        </div>`)
)


$('.toTop').on('click',function(){
    // $('html').scrollTop(0);
    if (document.documentElement.scrollTop){
        document.documentElement.scrollTop=0;
    }
    if (document.body.scrollTop){
        document.body.scrollTop=0 ;
    }
})

window.onscroll = function(){
    showTop()
}

function showTop(){
    document.documentElement.scrollTop > 10 ? $('.toTop').css({
        opacity: 1
    }) : $('.toTop').css({
        opacity: 0
    })
}