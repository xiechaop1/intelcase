<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/26
 * Time: 9:41 PM
 */

namespace frontend\actions\passport;


use common\helpers\Email;
use common\helpers\Sms;
use common\models\Member;
use liyifei\base\actions\ApiAction;
use liyifei\base\helpers\Net;
use yii\web\BadRequestHttpException;
use yii;

class VerificationCode extends ApiAction
{
    // mobile: 短信发送 email: 邮箱发送
    public $way;

    // 1:注册 2:找回密码
    public $type;

    public function run()
    {
        $timeout = 300; // 秒
        if ($this->way == 'mobile') {
            $mobileSection = Net::post('mobile_section');
            $mobile = Net::post('mobile');

            $this->type = empty($this->type) ? \common\definitions\VerificationCode::TYPE_REGISTER : $this->type;

            switch ($this->type) {
//                case \common\definitions\VerificationCode::TYPE_CHANGE_MOBILE:
//                case \common\definitions\VerificationCode::TYPE_CHANGE_EMAIL:
                case \common\definitions\VerificationCode::TYPE_REGISTER:
//                case \common\definitions\VerificationCode::TYPE_LOGIN:
//                    if (Yii::$app->member->checkMobileExists($mobileSection, $mobile)) {
//                        return $this->fail(Yii::t('web', 'mobile already exists'));
//                    }
                    break;
                case \common\definitions\VerificationCode::TYPE_FINDBACK;
                    if (!Member::find()->where(['mobile' => $mobile, 'mobile_section' => $mobileSection])->exists()) {
                        return $this->fail('该手机号尚未绑定任何账号');
                    }
                    break;
                default:
                    break;
            }

            if (in_array($this->type, \common\definitions\VerificationCode::$checkExistsBeforeSend) && Yii::$app->member->checkMobileExists($mobileSection, $mobile)) {
                return $this->fail(Yii::t('web', 'mobile already exists'));
            }

            /**
             * @var \common\models\VerificationCode $vcAr
             */
            $vcAr = Yii::$app->verificationCode->generate([
                'mobileSection' => $mobileSection,
                'mobile'        => $mobile,
                'timeout'       => $timeout,
            ], $this->type);

            // 发送短信验证码
            try {

                if (!YII_DEBUG) {
                    $ret = Yii::$app->sms->sendSms('verificationCode', $mobile, [
                        $vcAr->code,
                        intval($timeout / 60) . '分钟',
                    ]);

                    $retJson = json_decode($ret, TRUE);
                    if (!isset($retJson['code']) || $retJson['code'] != '000000') {

                    }
                }
//                Sms::sendVerifycodeSms($mobile, $vcAr->code, '5分钟');
//                if ($mobileSection == '+86') {
//                    Yii::$app->internalVerifyCodeSms->sendSms($mobile, ['code' => $vcAr->code]);
//                } else {
//                    Yii::$app->externalVerifyCodeSms->sendSms(ltrim($mobileSection, '+') . $mobile, ['code' => $vcAr->code]);
//                }


            } catch (\Exception $e) {
                Yii::warning(ltrim($mobileSection, '+') . $mobile);
                Yii::warning('Send sms fail, error: ' . $e->getMessage());
            }


        } elseif ($this->way == 'email') {
            $email = Net::post('email');

            switch ($this->type) {
//                case \common\definitions\VerificationCode::TYPE_CHANGE_MOBILE:
//                case \common\definitions\VerificationCode::TYPE_CHANGE_EMAIL:
                case \common\definitions\VerificationCode::TYPE_REGISTER:
                    if (Yii::$app->member->checkEmailExists($email)) {
                        return $this->fail(Yii::t('web', 'email already exists'));
                    }
                    break;
                case \common\definitions\VerificationCode::TYPE_FINDBACK;
                    if (!Member::find()->where(['email' => $email])->exists()) {
                        return $this->fail('该邮箱尚未绑定任何账号');
                    }
                    break;
                default:
                    break;
            }

            $vcAr = Yii::$app->verificationCode->generate($email, $this->type);
            // 发送注册邮件
            $ret = Yii::$app->email->sendMail('verificationCode', $email, [
                $vcAr->code,
                intval($timeout / 60) . '分钟',
            ]);

            $retJson = json_decode($ret, TRUE);
            if (!isset($retJson['code']) || $retJson['code'] != '000000') {

            }
//            Email::verificationCode($email, $vcAr->code);

        } else {
            throw new BadRequestHttpException();
        }

        return $this->success();
    }
}