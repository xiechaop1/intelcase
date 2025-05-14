<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/26
 * Time: 9:38 PM
 */

namespace frontend\actions\passport;


use common\definitions\Common;
use common\definitions\VerificationCode;
use common\helpers\No;
use common\models\ConsultantCompany;
use common\models\Member;
use common\models\RegConvrate;
use common\models\UserCompany;
use liyifei\base\actions\ApiAction;
use liyifei\base\helpers\Net;
use yii;

class CheckMobile extends ApiAction
{
    public function run()
    {
        $mobileSection = Net::post('mobile_section');
        $mobile         = Net::post('mobile');

        if (substr($mobileSection, 0, 1) != '+') {
            $mobileSection = '+' . ltrim($mobileSection);
        }

        if (!$mobile || !$mobileSection) {
            return $this->fail(Yii::t('web', 'wrong mobile or mobile section'));
        }

        $verificationCode = Net::post('verificationCode');

        $data = [
            'mobile_section'    => $mobileSection,
            'mobile'            => $mobile,
        ];

        if (!YII_DEBUG) {
            $res = Yii::$app->verificationCode->validate($data, $verificationCode, VerificationCode::TYPE_REGISTER);
        } else {
            // 测试环境去掉验证码校验
//            $res = true;
            $res = Yii::$app->verificationCode->validate($data, $verificationCode, VerificationCode::TYPE_REGISTER);
        }

        if ($res !== true) {
            return $this->fail($res);
        }

        $ret = Yii::$app->member->checkMobileExists($mobileSection, $mobile);
        if ($ret) {
            $user = Member::findOne([
                'mobile_section'    => $mobileSection,
                'mobile'            => $mobile,
            ]);
//            if (in_array($user->member_status,
//                [
//                    Member::MEMBER_STATUS_NORMAL,
//                    Member::MEMBER_STATUS_WAIT_AUDIT,
//                ])) {
//                return $this->fail(Yii::t('web', 'mobile already exists'), Common::ACCOUNT_EXISTS, $user);
//            }
            switch ($user->member_status) {
                case Member::MEMBER_STATUS_NORMAL:
                    return $this->fail(Yii::t('web', 'mobile already exists'), Common::ACCOUNT_EXISTS, $user);
                    break;
                case Member::MEMBER_STATUS_WAIT_AUDIT:
                    return $this->fail(Yii::t('web', 'mobile is waiting for audit'), Common::ACCOUNT_EXISTS, $user);
                    break;
                case Member::MEMBER_STATUS_FAIL:
                    return $this->fail(Yii::t('web', 'mobile is fail'), Common::ACCOUNT_AUDIT_FAIL, $user);
                    break;
            }
            return $this->success($user);
        }

        return $this->success($ret);


    }
}