<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/11/24
 * Time: 10:27 AM
 */

namespace common\helpers;

use common\models\Documents;
use common\models\Job;
use common\models\UserCompany;
use common\models\Orders;
use common\models\Member;

class No
{

    public static function create($modelName = 'Job', $prefix = '', $needDate = true, $basicNum = 100000) {

        switch ($modelName) {
            case 'Job':
                $model = Job::find();
                break;
            case 'Documents':
                $model = Documents::find();
                break;
            case 'UserCompany':
                $model = UserCompany::find();
                break;
            case 'Orders':
                $model = Orders::find();
                break;
            case 'Users':
                $model = Member::find();
                break;
        }

        $maxRet = $model
            ->select('id')
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->all();

        $maxId = !empty($maxRet[0]->id) ? $maxRet[0]->id : 0;

        $retId = !empty($maxId) ? $maxId + 1 : 1;

        if ($needDate) {
            $dateStr = Date('Ymd');
        } else {
            $dateStr = '';
        }

        $ret = $prefix . $dateStr . ($basicNum + $retId);

        return $ret;
    }
}