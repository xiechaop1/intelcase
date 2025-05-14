$.extend({vcode:function(){
    $('.vcode').each(function(ind,ele){
        ele.wait = false
    })
    $('.vcode').on('click',function(){
        if(!this.wait){
            var that = this;
            var email = $('input[name="email"]').parents('div[name]:not(.d-none),tab-pane:not(.active)').find('input[name="email"]').val()
            var mobile = $('input[name="phone"]').parents('div[name]:not(.d-none),tab-pane:not(.active)').find('input[name="phone"]').val()
            if(/^1[34578]\d{9}$/.test(mobile)&&$(this).hasClass('vcode-phone')){
                //等待验证码时间
                this.wait = true;

                //发送验证码网络请求
                var name = $(this).parents('form').attr('name')
                var section = `${$(this).parents('form').find('select').val()}`;
                var way = 'mobile';
                var type = '';
                var email = ''
                
                switch(name){
                    case "phoneReg" : 
                        type = '1';
                        break;
                    case "phoneFind" : 
                        type = '2';
                        break;
                }

                console.log('aaa')
                sendCode({
                    section,
                    mobile,
                    email,
                    way,
                    type
                },callback.bind(this,that))

                //邮箱
            }else if(/^[\w.\-]+@(?:[a-z0-9]+(?:-[a-z0-9]+)*\.)+[a-z]{2,3}$/.test(email)&&$(this).hasClass('vcode-mail')){
                //等待验证码时间
                this.wait = true;

                var name = $(this).parents('form').attr('name')

                var section = '';
                var way = 'email';
                var type = '';
                var mobile = '';

                switch(name){
                    case "emailReg" : 
                        type = '1';
                        break;
                    case "emailFind" :{
                        type = '2';
                        break;
                    }
                }

                //发送验证码网络请求
                sendCode({
                    section,
                    mobile,
                    email,
                    type,
                    way
                },callback.bind(this,that))

                
                //邮箱
            }else{
                if($(this).hasClass('vcode-mail')){
                    $.alert('emailErr')
                }else if($(this).hasClass('vcode-phone')){
                    $.alert('phoneErr')
                }
                
            }
        }
    })  
    function callback(that){
        var second = 60;
        $(that).html(`重新发送${second}s`);
        var timer = setInterval(()=>{
            second--;
            $(that).html(`重新发送${second}s`);
            if(second == 0){
                that.wait = false;
                $(that).html(`获取验证码`);
                clearInterval(timer)
            }
        },1000)
    }
    function sendCode(option,callback){
        console.log(option)
        $.ajax({
            url: "/passport/verification-code",
            type: "POST",
            data: {
                way : option.way,
                type : option.type,
                mobile_section : option.section,
                mobile : option.mobile,
                email : option.email,
            },
            success: function(result) {
                console.log(result)
                
                result.status == true ? callback() : $.alert('regRepetition')
            },
            error(xhr,status,error){
                $.alert('sendErr')
            }
            
        })
    }
}})