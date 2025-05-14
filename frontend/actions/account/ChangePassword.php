<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/7
 * Time: 下午10:56
 */

namespace frontend\actions\account;


use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use liyifei\base\helpers\Net;
use common\definitions\VerificationCode;
use yii;

class ChangePassword extends ApiAction
{
    public function run()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            /**
             * @var MemberIdentity $identity
             */
            $identity = Yii::$app->user->identity;

            $oldPassword = Net::post('old_password');
            $newPassword = Net::post('password');
            $newPassword2 = Net::post('password2');
            $verificationCode = Net::post('verification_code');
            $data = [
                'mobile'            => $identity->mobile,
                'mobile_section'    => $identity->mobile_section,
            ];

            if ($newPassword != $newPassword2) {
                return $this->fail(Yii::t('web', 'there is some differents with password and confirm'), 403);
            }

            $res = Yii::$app->verificationCode->validate($data, $verificationCode, VerificationCode::TYPE_CHANGE_PASSWORD);

            if (!$res) {
                return $this->fail(Yii::t('web', 'verification code is not correct'), 403);
            }
//            if (!$identity->validatePassword($oldPassword)) {
//                return $this->fail(Yii::t('web', 'old password is not incorrect'), 403);
//            }

            $identity->password = Yii::$app->security->generatePasswordHash($newPassword);
            $identity->save();

            return $this->success();
        }

        return $this->controller->render('change-password', []);
    }
}