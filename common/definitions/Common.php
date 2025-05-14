<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2018/5/28
 * Time: 下午3:51
 */

namespace common\definitions;


use liyifei\base\definitions\Api;

class Common extends Api
{
    // 正常
    const STATUS_ENABLE = 1;

    // 待审
    const STATUS_PENDING = 2;

    // 禁用
    const STATUS_DISABLE = 3;

    // 开启
    const ENABLE = 1;

    // 关闭
    const DISABLE = 2;

    // 登录短信
    const SMS_FOR_LOGIN = 1;

    // 注册短信
    const SMS_FOR_REGISTER = 2;

    // 男
    const SEX_MALE = 1;

    // 女
    const SEX_FEMALE = 2;

    // 需要绑定手机
    const NEED_BIND_MOBILE = 1001;

    // 需要绑定邮箱
    const NEED_BIND_EMAIL = 1002;

    // 账号已经创建
    const ACCOUNT_EXISTS = 1003;

    const ACCOUNT_AUDIT_FAIL    = 1008;

    const DIFFERENT_PASSWORD = 1104;

    const WRONG_PARAMETER      = 1105;

    // 删除
    const STATUS_DELETED = 100;

    // 正常
    const STATUS_NORMAL  = 0;

    const DATE_RANGE_ALL    = 0;
    const DATE_RANGE_7_DAYS = 7;
    const DATE_RANGE_30_DAYS = 30;
    const DATE_RANGE_90_DAYS = 90;
    const DATE_RANGE_180_DAYS = 180;

    public static $dateRange = [
        self::DATE_RANGE_ALL => '全部',
        self::DATE_RANGE_7_DAYS => '近7天',
        self::DATE_RANGE_30_DAYS => '近30天',
        self::DATE_RANGE_90_DAYS => '近90天',
        self::DATE_RANGE_180_DAYS => '近180天',
    ];

    public static $firstCity = [
        '北京市','上海市','重庆市','天津市'
    ];
}