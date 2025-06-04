<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2018/5/28
 * Time: 下午3:51
 */

namespace common\definitions;


use liyifei\base\definitions\Api;

class Privilege extends Api
{
    const PROJECT_ADD = 'project_add';
    const REPORT_CONFIRM = 'report_confirm';
    const VISIT_CONFIRM = 'visit_confirm';
    const SUB_CONFIRM_SIGN = 'sub_confirm_sign';
    const SUB_CONFIRM_DEAL = 'sub_confirm_deal';
    const PAYMENT_CONFIRM = 'payment_confirm';
}