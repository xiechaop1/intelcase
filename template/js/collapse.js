$('.collapse').on('show.bs.collapse',function(e){
    var $target =  $(`a[data-toggle="collapse"][href="#${e.target.id}"]`)
    console.log($target)
    if($target.hasClass('iconarrow')){
        $target.removeClass('iconarrow')
        $target.addClass('iconshang')
    }else{
        if($target.find('.iconarrow').length > 0 ){
            icon = $target.find('.iconarrow')
            icon.removeClass('iconarrow')
            icon.addClass('iconshang')
            var text = $target.text()
            switch(text){
                case '航班详情' : 
                    $target.html('收起<span class="iconfont iconshang"></span>')
                    $target.attr('text','航班详情')
                    break;
                case '购买套餐' : 
                    $target.html('收起<span class="iconfont iconshang"></span>')
                    $target.attr('text','购买套餐')
                    break;
            }
            
        }else{

        }
    }
    
})
$('.collapse').on('hide.bs.collapse',function(e){
    var $target =  $(`a[data-toggle="collapse"][href="#${e.target.id}"]`)
    console.log($target.find('.iconshang'))
    if($target.hasClass('iconshang')){
        $target.removeClass('iconshang')
        $target.addClass('iconarrow')
    }else{
        if($target.find('.iconshang').length > 0 ){
            icon = $target.find('.iconshang')
            icon.removeClass('iconshang')
            icon.addClass('iconarrow')
            var text = $target.text()
            
            switch(text){
                case '收起' : 
                    $target.html($target.attr('text')+'<span class="iconfont iconarrow"></span>')
                    break;
            }
        }else{

        }
    }
    
})
