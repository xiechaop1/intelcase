$(function () {
    $('.toTop2').on('click',function(){
        // $('html').scrollTop(0)
        if (document.documentElement.scrollTop){
            document.documentElement.scrollTop=0;
        }
        if (document.body.scrollTop){
            document.body.scrollTop=0 ;
        }
    })

})