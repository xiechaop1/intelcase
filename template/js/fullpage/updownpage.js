(function () {
    var a = 0;
    var b = 0;
    $(function () {
        b = $("#content .content-child").length;
        var doteul = document.createElement("ul");
        doteul.setAttribute("id", "doteul");
        for (var i = 0; i < b; i++) {
            var li = document.createElement("li");
            if (i == 0) {
                li.setAttribute("class", "ckeck-li")
            }
            doteul.appendChild(li)
        }

        $(doteul).prepend('<div class="up iconfont iconjiantou12-copy text-66"></div>')
        $(doteul).append('<div class="down iconfont iconjiantou12-copy text-66"></div>')

        $(".content").append(doteul);
        $("#doteul li").click(function () {
            var index = $("#doteul li").index(this);
            // var a = $('#doteul .ckeck-li').index();
            // if (index > a) {
            move(index)
            // } else {
                // if (index < a) {
                //     $("#content").animate({
                //         top: "+=" + (a - index) + "00vh"
                //     }, 500);
                //     $("#doteul li").removeClass("ckeck-li");
                //     $("#doteul li").eq(index).addClass("ckeck-li")
                // } else {
                //     return
                // }
            // }
        })
        $('#doteul .up').on('click',function(){
            var index = $('#doteul .ckeck-li').index()-2;
            index>=0 ? move(index) : ''
        })
        $('#doteul .down').on('click',function(){
            var index = $('#doteul .ckeck-li').index();
            index<=4 ? move(index) : ''
        })
        function move(index){
            $("#content").animate({
                top: "-" + index + "00vh"
            }, 500);
            $("#doteul li").removeClass("ckeck-li");
            $("#doteul li").eq(index).addClass("ckeck-li")
        }
    })
}());
