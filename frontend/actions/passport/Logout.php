<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 4:15 PM
 */

namespace frontend\actions\passport;

use common\services\HewaApi;
use liyifei\base\actions\ApiAction;
use yii;

class Logout extends ApiAction
{
    public function run()
    {
        $logData = [
//            'source'        => HewaApi::LOGIN_SOURCE_PASSWORD,
        ];

        try {
            Yii::$app->hewaApi->setLogoutLog($logData);
        } catch (yii\web\BadRequestHttpException $e) {

        }

        $identity = Yii::$app->user->identity;
        $identity->web_session_id = '';
        $identity->wap_session_id = '';
        $identity->save();

        Yii::$app->user->logout();

        if (Yii::$app->request->isAjax) {
            return $this->success();
        } else {
            return $this->controller->redirect('/site/index');
        }
    }
}