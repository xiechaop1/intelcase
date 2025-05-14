$(function () {
    //初始化邀请码
    var winArgs = new Object();
    winArgs = GetUrlParms();

    //职位详情页诸葛埋点
    //诸葛登录按钮点击埋点
    zhugeJobDetailOpen();
    function zhugeJobDetailOpen() {
        zhuge.track('进入职位详情页', {
            id:'post_details_page_entrance',
            time: time(),//时间
            source_list: pageName(),  //预定义属性
            jobid:winArgs.job_id,
        });
        zhuge.identify('post_details_page_entrance',{
            '事件名称':'进入职位详情页',
            'time':time(),//时间
            'source_list': pageName(),  //预定义属性
            'jobid':winArgs.job_id,
        })
        console.log("post_details_page_entrance"+time(),winArgs.job_id,pageName());
    }

    //诸葛页面识别id
    function pageName() {
        var pageId=winArgs.page_id;
        var pageName='';

        switch(pageId){
            case 'h5_job_list':
                pageName="小蛙推荐";
                break;
            case 'pc_favorite':
                pageName="收藏夹";
                break;
            case 'pc_index_history':
                pageName="浏览历史";
                break;
            case 'pc_index_search':
                pageName="搜索结果";
                break;
            case 'pc_index_filter':
                pageName="筛选结果";
                break;
            case 'pc_index_recommend':
                pageName="为你推荐";
                break;
            case 'pc_index_hot':
                pageName="近期优选";
                break;
            case 'pc_index_no_login':
                pageName="小蛙推荐";
                break;
            default:
                pageName="未知";
                break;
        }

        return pageName;

    }


    //公用函数获取时间
    function time(){
        var myDate = new Date();
        //获取当前年
        var year = myDate.getFullYear();
        //获取当前月
        var month = myDate.getMonth() + 1;
        //获取当前日
        var date = myDate.getDate();
        var h = myDate.getHours(); //获取当前小时数(0-23)
        var m = myDate.getMinutes(); //获取当前分钟数(0-59)
        var s = myDate.getSeconds();
        //获取当前时间
        var now = year+'-'+ conver(month)+"-"+conver(date) +" "+conver(h)+':'+conver(m)+ ":"+ conver(s);
        return now;
    }

    //日期时间处理
    function conver(s) {
        return s < 10 ? '0' + s : s;
    }

    //获取url中的关键词
    function GetUrlParms(){

        var args=new Object();
        var url=window.location.href
        url?url=url:url="?";

        var query=url.split("?")[1];//获取查询串
        if(query){
            var pairs=query.split("&");//在逗号处断开

            for(var   i=0;i<pairs.length;i++)

            {

                var pos=pairs[i].indexOf('=');//查找name=value

                if(pos==-1)   continue;//如果没有找到就跳过

                var argname=pairs[i].substring(0,pos);//提取name

                var value=pairs[i].substring(pos+1);//提取value

                args[argname]=unescape(value);//存为属性

            }
        }

        return args;
        console.log(JSON.stringify(args))

    }

})