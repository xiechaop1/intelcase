(function(){
    $.extend(
    {
        bindCheck:function(){
        $('input').each((ind,ele)=>{
            $(ele).on('blur',function(){
                switchVal(this)
            })
            $(ele).on('change',function(){
                switchVal(this)
            })
            $(ele).on('focus',function(){
                $(this).removeClass('is-invalid')
                $(this).removeClass('is-valid')
            })
            
        })
        $('select').on('change',function(){
            switchVal(this)
        })
            $("[name='industry'],[name='share_rate']").on('change',function(){
                switchVal(this)
            })
            $('textarea').bind('input propertychange',function(){
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
                if($(ele).hasClass('is-invalid')){
                    return false
                }else{
                    return true;
                }
            })
            //bool ? callBack.call(form) : $.alert("请检查填写内容");
            bool ? callBack.call(form) : console.log("请检查填写内容");
        }
    })

    function switchVal(ele){

        if($(ele).attr('required')||$(ele).attr('require')||$(ele).hasClass("required")||$(ele).hasClass("require")){
            $(ele).val() === '' ?  $(ele).addClass('is-invalid').removeClass('is-valid') : $(ele).addClass('is-valid').removeClass('is-invalid')
        }

        if($(ele).hasClass("max1000")){


            var len=$(ele).val().length;
            console.log("wordLen:"+len)

            if(len>1000){
                $(ele).closest('div').find('.word-total').text(len).addClass('text-red');
                $(ele).addClass('is-invalid').removeClass('is-valid')
            }
            else if(len==0){
                $(ele).closest('div').find('.word-total').text('0').removeClass('text-red');
                $(ele).addClass('is-invalid').removeClass('is-valid')
            }
            else{
                $(ele).closest('div').find('.word-total').text(len).removeClass('text-red');
                $(ele).removeClass('is-invalid').addClass('is-valid')
            }
        }


        switch(ele.name){
            case 'id' :
                $(ele).val() === '' ?  $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'old_password' :
            case 'password' :
                $(ele).val().length < 6 ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'phone' :
            case 'mobile' :
                var sections = $(ele).parents('form').find('select[name="sections"],[name="mobile_section"],select[name="mobileSection"],[name="sections"]').val();
                console.log(sections)
                switch(sections){
                    case '+86' :
                    case '86' :
                        /^1[345789]\d{9}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                        break;
                    case '+44' :
                    case '44' :
                        console.log($(ele).val());
                        /^[07||7](\d*)/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                        break;
                    case '853' :
                    case '+853' :
                        console.log($(ele).val());
                        /^[1][3-8]\d{9}$|^([6|9])\d{7}$|^[0][9]\d{8}$|^6\d{5}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                        break;
                    case '852' :
                    case '+852' :
                        console.log($(ele).val());
                        /^(5|6|8|9)\\d{7}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                        break;
                    case '886' :
                    case '+886' :
                        console.log($(ele).val());
                        /^(\+886\s)?[0]{1}[9]{1}\d{8}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                        break;

                }
                break;
            case 'target_user_mobile' :
                /^1[345789]\d{9}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;

                // $(ele).val() === $('input[id = password]').val() && $(ele).val().length >= 6 ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                // break;
            case 'email_verification_code' :
            case 'mobile_verification_code' :
            case 'vcode' :
                $(ele).val() === '' ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'name' :
            case 'passenger_name' :
            case 'realname' :
                $(ele).val() === '' ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'new_password' :
            case 'oldPassword' :
                $(ele).val().length < 6 ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                $(ele).val() !== $(ele).parents('form').find('input[name="newPassword"],input[name="comfirm"]').val() && $(ele).parents('form').find('input[name="newPassword"],input[name="comfirm"]').val() !=='' ? $(ele).parents('form').find('input[name="newPassword"],input[name="comfirm"]').addClass('is-invalid') : ''
                break;
            case 'comfirm' :
            case 'newPassword' :
                $(ele).val() === '' || $(ele).val() !== $(ele).parents('form').find('input[name="oldPassword"],input[name="new_password"]').val() ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'confirmPassword' :
                $(ele).val() === '' || $(ele).val() !== $(ele).parents('form').find('input[name="password"]').val() ? $(ele).addClass('is-invalid') : $(ele).addClass('is-valid')
                break;
            case 'mail' :
            case 'email' :
                /^[\w.\-]+@(?:[a-z0-9]+(?:-[a-z0-9]+)*\.)+[a-z]{2,3}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;
            case 'account' :
                /^[a-zA-Z0-9]{9,}$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;
            case 'contract-confirm-agree' :
            case 'mustCheck' :
                $(ele)[0].checked ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                $(ele)[0].checked ? $(ele).removeClass('is-invalid') : $(ele).removeClass('is-valid')
                break;
            case 'sections':
            case 'mobileSection' :
            case 'mobile_section' :
                var sections = $(ele).val();
                console.log(/^1[345789]\d{9}$/.test($(ele).parents('form').find('input[name="mobile"]').val()))
                switch(sections){
                    case '+86' :
                    case '86' :
                        /^1[345789]\d{9}$/.test($(ele).parents('form').find('input[name="mobile"],input[name="phone"]').val()) ? $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').removeClass('is-invalid') : $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').addClass('is-invalid')
                        break;
                    case '+44' :
                    case '44' :
                        /^[07||7](\d*)/.test($(ele).parents('form').find('input[name="mobile"],input[name="phone"]').val()) ? $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').removeClass('is-invalid') : $(ele).parents('form').find('input[name="mobile"],input[name="phone"]').addClass('is-invalid')
                        break;

                }
                break;
            case 'userName' :
                $(ele).val() === '' ?  $(ele).removeClass("is-valid").addClass('is-invalid') : $(ele).removeClass("is-invalid").addClass('is-valid')
                break;
            case 'ID_num' :
                /^[1-9]\d{5}(18|19|20|(3\d))\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                break;
            case 'true_name':
                /^[\S]{1,5}$/.test($(ele).val()) ? $(ele).removeClass("is-invalid").addClass('is-valid'):$(ele).removeClass("is-valid").addClass('is-invalid')
                break;
            case 'true_name':
                /^[\S]{1,5}$/.test($(ele).val()) ? $(ele).removeClass("is-invalid").addClass('is-valid'):$(ele).removeClass("is-valid").addClass('is-invalid')
                break;
            case 'salary_min':
                var maxSalary=parseInt($("input[name='salary_max']").val());
                var minSalary=parseInt($("input[name='salary_min']").val());
                if(maxSalary<minSalary){
                    $("input[name='salary_max']").removeClass('is-valid').addClass('is-invalid')
                }
                break;
            case 'salary_max':
                var maxSalary=parseInt($("input[name='salary_max']").val());
                var minSalary=parseInt($("input[name='salary_min']").val());
                if(maxSalary<minSalary){
                    $("input[name='salary_max']").removeClass('is-valid').addClass('is-invalid')
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
                $(ele).val().length<201?$(ele).removeClass("is-invalid").addClass('is-valid'):$(ele).removeClass("is-valid").addClass('is-invalid')
                break;
            case 'salary':
                var divideAmount;
                var rate=parseFloat($(ele).parents('form').find('.modal-publish-change-status').attr('data-rate'));
                var divide=parseInt($(ele).parents('form').find('.modal-publish-change-status').attr('data-divide'));

                divide==1?divideAmount=(parseFloat($(ele).val())*rate).toFixed(2):divideAmount=(parseFloat($(ele).val())).toFixed(2);
                $.trim($(ele).val()).length>0?divideAmount=divideAmount:divideAmount='0.00';

                $(ele).closest('section').find("input[name='estimated_share']").val(divideAmount);

                break;
            // case 'customer_fee_type':

            case 'share_rate':
       /*         var rate=parseFloat($(ele).val())
                if(rate>=0&&rate<=100){
                    $(ele).removeClass("is-invalid")
                }
                else{
                    $(ele).removeClass("is-valid").addClass('is-invalid')
                }
*/
                var type= parseInt($("[name='customer_fee_type']:checked").val());



                var title=$("input[name='share_rate']").closest(".form-group").find(".title");
                var unit=$("input[name='share_rate']").closest(".form-group").find(".unit");

                var rate=parseFloat($("input[name='share_rate']").val());
                console.log("type:"+type,"rate:"+rate)

                if(type==1){
                    $("input[name='share_rate']").attr("placeholder","请填写与合同相符的收费比例");
                    $("input[name='share_rate']").closest(".form-group").find(".invalid-feedback").empty().text("比例需在50到100之间");

                    if(rate>=50&&rate<=100){
                        $("input[name='share_rate']").removeClass("is-invalid")
                    }
                    else{
                        //$("input[name='customer_fee']").removeClass("is-valid").addClass('is-invalid')
                       /// $("input[name='share_rate']").val('50');
                        $("input[name='share_rate']").removeClass("is-valid").addClass("is-invalid")
                    }


                }else if(type==2){

                    $("input[name='share_rate']").attr("placeholder","请填写与合同相符的收费金额");
                    $("input[name='share_rate']").closest(".form-group").find(".invalid-feedback").empty().text("金额需大于等于0");


                    if(rate>0){
                        $("input[name='share_rate']").removeClass("is-invalid")
                    }
                    else{
                        $("input[name='share_rate']").removeClass("is-valid").addClass('is-invalid')
                    }

                }

                break;
                break;

            case 'industry':
                var len=$("input[name='industry']:checked").length
                if(len>0){
                    $(ele).closest(".item-input-right,.industry-invalid").removeClass("is-invalid");
                    $("input[name='industry']").removeClass('is-invalid')
                }
                else{
                    $(ele).closest(".item-input-right,.industry-invalid").removeClass("is-valid").addClass('is-invalid')
                    $("input[name='industry']").removeClass("is-valid").addClass('is-invalid')
                }
                break;
            case 'bank_card_no':
                var no=$(ele).val();
                console.log("卡号校验结果"+luhnCheck(no))
             /*   if(luhnCheck(no)){
                    $(ele).removeClass("is-invalid").addClass('is-valid')
                }
                else{
                    $(ele).removeClass("is-valid").addClass('is-invalid')
                }
*/
                // $(ele).val().length>15?$(ele).removeClass("is-invalid").addClass('is-valid'):$(ele).removeClass("is-valid").addClass('is-invalid')

                break;
            case 'customer_fee_rate':
                var rate=$(ele).val();
                rate=parseFloat(rate);
                if(rate>100){
                    $(ele).val('100')
                    $.alert("最大按税前年薪的100%支付服务费用")
                }



                break;
            case 'customer_fee_money':
                if($(ele).hasClass('require')){
                    $(ele).val()>=1000?$(ele).removeClass("is-invalid").addClass('is-valid'):$(ele).removeClass("is-valid").addClass('is-invalid')
                }
                else{
                    $(ele).removeClass("is-invalid")
                }
                console.log($(ele).val());
                break;

            case 'license_no':
                /(^(?:(?![IOZSV])[\dA-Z]){2}\d{6}(?:(?![IOZSV])[\dA-Z]){10}$)|(^\d{15}$)/.test($(ele).val()) ? $(ele).addClass('is-valid') : $(ele).addClass('is-invalid')
                
                break;



        }


        
    }


//银行卡号码检测
    function luhnCheck(bankno) {
        var lastNum = bankno.substr(bankno.length - 1, 1); //取出最后一位（与luhn进行比较）
        var first15Num = bankno.substr(0, bankno.length - 1); //前15或18位
        var newArr = new Array();
        for (var i = first15Num.length - 1; i > -1; i--) { //前15或18位倒序存进数组
            newArr.push(first15Num.substr(i, 1));
        }
        var arrJiShu = new Array(); //奇数位*2的积 <9
        var arrJiShu2 = new Array(); //奇数位*2的积 >9
        var arrOuShu = new Array(); //偶数位数组
        for (var j = 0; j < newArr.length; j++) {
            if ((j + 1) % 2 == 1) { //奇数位
                if (parseInt(newArr[j]) * 2 < 9) arrJiShu.push(parseInt(newArr[j]) * 2);
                else arrJiShu2.push(parseInt(newArr[j]) * 2);
            } else //偶数位
                arrOuShu.push(newArr[j]);
        }

        var jishu_child1 = new Array(); //奇数位*2 >9 的分割之后的数组个位数
        var jishu_child2 = new Array(); //奇数位*2 >9 的分割之后的数组十位数
        for (var h = 0; h < arrJiShu2.length; h++) {
            jishu_child1.push(parseInt(arrJiShu2[h]) % 10);
            jishu_child2.push(parseInt(arrJiShu2[h]) / 10);
        }

        var sumJiShu = 0; //奇数位*2 < 9 的数组之和
        var sumOuShu = 0; //偶数位数组之和
        var sumJiShuChild1 = 0; //奇数位*2 >9 的分割之后的数组个位数之和
        var sumJiShuChild2 = 0; //奇数位*2 >9 的分割之后的数组十位数之和
        var sumTotal = 0;
        for (var m = 0; m < arrJiShu.length; m++) {
            sumJiShu = sumJiShu + parseInt(arrJiShu[m]);
        }

        for (var n = 0; n < arrOuShu.length; n++) {
            sumOuShu = sumOuShu + parseInt(arrOuShu[n]);
        }

        for (var p = 0; p < jishu_child1.length; p++) {
            sumJiShuChild1 = sumJiShuChild1 + parseInt(jishu_child1[p]);
            sumJiShuChild2 = sumJiShuChild2 + parseInt(jishu_child2[p]);
        }
        //计算总和
        sumTotal = parseInt(sumJiShu) + parseInt(sumOuShu) + parseInt(sumJiShuChild1) + parseInt(sumJiShuChild2);

        //计算luhn值
        var k = parseInt(sumTotal) % 10 == 0 ? 10 : parseInt(sumTotal) % 10;
        var luhn = 10 - k;

        if (lastNum == luhn) {
            //$("#banknoInfo").html("luhn验证通过");
            return true;
        } else {
           // $("#banknoInfo").html("银行卡号必须符合luhn校验");
            return false;
        }
    }
})()