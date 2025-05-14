(function(){
    $.extend({
        alert : function(type,dialogBtn,dialogFn){
            var content = template[type];
            $('.modal[id="alert"]').find('.modal-content .wraper').html(content)

            $('.modal[id="alert"]').modal('show')
            dialogFn && dialogBtn ? $(dialogBtn).on('click',dialogFn) : ''
        }
    })
    var template = {
        //注册成功
        regSuc : `
                        <div class="text-center">
                            <div class="fs-19 text-F6 my-4 medium">
                                注册成功!
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        // 注册 账户重复
        regRepetition : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="fs-19 text-F6 medium">
                                    该账户已被注册
                                </div>
                                <div class="text-66 mt-1 medium">
                                    请直接登录或找回密码。
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        // 兑换成功
        convertSuc : `
                        <div class="text-center">
                            <div class="fs-19 text-F6 my-4 medium">
                                兑换成功!
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //积分兑换
        points : `
                        <form class="text-center">
                            <div class="d-flex my-3">
                                <lable for="points" class="fs-19 text-F6 medium mr-3">
                                    积分兑换
                                </lable>
                                <div class="border-F6 rounded-5">
                                    <input type="text" id="points" name="points">
                                </div>
                            </div>
                            <div class="text-left my-4 text-66 w-90">
                                兑换规则：每消费£1积累1分，100积分即可兑换£1，需整数兑换。例：100分兑换£1。
                            </div>
                            <div class="d-flex justify-content-center my-3">
                                <div data-dismiss="modal" class="btn btn-outline-FE w-30 medium rounded-20 mt-2 mx-3">关闭</div>
                                <div class="btn btn-danger w-30 medium rounded-20 mt-2 mx-3">兑换</div>
                            </div>
                        </form> `,
        //获得发票
        bill : `
                        <div class="text-center">
                            <div class="my-3 text-left ml-5">
                                <div class="fs-18 bold text-F6 medium">
                                    发票已发送至您的邮箱！ 
                                </div>
                                <div class="fs-18 bold text-F6 medium">
                                    请留意查收哦
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-1 mb-2">关闭</div>
                        </div> `,
        //反馈提示
        feedback : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium">
                                僧僧已经收到您的反馈，我们会尽快给您回复。
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //规划师不在线
        offline : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    抱歉，我们的规划师不在线！ 
                                </div>
                                <div class="text-F6 medium">
                                    您的需求我们已经收到，我们将尽快与您联系！
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //页面超时
        overtime : `
                        <div class="text-center">
                            <div class="mb-3 text-left">
                                <div class="fs-19 text-F6 medium">
                                    停留页面超时
                                </div>
                                <div class="text-66 mt-1 medium">
                                    抱歉，页面停留时间过长，请重新查询航班信息。
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block medium rounded-20 mt-3">重新查询航班信息</div>
                        </div>`,
        //旅行预算超出人数
        beyondnumber : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium">
                                僧僧已经收到您的反馈，我们会尽快给您回复。
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        
    } 
    var str = `
                <div class="modal fade" id="alert" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded overflow-hidden">
                            <div class="text-FF bg-F6 px-4 py-2 d-flex justify-content-center align-items-center">
                                <span class="fs-18">僧僧提示</span>
                            </div>
                            <div class="px-4 py-3 bg-sm-lxs wraper min-h-166">
                                
                            </div>
                        </div>
                    </div>
                </div>
            `
    $('body').append(str)
})()
