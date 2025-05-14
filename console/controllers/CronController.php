<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/26
 * Time: 2:29 AM
 */

namespace console\controllers;


use common\extensions\pay\easyeuro\Service;
use common\helpers\Email;
use common\helpers\Sms;
use common\models\BoutiqueGroup;
use common\models\Dropoff;
use common\models\Order;
use common\models\Pickup;
use common\models\TicketData;
use common\models\BoutiqueOrder;
use yii\console\Controller;
use yii;

class CronController extends Controller
{
    public function actionPassword()
    {
        $newPassword = 'music1234@';
        $res = Yii::$app->security->generatePasswordHash($newPassword);

        var_dump($res);
    }

}