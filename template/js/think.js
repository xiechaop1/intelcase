(function(){
    window.already = [] 
    $('.think input').on('click',function(e){
        // console.log($(e.target).val())
        show($(e.target),$(e.target).val())
    })
    $('.think input').on('input',function(e){
        // console.log($(e.target).val())
        show($(e.target),$(e.target).val())
    })
    function show($dom,kw){
        // kw !== '' ?
        $.ajax({
            url:'/ticket/search-airport',
            type:'get',
            data:{
                kw
            },
            success: function(res) {
                if(res.status){
                    var str = ``
                    console.log(res.data)
                    $dom.next().remove()
                    res.data = res.data.filter(function(ele,ind){
                        // if(window.already.indexOf(ele.name_of_chinese)!==-1){
                        //     return false
                        // }else{
                            if(ele.name_of_chinese.indexOf(kw)!==-1
                            // &&!$dom.parents('.think').find('input').attr('three_code')
                            ){
                                // $dom.parents('.think').find('input').val()
                                $dom.parents('.think').find('input').attr('three_code',ele.three_code)
                            }
                            str += `<li class="bg-FF p-2 pointer fs-14" data-nation="${ele.nation}" three_code="${ele.three_code}">
                                <div class="text-66">${ele.name_of_chinese}（${ele.three_code}）</div>
                                <div class="fs-12 text-99">${ele.nation}</div>
                            </li>`
                            return true
                        // }
                        
                    })
                    str !== `` ? $dom.after(`
                        <ul class="think_content p-0 absolute left-0 rounded mt-2 z-index">
                            ${str}
                        </ul>
                    `) : ''
                    bindEvent()
                    console.log($dom)
                } 
            }
        })
    }
    function bindEvent() {
        $('.think_content li').on('click',function(e) {
            // var old = $(e.target).parents('.think').find('input').val()
            
            // window.already.splice(window.already.indexOf(old),1)

            $(e.target).parents('.think').find('input').val($(e.target).parents('li').find('div:nth-of-type(1)').text())
            $(e.target).parents('.think').find('input').attr('three_code',$(e.target).attr('three_code'))
            $(e.target).parents('.think_content').remove()
            // window.already.push($(e.target).text())

        })
    }
    window.thinkBind = function(){
        $('.think input').on('click',function(e){
            // console.log($(e.target).val())
            show($(e.target),$(e.target).val())
        })
        $('.think input').on('input',function(e){
            // console.log($(e.target).val())
            show($(e.target),$(e.target).val())
        })
        bindEvent()
    }
})()