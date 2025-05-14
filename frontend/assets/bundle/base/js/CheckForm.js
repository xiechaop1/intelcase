(function(){
    $.extend(
    {
        bindCheck:function(){
        $('input').each((ind,ele)=>{
            $(ele).on('blur',function(){
                switchVal(this)
                    
            })
            $(ele).on('focus',function(){
                $(this).removeClass('is-invalid')
                $(this).removeClass('is-valid')
            })
            
        })
    }
    })
    $.extend(
        {
            checkForm : function(form,callBack){
            $(form).find('input,select,textarea').each((ind,ele)=>{
                switchVal(ele)
            })
            var bool = Array.from($(form).find('input,select,textarea')).every(function(ele,ind){
                if($(ele).hasClass('is-invalid')){
                    return false
                }else{
                    return true;
                }
            })
            bool ? callBack.call(form) : ''
        }
    })

    function switchVal(ele){
        switch(ele.name){
            case 'id' :
                $(ele).val() === '' ?  $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'password' : 
                $(ele).val().length < 6 ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'phone' :
                /^1[34578]\d{9}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;
            case 'comfirm' : 
                $(ele).val() === $('input[id = password]').val() && $(ele).val().length >= 6 ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;
            case 'vcode' : 
                $(ele).val() === '' ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')   
                break;
            case 'name' : 
                $(ele).val() === '' ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'oldPassword' : 
                $(ele).val().length < 6 ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                $(ele).val() !== $(ele).parents('form').find('input[name="newPassword"]').val() && $(ele).parents('form').find('input[name="newPassword"]').val() !=='' ? $(ele).parents('form').find('input[name="newPassword"]').addClass('is-invalid') : ''
                break;
            case 'newPassword' : 
                $(ele).val() === '' || $(ele).val() !== $(ele).parents('form').find('input[name="oldPassword"]').val() ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'email' :
                /^[\w.\-]+@(?:[a-z0-9]+(?:-[a-z0-9]+)*\.)+[a-z]{2,3}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;
            case 'mustCheck' : 
                $(ele)[0].checked ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;
        }
        
    }
})()