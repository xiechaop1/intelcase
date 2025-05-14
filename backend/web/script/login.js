(function(){

    $('.submit-form').on('click',submitHandle)
    $.vcode()
    $.bindCheck()



/*    $(document).on('click', '.submit-form',
       function(e){
           var $form = $(e.target).parents('form');
           console.log('点击submit')

           $.checkForm($form,function(){
               var name = this.attr('name').toString()
               // console.log(this.attr('name').toString())
               var self = this;
              

               switch(name){
                   case "forgotPassword" :
                       console.log('forgotPassword')
                       var data1={
                           mobile : self.find("input[name='mobile']").val(),
                           mobile_section : `${self.find("select").val()}`,
                           verification_code: self.find("input[name='vcode']").val(),
                           password:self.find("input[name='password']").val(),
                           password2:self.find("input[name='confirmPassword']").val(),
                       };
                       var data=self.serialize()
                       console.log("/passport/reset_password?"+JSON.stringify(data1))
                       $.ajax({
                           url: "/passport/reset_password",
                           type: "POST",
                           data: data,
                           error: function (XMLHttpRequest, textStatus, errorThrown) {
                               console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                           },
                           success: function(result) {
                               result.status ? window.location.href='/passport/web_login' : $.alert(result.msg)
                               if(result.status){
                                   $("#forgot-password").modal('hide');
                                   alert("重置密码成功")
                               }
                           }
                       })
                       break;


               }

           })
       }
    );*/

    function submitHandle(e){

        var $form = $(e.target).parents('form');
        console.log('点击submit')

        $.checkForm($form,function(){
            var name = this.attr('name').toString()
            // console.log(this.attr('name').toString())
            var self = this;

            switch(name){
                case "forgotPassword" :
                    console.log('forgotPassword')
                    var data1={
                        mobile : self.find("input[name='phone']").val(),
                        mobile_section : `${self.find("select").val()}`,
                        verification_code: self.find("input[name='vcode']").val(),
                        password:self.find("input[name='password']").val(),
                        password2:self.find("input[name='confirmPassword']").val(),
                    };
                    var data=self.serialize()
                    console.log("/passport/reset_password?"+JSON.stringify(data1))
                    $.ajax({
                        url: "/passport/reset_password",
                        type: "POST",
                        data: data,
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            console.log("ajax请求失败:"+JSON.stringify(XMLHttpRequest),textStatus,errorThrown);
                        },
                        success: function(result) {
                            result.status ? window.location.href='/passport/web_login' : $.alert(result.msg)
                            if(result.status){
                                $("#forgot-password").modal('hide');
                                alert("重置密码成功")
                            }
                        }
                    })
                    break;


            }

        })

    }
})();




