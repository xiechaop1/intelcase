$(function () {

    //我的发单-候选人修改状态 上传附件
    $('#addAuthorize').fileupload({
        autoUpload : true,
        url: "/upload/files",
        datatype:"json",
        /*   progressall: function (e, data) {
               var progress = parseInt(data.loaded / data.total * 100, 10);
               $('#progress .bar').css(
                   'width',
                   progress + '%'
               );
              console.log("addAuthorizeProgress");
              console.log(e,data);
           },
           add: function (e, data) {
               console.log("addAuthorizeProgress:add")
               console.log(e,data);
           },*/
        add: function (e, data) {

            var uploadErrors = [];
          

            //文件大小判断
            if (data.originalFiles[0].size > (2 * 1024 * 1024)) {
                uploadErrors.push('请上传不超过2M的文件');
            }

            if (uploadErrors.length > 0) {
                alert(uploadErrors.join("\n"));
            } else {
                data.submit();
            }
        },
        done: function (e, data) {
            console.log("addAuthorizeProgress:done:"+JSON.stringify(data.result));

            // var obj=$.toJSON(data.result);
            // var obj2 = eval( "(" + data.result + ")" );//转换后的JSON对象

            //console.log(obj.code,obj['code'],data.result.code);

            if(data.result.code==200){


                // $("input[name='authorize']").val(data.result.data.file_array.file_path);
                $("input[name='authorize']").val(data.result.data.sub_file_name);

                $("input[name='authorize']").removeClass("is-invalid")
                var url="http://www.vhewa.com"+data.result.data.file_array.file_path;
                var name=data.result.data.file_array.file_model.name;

                var path=data.result.data.file_array.file_path
                var type=path.substr(path.length-3);

                if(type=='doc'||type=='ppt'||type=='xlsx'||type=="ocx"){
                    url="http://view.officeapps.live.com/op/view.aspx?src="+url;
                }
                else{

                }

                $("#addAuthorize").closest(".add-file").addClass("d-none");

                $("#addAuthorize-pre").removeClass("d-none");
                $("#addAuthorize-pre a").attr("href",url).empty().text(name);
                console.log("upload_file:"+$("input[name='upload_file']").val())

                $(".position-analyse").removeClass("require").removeClass("is-invalid");

            }
            else{
                alert(data.result.msg+"code!=200");
            }


        },
        /* start: function (e) {
             console.log('Uploads started');
         },
         change: function (e, data) {
             console.log("addAuthorizeProgress:change")
             console.log(e,data);
             $.each(data.files, function (index, file) {
                 console.log('Selected file: ' + file.name);
             });
         },
         processstart: function (e) {
             console.log('Processing started...');
         },
         processfail:function (e,data) {
             console.log(e,data);
             if (data.errorThrown=='abort') {
                 console.log('上传取消！', 'success');
             }else{
                 console.log('上传失败，请稍后重试！', 'error');
             }
         },*/
        fail:function (e,data) {
            console.log(e,data);
            if (data.errorThrown=='abort') {
                console.log('上传取消！', 'success');
            }else{
                console.log('上传失败，请稍后重试！', 'error');
            }
        },
    })

    //个人中心上传头像
    $('#addUserPhoto,#addUserPhoto1').fileupload({
        autoUpload : true,
        url: "/upload/images",
        datatype:"json",
        add: function (e, data) {

            var uploadErrors = [];
            var acceptFileTypes = /^image\/(gif|jpe?g|png|bmp)$/i;

            //文件类型判断
            if (data.originalFiles[0].type.length && !acceptFileTypes.test(data.originalFiles[0].type)) {
                uploadErrors.push('请上传gif、jpg、jpeg或png格式的文件');
            }

            //文件大小判断
            if (data.originalFiles[0].size > (2 * 1024 * 1024)) {
                uploadErrors.push('请上传不超过2M的文件');
            }

            if (uploadErrors.length > 0) {
                alert(uploadErrors.join("\n"));
            } else {
                data.submit();
            }
        },

        done: function (e, data) {
            console.log("#addUserPhoto:"+JSON.stringify(data.result));

            if(data.result.code==200){
                var url=data.result.data.file_array.file_path;
                var subUrl=data.result.data.sub_file_name

                $("input[name='userPhoto']").val(subUrl);
                $("input[name='userPhoto']").removeClass("is-invalid")

                $("#addUserPhoto").closest(".add-file-box").addClass("d-none");
                $("#addUserPhoto-pre img").attr("src",url);
                $("#addUserPhoto-pre").removeClass("d-none");
                $(".user-photo-pre").removeClass("d-none").attr("src",url)

            }
            else{
                alert(data.result.msg+"code!=200");
            }

        },
        fail:function (e,data) {
            console.log(e,data);
            if (data.errorThrown=='abort') {
                console.log('上传取消！', 'success');
            }else{
                console.log('上传失败，请稍后重试！', 'error');
            }
        },
    })

    //个人中心上传身份证，营业执照，许可证
    $('#identity_front,#identity_back,#addLicense,#addBankPhoto').fileupload({
        autoUpload : true,
        url: "/upload/images",
        datatype:"json",
        add: function (e, data) {

            var uploadErrors = [];
            var acceptFileTypes = /^image\/(gif|jpe?g|png|bmp)$/i;

            //文件类型判断
            if (data.originalFiles[0].type.length && !acceptFileTypes.test(data.originalFiles[0].type)) {
                uploadErrors.push('请上传gif、jpg、jpeg或png格式的文件');
            }

            //文件大小判断
            if (data.originalFiles[0].size > (2 * 1024 * 1024)) {
                uploadErrors.push('请上传不超过2M的文件');
            }

            if (uploadErrors.length > 0) {
                alert(uploadErrors.join("\n"));
            } else {
                data.submit();
            }
        },

        done: function (e, data) {
            console.log("#addPhoto:"+JSON.stringify(data.result));

            if(data.result.code==200){
                var url=data.result.data.file_array.file_path;
                var subUrl=data.result.data.sub_file_name

                $(this).closest("label").find(".img-280").attr('src',url);
                $(this).closest("label").find(".required").val(subUrl).trigger('change');
                $(this).closest("label").addClass('hover-show-box')
            }
            else{
                alert(data.result.msg+"code!=200");
            }

        },
        fail:function (e,data) {
            console.log(e,data);
            if (data.errorThrown=='abort') {
                console.log('上传取消！', 'success');
            }else{
                console.log('上传失败，请稍后重试！', 'error');
            }
        },
    })

    //上传公司合同
/*
    $('#addLicense').fileupload({
        autoUpload : true,
        url: "/upload/index",
        datatype:"json",
        add: function (e, data) {

            var uploadErrors = [];
            var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;


            //文件大小判断
            if (data.originalFiles[0].size > (5 * 1024 * 1024)) {
                uploadErrors.push('请上传不超过5M的文件');
            }

            if (uploadErrors.length > 0) {
                $.alert(uploadErrors.join("\n"));
            } else {
                data.submit();
            }
        },
        done: function (e, data) {
            console.log("addAuthorizeProgress:done:"+JSON.stringify(data.result));


            if(data.result.code==200){

                $("input[name='license']").val(data.result.data.file_array.file_path);
                var url="http://www.vhewa.com"+data.result.data.file_array.file_path;
                var name=data.result.data.file_array.file_model.name;

                var path=data.result.data.file_array.file_path
                var type=path.substr(path.length-3);


                $("input[name='license']").removeClass("is-invalid");
                $("#addLicense").closest(".add-file").addClass("d-none");
                $("#addLicense-pre").removeClass("d-none");

                $("#addLicense-pre a").attr("href",url).empty().text(name);
                console.log("contract:"+$("input[name='contract']").val())
            }
            else{
                $.alert(data.result.msg+"code!=200");
            }

        },
        fail:function (e,data) {
            console.log(e,data);
            if (data.errorThrown=='abort') {
                console.log('上传取消！', 'success');
            }else{
                // console.log('上传失败，请稍后重试！', 'error');
                alert('上传失败！');
            }
        },
    });
*/

    //上传公司合同
/*
    $('#addBankPhoto').fileupload({
        autoUpload : true,
        url: "/upload/index",
        datatype:"json",
        add: function (e, data) {

            var uploadErrors = [];
            var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;


            //文件大小判断
            if (data.originalFiles[0].size > (5 * 1024 * 1024)) {
                uploadErrors.push('请上传不超过5M的文件');
            }

            if (uploadErrors.length > 0) {
                $.alert(uploadErrors.join("\n"));
            } else {
                data.submit();
            }
        },
        done: function (e, data) {
            console.log("addBankPhotoProgress:done:"+JSON.stringify(data.result));


            if(data.result.code==200){

                $("input[name='bank_photo']").val(data.result.data.file_array.file_path);
                var url="http://www.vhewa.com"+data.result.data.file_array.file_path;
                var name=data.result.data.file_array.file_model.name;

                var path=data.result.data.file_array.file_path
                var type=path.substr(path.length-3);


                $("input[name='bank_photo']").removeClass("is-invalid");
                $("#addBankPhoto").closest(".add-file").addClass("d-none");
                $("#addBankPhoto-pre").removeClass("d-none");

                $("#addBankPhoto-pre a").attr("href",url).empty().text(name);
                console.log("contract:"+$("input[name='contract']").val())
            }
            else{
                $.alert(data.result.msg+"code!=200");
            }

        },
        fail:function (e,data) {
            console.log(e,data);
            if (data.errorThrown=='abort') {
                console.log('上传取消！', 'success');
            }else{
                // console.log('上传失败，请稍后重试！', 'error');
                alert('上传失败！');
            }
        },
    });
*/



    $(".delete-add-image").click(function () {
        var pre=$(this).closest("label");
        pre.addClass("d-none");
        pre.find("a").attr("href","").empty();

        var add=$(this).closest('div');
        add.find(".add-file").removeClass("d-none");
        add.find("input[type='file']").val("");
        add.find(".file-url").val("");

    })
})