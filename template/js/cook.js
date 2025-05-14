let html = `
    <div class="agreeCookie fixed bottom-0 tip text-FF p-3 rounded left-0 right-0 text-center z-9999">
        We use cookies to offer better experience,do you agree us to use cookies？
        <a class="btn btn-danger agreeCookieBtn ml-3">agree</a>
        <a class="hide mr-4 pointer text-FF iconfont iconguanbi1 right-0 absolute"></a>
    </div>
`
localStorage.agreeCookie ? '' : $('body').append(html)
$('.agreeCookieBtn').on('click',function(){
    $('.agreeCookie').hide()
    localStorage.agreeCookie = true
})
$('.hide').on('click',function(){
    $('.agreeCookie').hide()
})