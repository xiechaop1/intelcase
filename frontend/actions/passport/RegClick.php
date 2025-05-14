<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/5/19
 * Time: 8:43 PM
 */

namespace frontend\actions\passport;


use common\models\RegConvrate;
use liyifei\base\actions\ApiAction;
use Yii;

class RegClick extends ApiAction
{
    public function run()
    {
        $rate = new RegConvrate([
            'token' => Yii::$app->security->generateRandomString(64)
        ]);

        $rate->save();

        return $this->success(['token' => $rate->token]);
    }
}