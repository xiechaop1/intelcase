<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 3:13 PM
 */

namespace frontend\actions\passport;


use common\definitions\Member;
use common\helpers\Client;
use common\models\MemberInvite;
use common\models\MemberInviteCode;
use common\services\HewaApi;
use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use yii\web\BadRequestHttpException;
use common\definitions\VerificationCode;
use yii;
use yii\web\Cookie;
use yii\web\Response;


class Login extends ApiAction
{
    public $mobileSection;

    public $mobile;

    public $email;

    public $userName;

    public $password;

    public $verificationCode;

    public $rememberPassword;

    public $rememberPhone;

    public $way;

    public $source;

    public $inviteCode;

    public function run()
    {
        if ($this->way == 'mobile') {
            $identity = MemberIdentity::findOne([
                'mobile_section' => $this->mobileSection,
                'mobile' => $this->mobile,
//                'status'    => \common\definitions\Common::STATUS_NORMAL,
            ]);
        } elseif ($this->way == 'verification_code') {
//            $identity = MemberIdentity::findOne(['email' => $this->email]);
            $identity = MemberIdentity::findOne([
                'mobile_section' => $this->mobileSection,
                'mobile' => $this->mobile,
//                'status'    => \common\definitions\Common::STATUS_NORMAL,
            ]);
        } elseif ($this->way == 'user_name') {
            $identity = MemberIdentity::findOne(['user_name' => $this->userName]);
        } elseif ($this->way == 'keep_login') {
            $cookie = \common\helpers\Cookie::getCookie('keep_login', '');
            $cookieJson = json_decode($cookie, true);
            if (!empty($cookieJson['id'])) {
                $identity = MemberIdentity::findOne($cookieJson['id']);
            }
        }else {
            throw new BadRequestHttpException();
        }

        switch ($this->way) {
            case 'mobile':
            case 'user_name':
                if (!$identity || $identity->validatePassword($this->password) !== true) {
                    $pointData = [
                        'login_fail_reason'   => 'username or password incorrect',
                    ];
                    Yii::$app->zhuge->put('loginpage_loginfail', $pointData);
                    return $this->fail(Yii::t('web', 'username or password incorrect'), 403);
                }
                break;
            case 'verification_code':
                $data = [
                    'mobile_section'    => $this->mobileSection,
                    'mobile'            => $this->mobile,
                ];
                if (!YII_DEBUG) {
                    if (Yii::$app->verificationCode->validate($data, $this->verificationCode, VerificationCode::TYPE_LOGIN) !== true) {

                        $pointData = [
                            'login_fail_reason'   => 'mobile verifycode incorrect',
                        ];
                        Yii::$app->zhuge->put('loginpage_loginfail', $pointData);
                        return $this->fail(Yii::t('web', 'mobile verifycode incorrect'), 403);
                    }
                }

                if (!$identity) {
                    $data = [
                        'mobile_section'    => $this->mobileSection,
                        'mobile'            => $this->mobile,
                        'type'              => \common\models\Member::MEMBER_TYPE_GUEST,
                        'source'            => !empty($this->source) ? $this->source : 'homepage',
//                'password'      => $password,
                        'email'         => !empty($this->email) ? $this->email : '',
//                        'true_name'     => $trueName,
                        'true_name'     => !empty($this->userName) ? $this->userName : '',
//                        'english_name'  => $englishName,
//                        'avatar'        => $avatar,
//                        'user_no'       => $userNo,
//                        'type'          => $type,
//                        'company_id'    => $companyId,
//                        'identity_no'   => $identityNo,
//                        'remark'        => $remark,
//                        'special'       => $special,
//                        'authorize'     => $authorize,
//
//                        'wx'            => $wx,
//                        'profession_type'   => $professionType,
//
//                        'legal_person'  => $legalPerson,
                    ];

                    $extra = [
                        'invite_code'       => $this->inviteCode,
                    ];
                    $identity = Yii::$app->member->register($data, '');

                    // 打点
                    $pointData = [
//            'cuid'      => $identity->id,
//            'eid'       => 'loginpage_login',
                        'userid'    => $identity->id,
                        'time'      => time(),
                        'username'  => $identity->true_name,
                        'role'      => \common\models\Member::$memberType2Name[$identity->type],
                        'mobile'    => $identity->mobile_section . ' ' . $identity->mobile,
                        'email'     => $identity->email,
                        'wechat'    => $identity->wx,
                        '注册端'   => 'PC',
                        '注册方式'     => '手机号'
                    ];
                    Yii::$app->zhuge->put('loginpage_registersuccess', $pointData);
//                    return $this->fail(Yii::t('web', 'account not exists'), 403);
                } else if (
//                    $identity->type == \common\models\Member::MEMBER_TYPE_GUEST
//                    &&
                (
                        empty($identity->company_id)
                        || (
                            !empty($identity->company_id) && $identity->member_status == \common\models\Member::MEMBER_STATUS_FAIL
                        )
                    )
                ) {
                    $extra = [
                        'invite_code'       => $this->inviteCode,
                    ];
                }

                if ($this->rememberPhone == 'true') {
                    \common\helpers\Cookie::setCookie('login_mobile', $this->mobile, 86400 * 365);
                    \common\helpers\Cookie::setCookie('login_mobile_section', $this->mobileSection, 86400 * 365);
                }

                break;
        }

        $inviteCode = null;
        $inviteCodeTimeout = 0;
        if (!empty($extra['invite_code'])) {
            $inviteCode = MemberInviteCode::findOne([
                'invite_code' => $extra['invite_code'],
            ]);

            if (!empty($inviteCode)) {

                if (
                    $inviteCode->created_at >= time() - MemberInvite::MAX_TIME
                    && $inviteCode->invite_ct < MemberInvite::MAX_INVITE) {

                    $inviteModel = MemberInvite::findOne([
                        'user_id' => $inviteCode->user_id,
                        'invite_uid' => $identity->id,
                    ]);

                    if (empty($inviteModel)) {
                        $inviteModel = new MemberInvite();
                        $inviteModel->user_id = $inviteCode->user_id;
                        $inviteModel->invite_code = $extra['invite_code'];
                        $inviteModel->invite_uid = $identity->id;
                        $inviteModel->save();

                        $inviteCode->invite_ct = $inviteCode->invite_ct + 1;
                        $inviteCode->save();


                        $inviteMember = $inviteCode->member;
                        $identity->company_id = $inviteMember->company_id;
                        $identity->audit_at = time();
                        $identity->member_status = \common\models\Member::MEMBER_STATUS_NORMAL;
                        $identity->type = \common\models\Member::MEMBER_TYPE_CONSULTANT;
                        $identity->save();
                    }
                } else {
                    $inviteCodeTimeout = 1;
                }
            }
        }
        
        if (empty($identity->company_id)) {
            // 没有猎头公司
//            return $this->fail(Yii::t('web', 'no consultant company'), 403);
        }

        Yii::$app->user->login($identity, Member::LOGIN_EXPIRE_AT);

        switch ($this->way) {
            case 'mobile':
//            case 'keep_login':

                $name = 'keep_login';
                if (
//                    $this->way == 'mobile'
//                    &&
                    $this->rememberPassword == 'true'
                ) {
                    $expire = Member::KEEP_LOGIN_EXPIRE_AT;
                    $value = json_encode(['id' => $identity->id,
                        'mobile_section' => $identity->mobile_section,
                        'mobile' => $identity->mobile,
                        'avatar' => $identity->avatar,
                        'true_name' => $identity->true_name,]);

                    \common\helpers\Cookie::setCookie($name, $value, $expire);
                } else {
                    \common\helpers\Cookie::unsetCookie($name);
                }

                break;
        }

        $identity->login_time = time();
        if (Client::isMobile()) {
            $identity->wap_session_id = Yii::$app->session->id;
        } else {
            $identity->web_session_id = Yii::$app->session->id;
        }
        $identity->save();

        switch ($this->way) {
            case 'mobile':
            case 'user_name':
                $source = HewaApi::LOGIN_SOURCE_PASSWORD;
                break;
            case 'keep_login':
                $source = HewaApi::LOGIN_SOURCE_KEEPLOGIN;
                break;
            case 'wechat':
                $source = HewaApi::LOGIN_SOURCE_WECHAT;
                break;
            case 'verification_code':
            default:
                $source = HewaApi::LOGIN_SOURCE_VERIFYCODE;
                break;
        }

        $sourceName = HewaApi::$loginSource2Name[$source];

        $logData = [
            'source'        => $source,
        ];

        Yii::$app->hewaApi->setLoginLog($logData);

        $pointData = [
//            'cuid'      => $identity->id,
//            'eid'       => 'loginpage_login',
            'userid'    => $identity->id,
            'time'      => time(),
            'loginport' => '',
            'browser'   => Yii::$app->request->userAgent,
            'loginmethod'   => $this->way,
            '登录方式'          => $sourceName,
            '登录端'           => 'PC',
            'freelogin'     => $this->rememberPassword == 'on' ? true : false,
            'companyid'     => $identity->company_id,
            'companyregtime'    => !empty($identity->consultantCompany->created_at) ? $identity->consultantCompany->created_at : 0,
        ];
        Yii::$app->zhuge->put('loginpage_loginsuccess', $pointData);

        Yii::$app->zhuge->setUserPoint('loginpage_loginsuccess');

        if (!empty($identity->consultantCompany)) {
            // 判断公司状态
            switch ($identity->consultantCompany->company_status) {
                case \common\models\ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT:
                    $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_PLATEFORM;
                    break;
                default:
                    $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NORMAL;
                    break;
            }
        } else {
            $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NO_BIND;
//        $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_PLATEFORM;
        }

        // 公司状态通过，看看个人状态
        if ($r == \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NORMAL) {
//            if ($identity->member_status == \common\models\Member::MEMBER_CONSULTANT_COMPANY_STATUS_WAIT_AUDIT) {
//                $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_ADMIN;
//            }
            switch ($identity->member_status) {
                case \common\models\Member::MEMBER_STATUS_WAIT_AUDIT:
                    $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_WAIT_ADMIN;
                    break;
                case \common\models\Member::MEMBER_STATUS_FAIL:
                    $r = \common\definitions\ConsultantCompany::STATUS_CON_COMPANY_NO_BIND;
                    break;
            }
        }

        return $this->success(['identity' => $identity, 'consultant_company_status' => $r, 'invite_code' => $inviteCode, 'invite_code_timeout' => $inviteCodeTimeout]);
    }

    public function getCookie($name, $defaultValue = null)
    {
        $cookies = Yii::$app->request->getCookies();
        if ($cookies) {
            return $cookies->getValue($name, $defaultValue);
        }

        return $defaultValue;
    }
}