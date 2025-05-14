(function(){
    $.extend({
        renderDate: renderDate
    })
    var time = new Date(Date.now())
    function renderDate() {
        $.fn.datepicker.dates['en'] = {
            days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            today: "Today",
            clear: "Clear",
            format: "mm/dd/yyyy",
            titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
            weekStart: 0
        };
        $(".datepicker").each(function(ind,ele){
    
            $(ele).datepicker({
                // initialDate: 0,
                // startDate: time,
                language: "zh-CN",
                forceParse: false,
                autoclose: true,//选中之后自动隐藏日期选择框
                clearBtn: false,//清除按钮
                todayHighlight: true,
                format: "yyyy-mm-dd",//日期格式，详见 http://bootstrap-datepicker.readthedocs.org/en/release/options.html#format
                startDate:$(this).attr('data-start-time')
                
            }).on('changeDate',function(){
                
                $('.datepicker').eq(0).datepicker('hide');
            })
        })
        // var now = `${time.getFullYear()}-${time.getMonth()+1<10?'0'+(time.getMonth()+1):time.getMonth()+1}-${time.getDate()<10?'0'+time.getDate():time.getDate()}`
        // console.log(now)
        // $('input.datepicker').length > 3 ? 
        // $('input.datepicker').eq(-1).val(now):
        // $('input.datepicker').val(now)
        $('input.datepicker').datepicker('update');
    }
    renderDate()

    function addDate(date,days){ 
        var d=new Date(date); 
        d.setDate(d.getDate()+days); 
        var m=d.getMonth()+1; 
        return d.getFullYear()+'-'+m+'-'+d.getDate(); 
    } 

    $('.datepicker[name="startDate"],.datepicker[name="departureDate"]').on('change',function(e){
        console.log(e.target.value)
        console.log($(e.target).parents('form').find('.datepicker[name="endDate"]').attr('data-start-time'))
        new Date(e.target.value) > new Date($(e.target).parents('form').find('.datepicker[name="endDate"]').attr('data-start-time'))?
            $(e.target).parents('form').find('.datepicker[name="endDate"]').attr({'data-start-time':e.target.value,'value':e.target.value}).val(e.target.value).datepicker('setStartDate',e.target.value) : ''

    })

    $('.datepicker[name="contract_start_date"]').on('change',function(e){
        new Date(e.target.value) > new Date($(e.target).parents('form').find('.datepicker[name="contract_end_date"]').attr('data-start-time'))?
            $(e.target).parents('form').find('.datepicker[name="contract_end_date"]').attr({'data-start-time':e.target.value,'value':e.target.value}).val(e.target.value).datepicker('setStartDate',e.target.value) : ''

        $(this).parents('form').find('.datepicker[name="contract_end_date"]').removeClass("is-invalid");
    })

    $('.datepicker.date-range-start').on('change',function(e){

        !$(e.target).closest('.date-range-box').find('.date-range-end').attr('data-start-time')?$(e.target).closest('.date-range-box').find('.date-range-end').attr({'data-start-time':e.target.value,'value':e.target.value}).val(e.target.value).datepicker('setStartDate',e.target.value) : ''


            new Date(e.target.value) > new Date($(e.target).closest('.date-range-box').find('.date-range-end').attr('data-start-time'))?
            $(e.target).closest('.date-range-box').find('.date-range-end').attr({'data-start-time':e.target.value,'value':e.target.value}).val(e.target.value).datepicker('setStartDate',e.target.value) : ''

        $(this).closest('.date-range-box').find('.date-range-end').removeClass("is-invalid");
        console.log( $(this).closest('.date-range-box').find('.date-range-end').val());
    })




     // 增减计算天数
    function getDays(strDateStart,strDateEnd){
        var strSeparator = "-"; //日期分隔符
        var oDate1;
        var oDate2;
        var iDays;
        oDate1= strDateStart.split(strSeparator);
        oDate2= strDateEnd.split(strSeparator);
        var strDateS = new Date(oDate1[0], oDate1[1]-1, oDate1[2]);
        var strDateE = new Date(oDate2[0], oDate2[1]-1, oDate2[2]);

        iDays = parseInt(Math.abs(strDateS - strDateE ) / 1000 / 60 / 60 /24)//把相差的毫秒数转换为天数

        return iDays ;
    }
    // 计算天数
    function counterDays(stratDateStr,endDateStr){
        var stratDateArr,endDateArr,days;
        stratDateArr = stratDateStr.split('-');
        endDateArr = endDateStr.split('-');
        var newDateS = new Date(Date.UTC(stratDateArr[0],stratDateArr[1]-1,stratDateArr[2]));
        var newDateE = new Date(Date.UTC(endDateArr[0],endDateArr[1]-1,endDateArr[2]));
        days = parseInt((newDateE - newDateS ) / 1000 / 60 / 60 /24);
        return days;
    }


    $('.datepicker[name="t-startDate"],.datepicker[name="departureDate"]').on('change',function(e){


        var threedate = addDate(e.target.value,0);

        var counter_endday =   $("input[name= 't-endDate']").val();
        var countday = counterDays(counter_endday,e.target.value);

        if(countday > 0){
            $(e.target).parents('form').find('.datepicker[name="t-endDate"]').attr({'data-start-time':threedate,'value':threedate}).val(threedate).datepicker('setStartDate',threedate)
        } else {
            $('.datepicker[name="t-endDate"]').attr({'data-start-time':threedate,'value':$("input[name= 't-endDate']").val()}).val($("input[name= 't-endDate']").val()).datepicker('setStartDate',threedate)
        }
        
        var days = getDays($("input[name= 't-startDate']").val(),$("input[name= 't-endDate']").val());
        $('.counterdays').html(days+'天');
    })

    $('.datepicker[name="t-endDate"],.datepicker[name="departureDate"]').on('change',function(e){
        var days = getDays($("input[name= 't-startDate']").val(),$("input[name= 't-endDate']").val());
        $('.counterdays').html(days+'天');
    })

    // $(‘.datepicker[name="startDate"])

    window.renderDate = renderDate;
})()
