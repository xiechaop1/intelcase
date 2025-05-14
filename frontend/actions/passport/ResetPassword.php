<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 3:58 PM
 */

namespace frontend\actions\passport;


use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use liyifei\base\helpers\Net;
use yii\web\BadRequestHttpException;
use yii;

class ResetPassword extends ApiAction
{
    public function run()
    {
//        $way = Net::post('way');
        $password = Net::post('password');
        $password2 = Net::post('password2');
        $verificationCode = Net::post('verification_code');

//        if ($way == 'mobile') {
            $mobileSection = Net::post('mobile_section');
            $mobile = Net::post('mobile');
            $identity = MemberIdentity::findOne([
                'mobile_section' => $mobileSection,
                'mobile' => $mobile
            ]);

            $data = [
                'mobile_section' => $mobileSection,
                'mobile' => $mobile
            ];
//        } elseif ($way == 'email') {
////            $userName = Net::post('user_name');
//            $email = Net::post('email');
//            $identity = MemberIdentity::findOne(['email' => $email]);
//            $data = $email;
////            $data = [
////                'mobileSection' => $mobileSection,
////                'mobile' => $mobile
////            ];
//        } else {
//            throw new BadRequestHttpException();
//        }

        if (!$identity) {
            return $this->fail(Yii::t('web', 'account not exists'), 403);
        }

        if ($password != $password2) {
            return $this->fail(Yii::t('web', 'there is some differents with password and confirm'), 403);
        }

        $res = Yii::$app->verificationCode->validate($data, $verificationCode, \common\definitions\VerificationCode::TYPE_FINDBACK);

        if ($res === true) {
            $identity->password = Yii::$app->security->generatePasswordHash($password);

            $identity->save();

            return $this->success();
        } else {
            return $this->fail(Yii::t('web', 'mobile verifycode incorrect'));
        }
    }
}