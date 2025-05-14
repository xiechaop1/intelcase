<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/26
 * Time: 9:54 PM
 */

namespace common\definitions;


class VerificationCode
{
    // 注册验证码
    const TYPE_REGISTER = 1;

    // 找回密码
    const TYPE_FINDBACK = 2;

    // 更换手机
    const TYPE_CHANGE_MOBILE = 3;

    // 邮箱验证码 验证更换手机
    const TYPE_CHANGE_MOBILE_VALIDATE = 4;

    // 更换邮箱
    const TYPE_CHANGE_EMAIL = 5;

    // 手机验证码 验证更换邮箱
    const TYPE_CHANGE_EMAIL_VALIDATE = 6;

    // 更换密码
    const TYPE_CHANGE_PASSWORD = 7;

    const TYPE_LOGIN    = 10;

    static $checkExistsBeforeSend = [
//        VerificationCode::TYPE_CHANGE_MOBILE,
//        VerificationCode::TYPE_CHANGE_EMAIL,
//        VerificationCode::TYPE_REGISTER,
//        VerificationCode::TYPE_LOGIN,
    ];
}