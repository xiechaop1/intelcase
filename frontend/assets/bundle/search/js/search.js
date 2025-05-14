$('.search input').on('focus',function(e){
    var arr = ''
    localStorage.historySearch ? arr = localStorage.historySearch.split('_,') : ''
    
    Array.isArray(arr) ? arr.shift() : ''
    Array.isArray(arr) ? arr.reverse() : ''
    $('.think').html('')
    localStorage.historySearch ? $('.think').css({
        opacity: '1',
        zIndex: '99'
    }) : ''
    Array.isArray(arr) ? arr.forEach(function(ele,ind){
        $('.think').append(`<a class="pointer list-group-item list-group-item-action p-2 text-33 fs-14">${ele}</a>`)
    }) : ''
    bindEvent()
})
$('.search input').on('blur',function(e){
    setTimeout(function(){
        $('.think').css({
            opacity: '0',
            zIndex: '-1'
        })
    },200)
})
function bindEvent(){
    $('.think a').on('click',function(e){
        $('input[name="word"]').val($(e.currentTarget).html())
        $('form[name="search"]').submit()
    })
}

$('form[name="search"]').on('submit',function(){
    if(localStorage.agreeCookie){
        (!localStorage.historySearch || localStorage.historySearch.indexOf('_,'+$('input[name="kw"]').val()) == -1) ?
        localStorage.historySearch = localStorage.historySearch + "_," + $('input[name="kw"]').val(): ''
    }
    
})
$('a.submit').on('click',function(){
    $('form[name="search"]').submit()
})


