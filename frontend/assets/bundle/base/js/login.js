(function(){
    var loginStr = `
            <div class="bg-FF py-4 px-5 d-none" name="loginStr">
                <div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <div class="fs-30">登录留学僧</div>
                            <span class="fs-18">还没有账号?<span class="text-F6 pointer registBtn">注册</span></span>
                        </div>
                        <div>
                            <div class="close" data-dismiss="modal">
                                <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs fs-18 bottomLine d-flex justify-content-around medium">
                        <li>
                            <a class="d-block px-2 pb-3 pt-4 active" href="#phone" data-toggle="tab">手机号登录</a>
                        </li>
                        <li>
                            <a class="d-block px-2 pb-3 pt-4" href="#email" data-toggle="tab">邮箱登录</a>
                        </li>
                    </ul>
                    <div class="mt-3 tab-content">
                        <div class="tab-pane fade show active" id="phone">
                            <form name="phone">
                                <div class="d-flex align-items-center mt-3 justify-content-between">
                                    <select class="border-0 h-42 bg-F5 w-16 p-2" name="sections">
                                        <option value="44">+44（英国）</option>
                                        <option value="86">+86（中国大陆）</option>
                                        <option value="852">+852（香港）</option>
                                        <option value="853">+853（澳门）</option>
                                        <option value="886">+886（台湾）</option>
                                    </select>
                                    <div class="w-80 bg-F5 p-2 d-flex align-items-center">
                                        <span class="text-F6 iconfont iconxingzhuang"></span>
                                        <input name="phone" class="w-80 ml-2" type="text" autocomplete="off" placeholder="手机号">
                                        <div class="invalid-feedback">请输入正确手机号</div>
                                    </div>
                                </div>
                                <div class="w-100 bg-F5 mt-3 p-2 d-flex align-items-center">
                                    <span class="text-F6 iconfont iconmima"></span>
                                    <input name="password" class="w-100 ml-2" type="password" placeholder="密码">
                                    <div class="invalid-feedback">密码不得少于6位</div>
                                </div>
                                <div class="text-99 text-right mt-2"><span class="findPassWord pointer">忘记密码？<span></div>
                                <div class="submit btn btn-danger w-100 mt-4">登录</div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="email">
                            <form name="email">
                                <div class="w-100 bg-F5 mt-3 p-2 d-flex align-items-center">
                                    <span class="text-F6 iconfont iconxingzhuang"></span>
                                    <input name="email" class="w-100 ml-2" type="text" autocomplete="off" placeholder="邮箱">
                                    <div class="invalid-feedback">请输入正确邮箱</div>
                                </div>
                                <div class="w-100 bg-F5 mt-3 p-2 d-flex align-items-center">
                                    <span class="text-F6 iconfont iconmima"></span>
                                    <input name="password" class="w-100 ml-2" type="password" placeholder="密码">
                                    <div class="invalid-feedback">密码不得少于6位</div>
                                </div>
                                <div class="text-99 text-right mt-2"><span class="findPassWord pointer">忘记密码？<span></div>
                                <div class="submit btn btn-danger w-100 mt-4">登录</div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-5 linetext">第三方登录</div>
                    <div class="d-flex justify-content-between mt-2 ">
                        <div class="pointer d-flex align-items-center text-99"><span class="text-99 mr-1 fs-22 iconfont iconweixindenglu"></span>微信</div>
                        <div class="pointer d-flex align-items-center text-99"><span class="text-99 mr-1 fs-22 iconfont iconqq"></span>QQ</div>
                        <div class="pointer d-flex align-items-center text-99"><span class="text-99 mr-1 fs-22 iconfont iconweibo"></span>微博</div>
                    </div>
                </div>
            </div>
    `
    var registStr = `
        <div class="bg-FF py-4 px-5 d-none" name="registStr">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="fs-30">注册留学僧</div>
                    <span class="fs-18">已有账号?<span class="text-F6 pointer loginBtn">登录</span></span>
                </div>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="btn btn-danger w-100 mt-3 phoneBtn">
                    <span>手机号注册<sapn>
                </div>
                <div class="btn btn-outline-danger w-100 mt-3 emailBtn">
                    <span>邮箱注册<sapn>
                </div>
            </div>
        </div>
    `
    var registPhone = `
        <div class="bg-FF py-4 px-5 d-none" name="registPhone">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="fs-30">手机号注册</div>
                    <span class="fs-18">已有账号?<span class="text-F6 pointer loginBtn">登录</span></span>
                </div>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <form name="phoneReg">
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <select class="w-30 border-EB rounded" name="sections">
                            <option value="44">+44（英国）</option>
                            <option value="86">+86（中国）</option>
                            <option value="852">+852（香港）</option>
                            <option value="853">+853（澳门）</option>
                            <option value="886">+886（台湾）</option>
                        </select>
                        <div class="p-2 ml-3 border-EB rounded w-70 d-flex">
                            <input name="phone" class="w-100" type="text" autocomplete="off" placeholder="手机号">
                            <div class="invalid-feedback">请输入正确手机号</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 align-items-center">
                        <div class="p-2 w-60 border-EB rounded d-flex align-items-center relative">
                            <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                            <div class="invalid-feedback">请输入验证码</div>
                        </div>
                        <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-phone vcode">发送验证码</div>
                    </div>
                    <div>
                        <div class="p-2 mt-3 border-EB rounded d-flex">
                            <input name="password" class="w-100" type="password" placeholder="设置密码">
                            <div class="invalid-feedback">密码不得少于6位</div>
                        </div>
                        <div class="p-2 mt-3 border-EB rounded">
                            <input class="w-100" type="text" name="invite" autocomplete="off" placeholder="推荐码">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-center">   
                        <input class="" type="checkbox" name="mustCheck" checked id="secretCheck_phone">
                        <label class="mb-0" for="secretCheck_phone">我已阅读并同意网站的使用条件以及隐私声明</label>
                        <div class="invalid-feedback">请同意</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <input class="" type="checkbox" name="push" checked id="receiveCheck_phone">
                        <label class="mb-0" for="receiveCheck_phone">同意接收推送订阅消息</label>
                    </div>
                </div>
                <div class="submit w-100 btn btn-danger mt-3">
                    注册
                </div>
            </form>
            <div class="mt-2 pointer text-right text-99 emailBtn">
                邮箱注册 >
            </div>
        </div>
    `
    var registEmail = `
        <div class="bg-FF py-4 px-5 d-none" name="registEmail">
            <div class="d-flex justify-content-between">
                <div class="">
                    <div class="fs-30">邮箱注册</div>
                    <span class="fs-18">已有账号?<span class="text-F6 pointer loginBtn">登录</span></span>
                </div>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <form name="emailReg">
                <div class="mt-3">
                    <div class="p-2 border-EB rounded d-flex">
                        <input name="email" class="w-100" type="text" autocomplete="off" placeholder="邮箱地址">
                        <div class="invalid-feedback">请输入正确邮箱</div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 align-items-center">
                        <div class="p-2 w-60 border-EB rounded d-flex">
                            <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                            <div class="invalid-feedback">请输入验证码</div>
                        </div>
                        <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-mail vcode">发送验证码</div>
                    </div>
                    <div>
                        <div class="p-2 mt-3 border-EB rounded d-flex">
                            <input name="password" class="w-100" type="password" placeholder="设置密码">
                            <div class="invalid-feedback">密码不得少于6位</div>
                        </div>
                        <div class="p-2 mt-3 border-EB rounded">
                            <input class="w-100" type="text" name="invite" autocomplete="off" placeholder="推荐码">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="mt-3">
                    <div class="d-flex align-items-center">   
                        <input class="" type="checkbox" name="mustCheck" checked id="secretCheck_email">
                        <label class="mb-0" for="secretCheck_email">我已阅读并同意网站的使用条件以及隐私声明</label>
                        <div class="invalid-feedback">请同意</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <input class="" type="checkbox" name="push" checked id="receiveCheck_email">
                        <label class="mb-0" for="receiveCheck_email">同意接收推送订阅消息</label>
                    </div>
                </div>
                </div>
                <div class="submit w-100 btn btn-danger mt-3">
                    注册
                </div>
            </form>
            <div class="mt-2 pointer text-right text-99 phoneBtn">
                手机号注册 >
            </div>
        </div>
    `
    var findPassWord = `
        <div class="bg-FF py-4 px-5 d-none" name="findPassWord">
            <div class="d-flex justify-content-between">
                <ul class="nav nav-tabs findpswTab align-items-center">
                    <li>
                        <a class="nav-link active" data-toggle="tab" href="#phoneFind">手机找回</a>
                    </li>
                    <li>
                        <a class="nav-link" data-toggle="tab" href="#emailFind">邮箱找回</a>
                    </li>
                </ul>
                <div>
                    <div class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="iconfont iconguanbi fs-26 text-D6"></span>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="mt-3 tab-pane fade show active" id="phoneFind">
                    <form name="phoneFind">
                        <div class="d-flex justify-content-between">
                            <select class="w-31 border-EB rounded" name="sections">
                                <option value="44">+44（英国）</option>
                                <option value="86">+86（中国）</option>
                                <option value="852">+852（香港）</option>
                                <option value="853">+853（澳门）</option>
                                <option value="886">+886（台湾）</option>
                            </select>
                            <div class="p-2 ml-3 border-EB rounded w-70 d-flex">
                                <input class="w-100" name="phone" type="text" autocomplete="off" placeholder="手机号">
                                <div class="invalid-feedback">请输入正确手机号</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3 align-items-center">
                            <div class="p-2 w-60 border-EB rounded d-flex">
                                <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                                <div class="invalid-feedback">请输入验证码</div>
                            </div>
                            <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-phone vcode">发送验证码</div>
                        </div>
                        <div>
                            <div class="p-2 mt-3 border-EB rounded d-flex">
                                <input name="oldPassword" class="w-100" type="password" placeholder="设置密码">
                                <div class="invalid-feedback">密码不得少于6位</div>
                            </div>
                            <div class="p-2 mt-3 border-EB rounded d-flex">
                                <input name="newPassword" class="w-100" type="password" placeholder="再次输入新的密码">
                                <div class="invalid-feedback">两次密码必须一致</div>
                            </div>
                        </div>
                        <div class="w-100 submit btn btn-danger mt-5">
                            保存
                        </div>
                    </form>
                </div>
                <div class="mt-3 tab-pane fade" id="emailFind">
                    <form name="emailFind">
                        <div class="p-2 border-EB rounded d-flex">
                            <input class="w-100" type="text" autocomplete="off" name="email" placeholder="邮箱地址">
                            <div class="invalid-feedback">请输入正确邮箱</div>
                        </div>
                        <div class="d-flex justify-content-between mt-3 align-items-center">
                            <div class="p-2 w-60 border-EB rounded d-flex">
                                <input class="w-100" name="vcode" type="text" autocomplete="off" placeholder="验证码">
                                <div class="invalid-feedback">请输入验证码</div>
                            </div>
                            <div class="btn btn-outline-danger ml-3 w-40 p-2 vcode-mail vcode">发送验证码</div>
                        </div>
                        <div>
                            <div class="p-2 mt-3 border-EB rounded d-flex">
                                <input name="oldPassword" class="w-100" type="password" placeholder="设置密码">
                                <div class="invalid-feedback">密码不得少于6位</div>
                            </div>
                            <div class="p-2 mt-3 border-EB rounded d-flex">
                                <input name="newPassword" class="w-100" type="password" placeholder="再次输入新的密码">
                                <div class="invalid-feedback">两次密码必须一致</div>
                            </div>
                        </div>
                        <div class="w-100 submit btn btn-danger mt-5">
                            保存
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `
    init()
    render('loginStr')
    
    
    function render(inner){
        hideAll()
        $(`div[name="${inner}"]`).removeClass('d-none')
        
    }
    function init(){
        var final = `<div class="modal fade" id="login" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                ${registStr}${loginStr}${registPhone}${registEmail}${findPassWord}
                            </div>
                        </div>
                    </div>`
        
        
        $('body').append(final)
        bindEvent()
        $.bindCheck()
    }
    function hideAll(){
        $('div[name="loginStr"],div[name="registStr"],div[name="registPhone"],div[name="registEmail"],div[name="findPassWord"]').each(function(ind,ele){
            $(ele).hasClass('d-none') ? '':$(ele).addClass('d-none')
        })
    }
    function bindEvent(){
        $('.registBtn').on('click',function(){
            render('registStr')
        })
        $('.loginBtn').on('click',function(){
            render('loginStr')
        })
        $('.modal[id="login"]').on('hidden.bs.modal',function(){
            render('loginStr')
        })
        $('.phoneBtn').on('click',function(){
            render('registPhone')
        })
        $('.emailBtn').on('click',function(){
            render('registEmail')
        })
        $('.findPassWord').on('click',function(){
            render('findPassWord')
        })
        $('.submit').on('click',function(e){
            
            var $form = $(e.target).parents('form')
            $.checkForm($form,function(){
                var name = this.attr('name').toString()
                console.log(this.attr('name').toString())
                switch(name){
                    case 'phone' : 
                            console.log('phone')
                            $.ajax({
                                url: "/passport/login",
                                type: "POST",
                                data: {
                                    way : "mobile",
                                    mobile : this.find("input[name='phone']").val(),
                                    mobile_section : `${this.find("select[name='sections']").val()}`,
                                    email:'',
                                    password : this.find("input[name='password']").val()
                                },
                                success: function(result) {
                                    window.location.reload()
                                }
                            })
                        break;
                    case "email" :
                        console.log('email')
                        $.ajax({
                            url: "/passport/login",
                            type: "POST",
                            data: {
                                way : "email",
                                mobile : '',
                                mobile_section : '',
                                email:this.find("input[name='email']").val(),
                                password : this.find("input[name='password']").val()
                            },
                            success: function(result) {
                                window.location.reload()
                            }
                        })
                        break;
                    case "phoneReg" :
                        console.log('phoneReg')
                        $.ajax({
                            url: "/passport/register",
                            type: "POST",
                            data: {
                                way : "mobile",
                                password : this.find("input[name='password']").val(),
                                mobile_section : `${this.find("select").val()}`,
                                mobile : this.find("input[name='phone']").val(),
                                email:'',
                                verification_code: this.find("input[name='vcode']").val(),
                                invite_code: this.find("input[name='invite']").val(),
                                allow_push: this.find("input[name='push']")[0].checked ? '1' : '2',
                            },
                            success: function(result) {
                                window.location.reload()
                            }
                        })
                        break;
                    case "emailReg" :
                        console.log('emailReg')
                        $.ajax({
                            url: "/passport/register",
                            type: "POST",
                            data: {
                                way : "email",
                                password : this.find("input[name='password']").val(),
                                mobile_section : '',
                                mobile : '',
                                email:this.find("input[name='email']").val(),
                                verification_code: this.find("input[name='vcode']").val(),
                                invite_code: this.find("input[name='invite']").val(),
                                allow_push: this.find("input[name='push']")[0].checked ? '1' : '2',
                            },
                            success: function(result) {
                                window.location.reload()
                            }
                        })
                        break;
                    case "phoneFind" :
                        console.log('phoneFind')
                        $.ajax({
                            url: "/passport/reset-password",
                            type: "POST",
                            data: {
                                way : "mobile",
                                mobile : this.find("input[name='phone']").val(),
                                mobile_section : `${this.find("select").val()}`,
                                email:'',
                                verification_code: this.find("input[name='vcode']").val(),
                                invite_code: this.find("input[name='invite']").val(),
                                password : this.find("input[name='newPassword']").val(),
                            },
                            success: function(result) {
                                window.location.reload()
                            }
                        })
                        break;
                    case "emailFind" :
                        console.log('emailFind')
                        $.ajax({
                            url: "/passport/reset-password",
                            type: "POST",
                            data: {
                                way : "email",
                                mobile_section : '',
                                mobile : '',
                                email:this.find("input[name='email']").val(),
                                verification_code: this.find("input[name='vcode']").val(),
                                invite_code: this.find("input[name='invite']").val(),
                                password : this.find("input[name='password']").val(),
                            },
                            success: function(result) {
                                window.location.reload()
                            }
                        })
                        break;
                    
                }
                
            })

        })
        $.vcode()
    }
})();



