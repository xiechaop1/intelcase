
var toTop=`<label class="toTop">
             <img src="../../static/image/h5/job/to-top.png" class="img-no-hover img-100">
          </label>`;

$("body").append(toTop);
$('.toTop').css({
    opacity: 0
});


$('.toTop').on('click',function(){
    // $('html').scrollTop(0)
    if (document.documentElement.scrollTop){
        document.documentElement.scrollTop=0;
    }
    if (document.body.scrollTop){
        document.body.scrollTop=0 ;
    }
})

window.onscroll = function(){
    showTop();
}

function showTop(){
    const el = document.scrollingElement || document.documentElement;
    console.log(el.scrollTop)
    el.scrollTop > 100 ? $('.toTop').css({
        opacity: 1
    }) : $('.toTop').css({
        opacity: 0
    });
}


