(function(){
    window.already = [] 
    $('.think input').on('click',function(e){
        // console.log($(e.target).val())
        show($(e.target).next(),$(e.target).val())
    })
    $('.think input').on('input',function(e){
        // console.log($(e.target).val())
        show($(e.target).next(),$(e.target).val())
    })
    function show($dom,kw){
        // kw !== '' ?
        $.ajax({
            url:'/transfer/search-college',
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
                                // $dom.parents('.think').find('input').attr('three_code',ele.three_code)
                            }
                            str += `<li class="bg-FF p-2 pointer w-100" data_id="${ele.id}">${ele.name_of_chinese+' '+ele.name_of_english}</li>`
                            return true
                        // }
                        
                    })
                    str !== `` ? $dom.after(`
                        <ul class="think_content_car p-0 absolute left-0 rounded mt-2 z-index w-100 overflow-auto">
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
        $('.think_content_car li').on('click',function(e) {
            // var old = $(e.target).parents('.think').find('input').val()
            
            // window.already.splice(window.already.indexOf(old),1)
            $(e.target).parents('.think').find('input').removeClass('is-invalid')
            $(e.target).parents('.think').find('input').val($(e.target).text())
            $(e.target).parents('form').find('input[name="college_id"]').val($(e.target).attr('data_id'))
            $(e.target).parents('.think_content_car').remove()
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

    $('input[name=postcode_prefix]').on('change', function(e){
        replaceReg = new RegExp(/\s/, 'g');
        $(e.target).val($(e.target).val().replace(replaceReg, ''));
    })
    $('input[name=postcode_suffix]').on('change', function(e){
        replaceReg = new RegExp(/\s/, 'g');
        $(e.target).val($(e.target).val().replace(replaceReg, ''));
    })
})()