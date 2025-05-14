//价格初始化
var $vat = $('.vat')

var $cny_vat = $('.cny_vat')

var vat = $vat.text()

var cny_vat = $cny_vat.text()


var original = parseFloat($('.allPrice').text())

var cny_original = parseFloat($('.cny_allPrice').text())

if($('.vat').length>0){
    original_price = parseFloat($('.allPrice').text())*100 + parseFloat($vat.text())*100;
    cny_original_price = parseFloat($('.cny_allPrice').text())*100+parseFloat($cny_vat.text())*100;

    if ($('.expeditedFee').length>0) {
        var expedited_fee = parseFloat($('.expeditedFee').text())
        original_price = original_price + expedited_fee * 100;
        cny_original_price = cny_original_price + expedited_fee * 100;
    }
    original = ((original_price)/100).toFixed(2);

    cny_original = ((cny_original_price)/100).toFixed(2);

}




$('form input[type="radio"]').on('change',function(e){
    var id = $(e.target).attr('id')
    handle.call(this,id,false)
})
$('.use').on('click',function(e){

    handle.call(this,$(e.target).parents('div[name]').attr('name'),true)
})
$('#discounts')[0].checked ? handle('discounts') : ''
$('#balance')[0].checked ? handle('balance') : ''
function handle(id,noalert){
   
    var inputVal = ''
    var order_type = getOrderType()
    var derate_type = ''
    switch(id){
        case 'useDiscounts' :
        case 'discounts' :
            $('div[name="useDiscounts"] input').attr('name','derate')
            $('div[name="useBalance"] input').attr('name','no')

            $('div[name="useDiscounts"]').removeClass('d-none')
            $('div[name="useDiscounts"]').addClass('d-flex')

            $('div[name="useBalance"]').addClass('d-none')
            $('div[name="useBalance"]').removeClass('d-flex')
            inputVal = $('div[name="useDiscounts"] input[name="derate"]').val()
            derate_type = 1

            $('.discounts_type').text('优惠金额：')
            $('.noDiscounts').removeClass('d-none')
            break;
        case 'useBalance' :
        case 'balance' :
            $('div[name="useDiscounts"] input').attr('name','no')
            $('div[name="useBalance"] input').attr('name','derate')

            $('div[name="useDiscounts"]').addClass('d-none')
            $('div[name="useDiscounts"]').removeClass('d-flex')

            $('div[name="useBalance"]').addClass('d-flex')
            $('div[name="useBalance"]').removeClass('d-none')
            inputVal = $('div[name="useBalance"] input[name="derate"]').val()
            if(parseFloat(inputVal)>5){
                inputVal = 5
            }
            derate_type = 2

            $('.discounts_type').text('余额优惠：')
            $('.noDiscounts').removeClass('d-none')
            break;
        case 'no' :
            $('div[name="useDiscounts"]').addClass('d-none')
            $('div[name="useDiscounts"]').removeClass('d-flex')

            $('div[name="useBalance"]').removeClass('d-flex')
            $('div[name="useBalance"]').addClass('d-none')
            $('.finial').text(original)

            $('.minus').text(0)
            
            $('.cny_minus').text(0)

            $('.cny_finial').text(cny_original)

            $vat.text(vat)

            $cny_vat.text(cny_vat)

            $('.discounts_type').text('')
            $('.noDiscounts').addClass('d-none')
            return ;
    }
    var data = {}
    data.derate = inputVal
    data.order_type = order_type
    data.derate_type = derate_type
    data.quan_code = inputVal
    reducePrice(data,noalert)
}
function getOrderType(){
    // var order_type = window.location.pathname.split('/')[1]
    // switch(order_type){
    //     case 'marry':
    //         order_type = 4
    //         break;
    //     case ''
    //     default :
    //         order_type = 4
    // }
    var order_type = $('input[name="type"]').val()
    return order_type
}
function reducePrice(data,noalert=false){
    $.ajax({
        url : '/order/derate',
        type : 'get',
        dataType : 'json',
        data : {
            item_id:$('input[type=hidden][name="item_id"]').val(),
            order_type : data.order_type,
            derate_type :data.derate_type,
            derate : data.derate? data.derate :0,
            quan_code : data.quan_code
        },
        success: function(result) {
            if(result.status){
                var min = result.data.derate == '' ? 0 : result.data.derate

                var cny_min = result.data.derate_cny == '' ? 0 : result.data.derate_cny

                

                $('.minus').text(min)

                $('.cny_minus').text(cny_min)

                var fin = (original*100 - parseFloat(min)*100)/100

                var cny_fin = (cny_original*100 - parseFloat(cny_min)*100)/100

                if(result.data.vat !== null){
                    $vat.text(result.data.vat)

                    $cny_vat.text(result.data.vat_cny)


                    fin = parseFloat($('.allPrice').text())*100 + parseFloat(result.data.vat)*100 - parseFloat(min)*100
                    cny_fin =  parseFloat($('.cny_allPrice').text())*100 + parseFloat(result.data.vat_cny)*100 - parseFloat(cny_min)*100

                    if ($('.expeditedFee').length>0) {
                        var expedited_fee = parseFloat($('.expeditedFee').text())
                        fin = fin + expedited_fee * 100;
                        cny_fin = cny_fin + expedited_fee * 100;
                    }

                    fin = (fin /100).toFixed(2)

                    cny_fin = (cny_fin /100).toFixed(2)
                }

                $('.finial').text(fin)

                $('.cny_finial').text(cny_fin)

                if(result.data.derate>=0){
                    if(result.data.derate<=5){
                        $('input[name="derate"][type="number"]').val(result.data.derate)
                    }else{
                        $('input[name="derate"][type="number"]').val(5)
                    }
                }
            }else{
                if(noalert){
                    switch(result.code){
                        case 1000 :
                            $.alert('balanceErr')
                                break;
                        case 404 :
                            $.alert('discountsErr')
                                break;
                        case 403 :
                            $.alert('useless')
                                break;
                        case 400 :
                            $.alert('used')
                                break;
                    }
                }

                $vat.text(vat)

                $cny_vat.text(cny_vat)

                $('.finial').text(original)

                $('.cny_finial').text(cny_original)
                // $('input[name="derate"]').val(0)

                $('.minus').text('0.00')

                $('.cny_minus').text('0.00')
            }
            // $('div[name="useDiscounts"] input[name="derate"]').val('')
           
        }
    })
}
$('#create-order').on('submit',function(){
    var isSubmit = false
    $.checkForm($(this),function(){
        isSubmit = true
    })
    console.log(isSubmit)
    if(!isSubmit){
        $.alert('agree')
        return isSubmit;
    }
    
})