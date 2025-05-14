<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 3:13 PM
 */

namespace frontend\actions\passport;


use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii;

class Webresetpassword extends Action
{

    public function init(){
        parent::init();

        $this->controller->layout = '@frontend/views/layouts/main_register.php';
    }

    public function run()
    {
        $tpl = 'reset_password';

        return $this->controller->render($tpl, []);

    }
}