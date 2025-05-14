$(function(){
    function init(){

        var selectArr = []
        //构造函数
        function Selector(ele){
            var self =this
            this.show = function(){
                self.ul.css({
                    display: 'block'
                })
                this.$ele.addClass('up')
                self.isShow = true;
            }
            this.hide = function(e){
                self.ul.css({
                    display: 'none'
                })
                this.$ele.removeClass('up')
                self.isShow = false;
            }
            this.isShow = false;
            this.$ele = $(ele)
            this.input = this.$ele.find('input')
            this.li = this.$ele.find('li')
            this.ul = this.$ele.find('ul')
            this.text  = this.$ele.find('.text')
            this.input.val()!='' ? this.text.val(this.input.val()) : ''
            this.$ele.on('click',function(e){
                // window.event?window.event.cancelBubble=true:event.stopPropagation();
                if(self.isShow){
                    self.hide()
                }else{
                    self.show()
                }
            })
            this.li.on('click',function(e){
                window.event?window.event.cancelBubble=true:event.stopPropagation();
                if(self.$ele.hasClass('stay')){

                }else if(self.$ele.hasClass('select-more')){
                    $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active')
                    var str = '';
                    var data_city = ''
                    self.li.each(function (ind,ele){
                        $(ele).hasClass('active') ? str = str + $(ele).html() + ',' : ''
                        if($(ele).attr('data_id')){
                            $(ele).hasClass('active') ? data_city = data_city + $(ele).attr('data_id') + ',' : ''
                        }

                    })
                    // if(data_city){
                        // self.input.val(data_city)
                        self.input.attr("data_city",str)
                    // }else{
                        // self.input.val(str)
                    // }
                    // self.input.change()
                    if(str == ''){
                        str = '请选择'
                    }
                    self.text.html(str)
                }else{
                    var val = $(this).attr('data-id') ? $(this).attr('data-id') : $(this).html()
                    console.log(val);
                    self.input.val(val);
                    self.text.html($(this).html())
                    console.log(self.input.val())
                    self.input.change();

                    self.hide()
                }
            })
            this.$ele.find('.hideDrop').on('click',function(){
                self.hide()
            })
        }
        $.extend({render:render})
        function render(){
            $('.select').each(function(ind,ele){
                selectArr.push(new Selector(ele))
            })
        }
        render()

        window.onclick = function(e){
            var res = Array.from(e.path||e.composedPath()).filter(ele=>{
                if($(ele).hasClass('select')){
                    return true
                }else{
                    return false
                }
            })
            // console.log(res)
            selectArr.forEach(ele=>{
                // console.log(ele);
                (ele.$ele[0] == res[0] && res.length>0) ? '' : ele.hide()
            })
            $('.think .think_content').hide()
            $('.think .think_content_car').hide()
        }
    }
    $.extend({
        dropdownBind:init
    })
    $.dropdownBind()
})

