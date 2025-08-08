<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/4/17
 * Time: 3:24 PM
 */

namespace common\helpers;


use common\models\Staff;

class Rules
{

    public static $ruleList = [
        'report_list' => Staff::STAFF_RULE_DATA_REPORT_ALL,
        'guest_list' => Staff::STAFF_RULE_DATA_VISIT_ALL,

    ];

    public static function checkStaffRule($staff, $ruleTag) {
        if (empty($staff) || empty($ruleTag)) {
            return false;
        }

        if (empty(self::$ruleList[$ruleTag])) {
            // 没有定义权限，就开放权限
            return true;
        }

        $staffRules = $staff->rules;
        if (!is_array($staffRules)) {
            $staffRules = json_decode($staffRules, true);
        }

        if (self::hasRule($ruleTag, $staffRules)) {
            return true;
        }

        return false;
    }

    public static function hasRule($ruleTag, $rules) {
        if (empty($rules)) {
            return false;
        }

        if (isset(self::$ruleList[$ruleTag]) && in_array(self::$ruleList[$ruleTag], $rules)) {
            return true;
        }

        return false;
    }

}