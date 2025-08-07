<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Staff extends \common\models\gii\Staff
{

    const STAFF_ROLE_SALES          = 1;      // 销售
    const STAFF_ROLE_PM             = 2;      // 项目经理

    const STAFF_ROLE_CONSULTANT     = 3;      // 招商顾问
    const STAFF_ROLE_ADVISOR        = 4;      // 投资顾问
    const STAFF_ROLE_FINANCE        = 10;      // 财务

    const STAFF_ROLE_ADMIN          = 99;       // 管理员
    const STAFF_ROLE_ADMIN_CHILD    = 98;       // 子管理员
    const STAFF_ROLE_ADMIN_PART     = 97;       // 分管理员

    const STAFF_STATUS_NORMAL       = 0;      // 正常
    const STAFF_STATUS_DISABLE      = 1;      // 禁用

    public static $staffRole2Name = [
        self::STAFF_ROLE_SALES         => '经纪人',
        self::STAFF_ROLE_PM            => '项目总监',
        self::STAFF_ROLE_CONSULTANT    => '招商顾问',
        self::STAFF_ROLE_ADVISOR       => '投资顾问',
        self::STAFF_ROLE_FINANCE       => '财务',
        self::STAFF_ROLE_ADMIN         => '管理员',
        self::STAFF_ROLE_ADMIN_CHILD   => '子管理员',
        self::STAFF_ROLE_ADMIN_PART    => '分管理员',
    ];

    public static $staffStatus2Name = [
        self::STAFF_STATUS_NORMAL       => '正常',
        self::STAFF_STATUS_DISABLE      => '禁用',
    ];

    const STAFF_RULE_DATA_REPORT_ALL        = 'report_all';            // 全部报备数
    const STAFF_RULE_DATA_REPORT_COUNT      = 'report_count';        // 报备统计
    const STAFF_RULE_DATA_REPORT_DRIFT      = 'report_drift';        // 报备变化率
    const STAFF_RULE_DATA_VISIT_ALL         = 'visit_all';              // 全部到访数
    const STAFF_RULE_DATA_VISIT_COUNT       = 'visit_count';          // 到访统计
    const STAFF_RULE_DATA_VISIT_DRIFT       = 'visit_drift';          // 到访变化率
    const STAFF_RULE_DATA_VISIT_RATE_ALL    = 'visit_rate_all';         // 整体到访率
    const STAFF_RULE_DATA_VISIT_RATE        = 'visit_rate';             // 到访率
    const STAFF_RULE_DATA_VISIT_RATE_DRIFT = 'visit_rate_drift';        // 到访率变化率
    const STAFF_RULE_DATA_PAYMENT_PAY       = 'payment_data_pay';      // 已付款数据
    const STAFF_RULE_DATA_PAYMENT_WAITPAY   = 'payment_data_wait_pay'; // 付款待付款数据
    const STAFF_RULE_DATA_PAYMENT_REFUND    = 'payment_data_refund'; // 退款数据
    const STAFF_RULE_DATA_PAYMENT_TOTAL     = 'payment_total';    // 付款总额数据
    const STAFF_RULE_DATA_SUBSCRIBED_COUNT = 'subscribed_data_count';  // 认购数量
    const STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA = 'subscribed_data_building_area';  // 认购面积
    const STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE = 'subscribed_data_sub_total_price'; // 认购总额
    const STAFF_RULE_DATA_SUBSCRIBED_TOTAL = 'subscribed_total'; // 认购总额数据

    const STAFF_RULE_SET_RULE = 'set_rule'; // 设置规则

    public static $staffRule2Name = [
        self::STAFF_RULE_DATA_REPORT_ALL          => '全部报备数',
        self::STAFF_RULE_DATA_REPORT_COUNT        => '报备统计',
        self::STAFF_RULE_DATA_REPORT_DRIFT        => '报备变化率',
        self::STAFF_RULE_DATA_VISIT_ALL           => '全部到访数',
        self::STAFF_RULE_DATA_VISIT_COUNT         => '到访统计',
        self::STAFF_RULE_DATA_VISIT_DRIFT         => '到访变化率',
        self::STAFF_RULE_DATA_VISIT_RATE_DRIFT    => '到访率变化率',
        self::STAFF_RULE_DATA_VISIT_RATE_ALL      => '到访率',
        self::STAFF_RULE_DATA_VISIT_RATE          => '整体到访率',
        self::STAFF_RULE_DATA_PAYMENT_PAY         => '已付款数据',
        self::STAFF_RULE_DATA_PAYMENT_WAITPAY     => '付款待付款数据',
        self::STAFF_RULE_DATA_PAYMENT_REFUND      => '退款数据',
        self::STAFF_RULE_DATA_PAYMENT_TOTAL       => '付款总额数据',
        self::STAFF_RULE_DATA_SUBSCRIBED_COUNT    => '认购数量',
        self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA => '认购面积',
        self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE => '认购总额',
        self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL    => '认购总额数据',
        self::STAFF_RULE_SET_RULE                 => '设置权限',
    ];

    public static $staffRole2rule = [
        self::STAFF_ROLE_ADMIN => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
            self::STAFF_RULE_SET_RULE,
        ],
        self::STAFF_ROLE_ADMIN_CHILD => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
        ],
        self::STAFF_ROLE_ADMIN_PART => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
        ],
        self::STAFF_ROLE_CONSULTANT => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
        ],
        self::STAFF_ROLE_ADVISOR => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
        ],
        self::STAFF_ROLE_PM => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
        ],
        self::STAFF_ROLE_SALES => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
        ],
        self::STAFF_ROLE_FINANCE => [
            self::STAFF_RULE_DATA_REPORT_ALL,
            self::STAFF_RULE_DATA_REPORT_COUNT,
            self::STAFF_RULE_DATA_REPORT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_ALL,
            self::STAFF_RULE_DATA_VISIT_COUNT,
            self::STAFF_RULE_DATA_VISIT_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_DRIFT,
            self::STAFF_RULE_DATA_VISIT_RATE_ALL,
            self::STAFF_RULE_DATA_VISIT_RATE,
            self::STAFF_RULE_DATA_PAYMENT_PAY,
            self::STAFF_RULE_DATA_PAYMENT_WAITPAY,
            self::STAFF_RULE_DATA_PAYMENT_REFUND,
            self::STAFF_RULE_DATA_PAYMENT_TOTAL,
            self::STAFF_RULE_DATA_SUBSCRIBED_COUNT,
            self::STAFF_RULE_DATA_SUBSCRIBED_BUILDING_AREA,
            self::STAFF_RULE_DATA_SUBSCRIBED_SUB_TOTAL_PRICE,
            self::STAFF_RULE_DATA_SUBSCRIBED_TOTAL,
        ],
    ];

    public function behaviors()
    {

        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }


}