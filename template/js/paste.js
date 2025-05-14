
$(function(){

    $.fn.modal.Constructor.prototype._enforceFocus = function() {};

    //邀请好友复制
    var clipboard = new ClipboardJS('.btn-paste', {
        container: document.getElementById('invite-step-modal')
    });
    clipboard.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        $("#invite-step-modal").modal('hide');
        $.alert("复制成功");
    });
    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });


    //h5详情页为绑定猎企
    var clipboardUnbind = new ClipboardJS('.btn-paste-unbind', {
        container: document.getElementById('note-unbind-company')
    });
    clipboardUnbind.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);

        $("#note-unbind-company").modal('hide');

        h5PasteSuccessNote();
        setTimeout(function () {
            window.location.reload();
        },2000);
    });
    clipboardUnbind.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    //h5 职位为空复制
    var clipboardEmptyPosition = new ClipboardJS('.btn-paste-empty-position', {
        container: document.getElementById('h5-empty-position')
    });
    clipboardEmptyPosition.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        $("#h5-empty-position").modal('hide');
        h5PasteSuccessNote();
        setTimeout(function () {
            window.location.reload();
        },2000);
    });
    clipboardEmptyPosition.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    //h5 收藏成功复制链接
    var clipboardLikeSuccess = new ClipboardJS('.btn-paste-like-success', {
        container: document.getElementById('h5-like-success')
    });
    clipboardLikeSuccess.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        $("#h5-like-success").modal('hide');
        h5PasteSuccessNote();
        setTimeout(function () {
            window.location.reload();
        },2000);
    });
    clipboardLikeSuccess.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    //h5 登录成功复制链接
    var clipboardLoginSuccess = new ClipboardJS('.btn-paste-login-success', {
        container: document.getElementById('h5-login-success')
    });
    clipboardLoginSuccess.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        $("#h5-login-success").modal('hide');
        h5PasteSuccessNote();
        setTimeout(function () {
            window.location.reload();
        },2000);
    });
    clipboardLoginSuccess.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });


    function h5PasteSuccessNote() {
        $.Toast("链接复制成功", "", "success", {
            stack: true,
            position_class: "toast-top-center",
            has_icon:true,
            has_close_btn:false,
            fullscreen:false,
            timeout:2000,
            sticky:false,
            has_progress:true,
            rtl:false,
        });

    }

})