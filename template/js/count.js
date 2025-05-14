(function(){
    function Count(ele,update){
        this.value = $(ele).find('.value').html()
        this.value_dom = $(ele).find('.value')
        this.add = $(ele).find('.add')
        this.minus = $(ele).find('.minus')
        var self = this;
        this.add.on('click',function(e){

            self.value = $(ele).find('.value').html()
            
            self.value ++;
            self.value_dom.html(self.value)
            update ? changeInput($(e.target)) : ''
        })
        this.minus.on('click',function(e){

            self.value = $(ele).find('.value').html()

            self.value>0 ? self.value -- : ''
            self.value_dom.html(self.value)
            update ? changeInput($(e.target)) : ''
        })
        function changeInput(target){
            var str = '';
            var val = '';

            var parent = target.parents('.stay.select')
            
            parent.find('.value').each(function(ind,ele){
                if($(ele).html() > 0){
                    str += $(ele).attr('name') +':'+ $(ele).html() + ';'
                    val += $(ele).attr('key') +':'+ $(ele).html() + ';'
                }
            })
            str = str.substring(0, str.length-1)
            val = val.substring(0, val.length-1)
            console.log(val)
    //input选择器
            parent.find('input[name="traveller"]').val(val)
            parent.find('.ticket').html(str)
        }
        function inputChanged(){

        }
        // $('')
    }
    $('.count').each(function(ind,ele){
        if($(ele).find('span[key]')){
            new Count(ele,true)
        }else{
            new Count(ele)
        }
    })
    
})()