$.extend({vcode:function(){
        $('.vcode').each(function(ind,ele){
            ele.wait = false
        })
        $('.vcode').unbind('click').on('click',function(){
            if(!this.wait){
                var that = this;

                var email_dom = $('input[name="email"]').parents('div[name]:not(.d-none),tab-pane:not(.active),section:not(.d-none)').find('input[name="email"]')
                var mobile_dom = $('input[name="phone"],input[name="mobile"]').closest('form').find('input[name="phone"],input[name="mobile"]')

                var email = email_dom.val()
                var mobile = mobile_dom.val()
                console.log(mobile,mobile_dom.hasClass('has-success'))
                if(mobile_dom.hasClass('has-success')&&$(this).hasClass('vcode-phone')&&!$(this).hasClass('disable')){
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
                        case "changePhone" :
                            type = '3'
                            break;
                        case "changeEmail" :
                            type = "6"
                            break;
                        case "phoneCode" :
                            type = '10';
                            break;
                        case "phoneFind" :
                            type = '2';
                            break;
                        case "registerPerson" :
                        case "registerCompany" :
                            type = '20';
                            break;
                        case "forgotPassword" :
                            type = '2';
                            break;
                    }

                    console.log('aaa')
                    sendCode.call(this,{
                        section,
                        mobile,
                        email,
                        way,
                        type
                    },callback.bind(this,that))

                    //邮箱
                }else if(email_dom.hasClass('has-success')&&$(this).hasClass('vcode-mail')){
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
                        case "emailFind" :
                            type = '2';
                            break;
                        case "changePhone" :
                            type = '4';
                            break;
                        case "changeEmail" :
                            type = '5';
                            break;
                        case "phoneCode" :
                            type = '10';
                            break;

                    }

                    //发送验证码网络请求
                    sendCode.call(this,{
                        section,
                        mobile,
                        email,
                        type,
                        way
                    },callback.bind(this,that))


                    //邮箱
                }else{
                    if($(this).hasClass('vcode-mail')){
                        alert('邮箱有误！')
                    }else if($(this).hasClass('vcode-phone')&&!$(this).hasClass('disable')){
                        alert('手机号有误！');
                        console.log('')
                    }
                    else if($(this).hasClass('vcode-phone')&&$(this).hasClass('disable')){
                        alert('请按住滑块，拖动到最右边！')
                    }


                }
            }
        })
        function callback(that){
            that.wait = true;
            var second = 60;
            $(that).html(`重新发送${second}s`);
            var timer = setInterval(()=>{
                second--;
                $(that).html(`重新发送${second}s`);
                if(second == 0){
                    $(that).html(`获取验证码`);
                    clearInterval(timer);
                    that.wait = false;
                }
            },1000)
        }
        function sendCode(option,callback){
            console.log(option)
            var that = this
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

                    if(result.status == true){
                        callback()
                        /*  if(option.type==10){
                              $("form[name='phoneCode'] #slider").slider("restore");
                              $("form[name='phoneCode'] .vcode").addClass("disable");
                          }*/

                    }else{
                        switch(option.type){
                            case '1':
                            case '3':
                                alert('regRepetition')
                                break;
                            case '2' :
                                alert(result.msg)
                                break;
                            default :
                                alert(result.msg)
                        }
                        // if(option.type ==1){
                        //     alert('regRepetition')
                        // }else if(option.type ==2){
                        //     alert(result.msg)
                        // }

                        that.wait = false;
                    }
                },
                error(xhr,status,error){
                    console.log(JSON.stringify(xhr),JSON.stringify(status),JSON.stringify(error))
                    console.log(that)
                    that.wait = false;
                    alert('发送失败')
                }

            })
        }
    }})