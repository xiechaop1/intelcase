<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/5/19
 * Time: 8:35 PM
 */

namespace backend\actions\site;


use common\models\Log;
use common\models\User;
use yii\base\Action;
use yii;

class Index extends Action
{
    public function run()
    {
        // set time zone
        date_default_timezone_set('Asia/Shanghai');

//        $month = Date('m');
//        $year = Date('Y');

//        if (in_array($month,[1,3,5,7,8,10,12])) {
//            $monMaxDay = 31;
//        } elseif (in_array($month,[4,6,9,11])) {
//            $monMaxDay = 30;
//        } else {
//            if ($year% 4 == 0 && $year % 100 != 0 || $year % 400 == 0) {
//                $monMaxDay = 29;
//            } else {
//                $monMaxDay = 28;
//            }
//        }
        $monMaxDay = 30;

        $startDateInt = !empty($_GET['start_date']) ? strtotime($_GET['start_date']) : strtotime('-30days');
        $endDateInt = !empty($_GET['end_date']) ? strtotime($_GET['end_date']) : time();

        $monMaxDay = (int)(($endDateInt - $startDateInt) / 86400);

        $totalCount = Yii::$app->db
            ->createCommand('select op_code, count(distinct user_id) as ct from o_log where op_status = 1 and created_at between '.$startDateInt.' and '.$endDateInt.' group by op_code');
        $totalCount = $totalCount->queryAll();

        $ret = [];
        foreach ($totalCount as $t) {
            $ret[$t['op_code']] = $t['ct'];
        }

        // 月均购买歌曲数
        $avgBuyMusic = Yii::$app->db
            ->createCommand('select count(distinct music_id) as ct from o_log where op_code = 105 and op_status = 1 and created_at between '.$startDateInt.' and '.$endDateInt);

        // 用户数
        $userCount = Yii::$app->db
            ->createCommand('select count(1) as ct from o_user where user_status in (0,2) and is_delete = 0');

        return $this->controller->render('index', [
            'ret' => $ret,
            'avgBuyMusic' => $avgBuyMusic->queryOne()['ct'],
            'userCount' => $userCount->queryOne()['ct'],
            'monMaxDay' => $monMaxDay,
        ]);
    }
}