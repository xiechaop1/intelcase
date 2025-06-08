<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/19
 * Time: 2:37 PM
 */

namespace common\models;


class Log extends \common\models\gii\Log
{

    const OP_STAUTS_ALL             = -1;   // 全部
    const OP_STATUS_SUCCESS         = 1;   // 成功
    const OP_STATUS_FAILED          = 0;    // 失败

    public static $opStatusMap = [
        self::OP_STAUTS_ALL         => '全部',
        self::OP_STATUS_SUCCESS     => '成功',
        self::OP_STATUS_FAILED      => '失败',
    ];

    const OP_CODE_PROJECT_ADD = 'project_add'; // 新建项目
    const OP_CODE_PROJECT_UPDATE = 'project_update'; // 更新项目
    const OP_CODE_REPORT_ADD = 'report_add'; // 新建报告
    const OP_CODE_REPORT_CONFIRM = 'report_confirm'; // 确认报告
    const OP_CODE_VISIT_ADD = 'visit_add'; // 新建拜访
    const OP_CODE_VISIT_CONFIRM = 'visit_confirm'; // 确认拜访
    const OP_CODE_SUB_ADD = 'sub_add'; // 新建签约
    const OP_CODE_SUB_CONFIRM_SIGN = 'sub_confirm_sign'; // 确认签约
    const OP_CODE_SUB_CONFIRM_DEAL = 'sub_confirm_deal'; // 确认成交
    const OP_CODE_PAYMENT_ADD = 'payment_add'; // 新建付款
    const OP_CODE_PAYMENT_CONFIRM = 'payment_confirm'; // 确认付款

    public static $opCodeMap = [
        self::OP_CODE_PROJECT_ADD => '新建项目',
        self::OP_CODE_PROJECT_UPDATE => '更新项目',
        self::OP_CODE_REPORT_ADD => '新建报告',
        self::OP_CODE_REPORT_CONFIRM => '确认报告',
        self::OP_CODE_VISIT_ADD => '新建拜访',
        self::OP_CODE_VISIT_CONFIRM => '确认拜访',
        self::OP_CODE_SUB_ADD => '新建签约',
        self::OP_CODE_SUB_CONFIRM_SIGN => '确认签约',
        self::OP_CODE_SUB_CONFIRM_DEAL => '确认成交',
        self::OP_CODE_PAYMENT_ADD => '新建付款',
        self::OP_CODE_PAYMENT_CONFIRM => '确认付款',
    ];


    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    // 获取用户信息
    public function getStaff()
    {
        return $this->hasOne('common\models\Staff', ['id' => 'staff_id']);
    }

    public function getCodeName()
    {
        return self::$opCodeMap[$this->op_code];
    }

}