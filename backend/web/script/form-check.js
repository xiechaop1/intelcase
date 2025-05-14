(function(){
    $.extend(
        {
            bindCheck:function(){
                $('input').each((ind,ele)=>{
                    $(ele).on('blur',function(){
                        switchVal(this)
                    })
                    $(ele).on('focus',function(){
                        $(this).removeClass('has-error')
                        $(this).removeClass('has-success')
                    })

                })
                $('select').on('change',function(){
                    switchVal(this)
                })
                $('textarea').bind('input propertychange',function(){
                    switchVal(this)
                })
                $('.required').on('change',function(){
                    switchVal(this)
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
                    if($(ele).hasClass('has-error')){
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
                $(ele).val() === '' ?  $(ele).addClass('has-error') : $(ele).addClass('has-success')
                break;
            case 'old_password' :
            case 'password' :
                $(ele).val().length < 6 ? $(ele).addClass('has-error') : $(ele).addClass('has-success')
                break;
            case 'phone' :
            case 'mobile' :
                var sections = $(ele).parents('form').find('select[name="sections"],select[name="mobile_section"],select[name="mobileSection"]').val();
                console.log(sections)
                switch(sections){
                    case '+86' :
                    case '86' :
                        /^1[345789]\d{9}$/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                        break;
                    case '+44' :
                    case '44' :
                        console.log($(ele).val());
                        /^[07||7](\d*)/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                        break;
                    case '853' :
                    case '+853' :
                        console.log($(ele).val());
                        /^[1][3-8]\d{9}$|^([6|9])\d{7}$|^[0][9]\d{8}$|^6\d{5}$/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                        break;
                    case '852' :
                    case '+852' :
                        console.log($(ele).val());
                        /^(5|6|8|9)\\d{7}$/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                        break;
                    case '886' :
                    case '+886' :
                        console.log($(ele).val());
                        /^(\+886\s)?[0]{1}[9]{1}\d{8}$/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                        break;

                }
                break;

            // $(ele).val() === $('input[id = password]').val() && $(ele).val().length >= 6 ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
            // break;
            case 'email_verification_code' :
            case 'mobile_verification_code' :
            case 'vcode' :
                $(ele).val() === '' ? $(ele).addClass('has-error') : $(ele).addClass('has-success')
                break;
            case 'name' :
            case 'passenger_name' :
            case 'realname' :
                $(ele).val() === '' ? $(ele).addClass('has-error') : $(ele).addClass('has-success')
                break;
            case 'new_password' :
            case 'oldPassword' :
                $(ele).val().length < 6 ? $(ele).addClass('has-error') : $(ele).addClass('has-success')
                $(ele).val() !== $(ele).parents('form').find('input[name="newPassword"],input[name="comfirm"]').val() && $(ele).parents('form').find('input[name="newPassword"],input[name="comfirm"]').val() !=='' ? $(ele).parents('form').find('input[name="newPassword"],input[name="comfirm"]').addClass('has-error') : ''
                break;
            case 'comfirm' :
            case 'newPassword' :
                $(ele).val() === '' || $(ele).val() !== $(ele).parents('form').find('input[name="oldPassword"],input[name="new_password"]').val() ? $(ele).addClass('has-error') : $(ele).addClass('has-success')
                break;
            case 'confirmPassword' :
                $(ele).val() === '' || $(ele).val() !== $(ele).parents('form').find('input[name="password"]').val() ? $(ele).addClass('has-error') : $(ele).addClass('has-success')
                break;
            case 'email' :
                /^[\w.\-]+@(?:[a-z0-9]+(?:-[a-z0-9]+)*\.)+[a-z]{2,3}$/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                break;
            case 'account' :
                /^[a-zA-Z0-9]{9,}$/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                break;
            case 'mustCheck' :
                $(ele)[0].checked ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                $(ele)[0].checked ? $(ele).removeClass('has-error') : $(ele).removeClass('has-success')
                break;
            case 'sections':
            case 'mobileSection' :
            case 'mobile_section' :
                var sections = $(ele).val();
                console.log(/^1[345789]\d{9}$/.test($(ele).parents('form').find('input[name="mobile"]').val()))
                switch(sections){
                    case '+86' :
                    case '86' :
                        /^1[345789]\d{9}$/.test($(ele).parents('form').find('input[name="mobile"],input[name="phone"]').val()) ? $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').removeClass('has-error') : $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').addClass('has-error')
                        break;
                    case '+44' :
                    case '44' :
                        /^[07||7](\d*)/.test($(ele).parents('form').find('input[name="mobile"],input[name="phone"]').val()) ? $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').removeClass('has-error') : $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').addClass('has-error')
                        break;

                }
                break;
            case 'userName' :
                $(ele).val() === '' ?  $(ele).removeClass("has-success").addClass('has-error') : $(ele).removeClass("has-error").addClass('has-success')
                break;
            case 'ID_num' :
                /^[1-9]\d{5}(18|19|20|(3\d))\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test($(ele).val()) ? $(ele).addClass('has-success') : $(ele).addClass('has-error')
                break;
            case 'true_name':
                /^[\S]{1,5}$/.test($(ele).val()) ? $(ele).removeClass("has-error").addClass('has-success'):$(ele).removeClass("has-success").addClass('has-error')
                break;
            case 'true_name':
                /^[\S]{1,5}$/.test($(ele).val()) ? $(ele).removeClass("has-error").addClass('has-success'):$(ele).removeClass("has-success").addClass('has-error')
                break;
            case 'salary_min':
                var maxSalary=parseInt($("input[name='salary_max']").val());
                var minSalary=parseInt($("input[name='salary_min']").val());
                if(maxSalary<minSalary){
                    $("input[name='salary_max']").removeClass('has-success').addClass('has-error')
                }
                break;
            case 'salary_max':
                var maxSalary=parseInt($("input[name='salary_max']").val());
                var minSalary=parseInt($("input[name='salary_min']").val());
                if(maxSalary<minSalary){
                    $("input[name='salary_max']").removeClass('has-success').addClass('has-error')
                }
                break;
            case 'customer_fee_way':
                var way=$("select[name='customer_fee_way'] option:checked").val();

                if(way=='一期'){
                    $("#customer_fee_way_detail").addClass("d-none")
                }
                else{
                    $("#customer_fee_way_detail").removeClass("d-none")
                }
                break;
            case 'qa':
                $(ele).val().length<201?$(ele).removeClass("has-error").addClass('has-success'):$(ele).removeClass("has-success").addClass('has-error')
                break;
            case 'salary':
                var divideAmount;
                var rate=parseFloat($(ele).parents('form').find('.modal-publish-change-status').attr('data-rate'));
                var divide=parseInt($(ele).parents('form').find('.modal-publish-change-status').attr('data-divide'));

                divide==1?divideAmount=(parseFloat($(ele).val())*rate).toFixed(2):divideAmount=(parseFloat($(ele).val())).toFixed(2);
                $.trim($(ele).val()).length>0?divideAmount=divideAmount:divideAmount='0.00';

                $(ele).closest('section').find("input[name='estimated_share']").val(divideAmount);

                break;
            case 'share_rate':
                var rate=parseFloat($(ele).val())
                if(rate>=0&&rate<=100){
                    $(ele).removeClass("has-error")
                }
                else{
                    $(ele).removeClass("has-success").addClass('has-error')
                }
                break;

            case 'customer_fee':
                var type=parseInt($(ele).parents('form').find("select[name='customer_fee_type'] option:checked").val());

                if(type==1){
                    var rate=parseFloat($(ele).val())
                    if(rate>=0&&rate<=100){
                        $("input[name='customer_fee']").removeClass("has-error")
                    }
                    else{
                        $("input[name='customer_fee']").removeClass("has-success").addClass('has-error')
                    }
                }
                else if(type==2){
                    var rate=parseFloat($(ele).val())
                    if(rate>=0){
                        $("input[name='customer_fee']").removeClass("has-error")
                    }
                    else{
                        $("input[name='customer_fee']").removeClass("has-success").addClass('has-error')
                    }
                }

                break;






        }
        if($(ele).attr('required')||$(ele).attr('require')||$(ele).hasClass("required")){
            $(ele).val() === '' ?  $(ele).removeClass("has-success").addClass('has-error') : $(ele).removeClass("has-error").addClass('has-success')
        }

    }
})()