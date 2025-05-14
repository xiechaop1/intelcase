(function(){
    var toastMsg = {
        value : 'asdasdasd'
    }
    $.extend({
        alert : function(type,dialogBtn,dialogFn,msg){
            // msg ? toastMsg.value = msg : ''
            if(msg){
                template.customMsg = `
                    <div class="text-center">
                        <div class="my-3 text-left">
                            <div class="text-F6 medium">
                                ${toastMsg.value}
                            </div>
                        </div>
                        <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                    </div>
                `
            }
            var content = template[type];
            if(content){

            }
            else{
                content=`
                        <div class="text-center">
                            <div class="text-F6 my-4 medium">`+type+` </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2 reload">关闭</div>
                        </div> 
        `}

            $('.modal[id="alert"]').find('.modal-content .wraper').html(content)
            $('.modal[id="alert"]').modal('show')
            dialogFn && dialogBtn ? $(dialogBtn).on('click',dialogFn) : ''
        }
    })
    var template = {
        max3:`
                        <div class="text-center">
                            <div class="text-F6 my-4 medium">
                                最多可以选择3个
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2 reload">关闭</div>
                        </div> 
        `,
        receive:`
                        <div class="text-center">
                            <div class="text-F6 my-4 medium">
                                僧僧已经收到您的需求，我们将尽快与您联系！
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2 reload">关闭</div>
                        </div> 
        `,
        //修改成功
        changeSuccess : `
                        <div class="text-center">
                            <div class="fs-19 text-F6 my-4 medium">
                                修改成功!
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2 reload">关闭</div>
                        </div> 
        `,
        //noPhone
        noPhone : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="fs-19 text-F6 medium">
                                    未绑定手机！
                                </div>
                                <div class="text-66 mt-1 medium">
                                    修改邮箱需要先绑定手机。
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div>
        `,
        //未绑定邮箱
        noMail : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="fs-19 text-F6 medium">
                                    未绑定邮箱！
                                </div>
                                <div class="text-66 mt-1 medium">
                                    修改手机号需要先绑定邮箱。
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div>`,
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
                            <div class="d-flex my-3 align-items-center">
                                <lable for="points" class="fs-18 text-F6 medium mr-3">
                                    积分兑换
                                </lable>
                                <div class="border-F6 rounded-5 p-1 w-60">
                                    <input class="w-100" type="text" id="points" name="credit">
                                </div>
                            </div>
                            <div class="text-left my-4 text-99 w-85 fs-14">
                                兑换规则：每消费£1积累1分，100积分即可兑换£1，需整数兑换。例：100分兑换£1。
                            </div>
                            <div class="d-flex justify-content-center my-3">
                                <div data-dismiss="modal" class="btn btn-outline-FE w-30 medium rounded-20 mt-2 mx-3">关闭</div>
                                <div class="btn btn-danger w-30 medium rounded-20 mt-2 mx-3 points">兑换</div>
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
        //加盟提示
        joinUs : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium">
                                僧僧已经收到您的信息，我们会尽快给您回复。
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        //规划师不在线
        offline : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    抱歉，我们的规划师不在线！ 
                                </div>
                                <div class="text-F6 medium">
                                    僧僧已经收到您的需求，我们将尽快与您联系！
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
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block medium rounded-20 mt-3 select_again">重新查询航班信息</div>
                        </div>`,
        //乘客类型发生变动，需要重新查询最优价格。
        peopleChange : `
                        <div class="text-center">
                            <div class="mb-3 text-left">
                                <div class="fs-19 text-F6 medium">
                                    乘机人数变更
                                </div>
                                <div class="text-66 mt-1 medium">
                                    乘客类型发生变动，需要重新查询最优价格。
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block medium rounded-20 mt-3 select_again">重新查询</div>
                        </div>
        `,
        //无法提供车辆
        noProvide : `
                        <div class="text-center">
                            <div class="mb-3 text-left">
                                <div class="fs-19 text-F6 medium">
                                    哎呀，抱歉，没有合适的车型
                                </div>
                                <div class="text-66 mt-1 medium">
                                    僧僧已收到您的需求，会有客服跟您用车！
                                </div>
                            </div>
                            <div class="btn btn-danger px-3 medium rounded-20 mt-2 points m-1">联系客服</div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block medium px-3 rounded-20 mt-2 m-1">返回主页</div>
                        </div>`,
        network :`
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    网络连接异常
                                </div>
                                <div class="text-F6 medium">
                                    请检查网络！
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        emailErr : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    邮箱有误
                                </div>
                                <div class="text-F6 medium">
                                    请检查邮箱格式是否正确
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        phoneErr : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    手机有误
                                </div>
                                <div class="text-F6 medium">
                                    请检查手机格式是否正确
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        sendErr : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    验证码发送失败
                                </div>
                                <div class="text-F6 medium">
                                    请检查网络连接是否正常
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        submitErr : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    订单提交失败
                                </div>
                                <div class="text-F6 medium">
                                    请检查网络连接是否正常
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        ticketErr : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    订单提交失败
                                </div>
                                <div class="text-F6 medium">
                                    机票信息有误
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        integralErr : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    积分兑换失败
                                </div>
                                <div class="text-F6 medium">
                                    积分必须是100的整数倍
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        balanceErr : `
                        <div class="text-center">
                            <div class="my-4 text-left">
                                <div class="text-F6 medium text-center">
                                    抱歉，您的余额不足，感谢您的支持！
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-1">关闭</div>
                        </div> `,
        discountsErr : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <!--<div class="text-F6 medium">-->
                                    <!--优惠券不存在-->
                                <!--</div>-->
                                <div class="text-F6 medium">
                                    抱歉，该优惠码不可用。如有疑问，请联系在线客服。
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        agree : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    请同意我们的服务条款！
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        used : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    您已经使用过该优惠券啦！
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        useless : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    该优惠券无法使用！
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        needLogin : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    请先登录留学僧
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div>`,
        fullMsg : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    请输入完整搜索条件
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        subscribeSuc : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    感谢您的订阅
                                </div>
                                <div class="text-F6 medium">
                                    僧僧会定期为您推送促销信息！
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        subscribeErr  : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    哎呀，很遗憾，您不愿意订阅僧僧推广
                                </div>
                                <div class="text-F6 medium">
                                    希望您今后多关注僧僧的促销信息。
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        cancelOrder : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium mt-4">
                                    您确定要取消此订单吗？
                                </div>
                            </div>
                            <div class="d-flex justify-content-center my-3">
                                <div class="btn btn-danger w-30 medium rounded-20 mt-2 mx-3 sureCancel">确定</div>
                                <div data-dismiss="modal" class="btn btn-outline-FE w-30 medium rounded-20 mt-2 mx-3">取消</div>
                            </div>
                        </div> 
        `,
        customMsg : `
                        <div class="text-center">
                            <div class="my-3 text-left">
                                <div class="text-F6 medium">
                                    ${toastMsg.value}
                                </div>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        getAward : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium">
                                领取成功，请到我的积分界面查询奖励！
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> 
        `,
        //旅行预算超出人数
        beyondnumber : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >自动估价人数超过15人，</span>
                                <span >请您直接咨询在线规划师或填写定制需求表。</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,

        //请选择出发日期
        chooseStartDay : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请选择出发日期</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请选择出发日期
        chooseEarlyStartDay : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请选择最早出发日</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请选择出发日期
        chooseLastStartDay : `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请选择最晚出发日</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请选择出发城市
        chooseeparture: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请选择出发城市</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //最少选择一人
        minAdult: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >最少选择一个成人</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请选择城市
        chooseCity: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请选择城市</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请选择房间
        chooseRoom: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >有团员未选择房间</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //住宿房间不能为空
        roomNoEmpty: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >住宿房间不能为空</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //房间数和人数不等
        roomNoEqualPerson: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >参团人与住房数不一致</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //暂无团期
        noBoutique: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >暂无团期</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //团购人数为0
        boutiqueNoPerson: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >团购人员数不能为0</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请选择出行天数
        chooseStartEndDay: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请输入出行天数范围</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请输入搜索内容
        enterSearchContent: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请输入搜索内容</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请选择分类
        chooseCategory: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >请选择分类</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,
        //请筛选内容已存在
        filterHistoryHas: `
                        <div class="text-center">
                            <div class="text-F6 my-4 medium d-flex flex-column">
                                <span >筛选内容已存在</span>
                            </div>
                            <div data-dismiss="modal" class="btn btn-outline-FE d-inline-block w-30 medium rounded-20 mt-2">关闭</div>
                        </div> `,

        
    }

    var str = `
                <div class="modal fade z-9999" id="alert" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded overflow-hidden">
                            <div class="text-FF bg-F6 px-4 py-2 d-flex justify-content-center align-items-center">
                                <span class="fs-18">禾蛙提示</span>
                            </div>
                            <div class="px-4 py-3 bg-sm-lxs wraper min-h-166">
                                
                            </div>
                        </div>
                    </div>
                </div>
            `

    $('body').append(str)
})()
