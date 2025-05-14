<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/17
 * Time: 3:24 PM
 */

namespace common\helpers;

use yii;
use common\definitions\Admin;

class AdminRole
{
    public static function checkRole($ruleRole)
    {
        $role = Yii::$app->user->identity->role;
        if ($role == Admin::ROLE_PLATFORM) {
            return true;
        }

        if ($role == $ruleRole) {
            return true;
        }

        return false;

    }




}