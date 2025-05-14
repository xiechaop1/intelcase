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

class Register extends ApiAction
{
    public $type;

    public $token;

    public function run()
    {
        $userId = Net::post('user_id');

        $password = Net::post('password');
        $passwordAgain = Net::post('password');

        $mobileSection = Net::post('mobile_section');
        $mobile         = Net::post('mobile');
        $email          = Net::post('email');
        $userName       = Net::post('user_name');
        $trueName       = Net::post('true_name');
        $englishName    = Net::post('english_name');
        $avatar         = Net::post('avatar');
        $companyId      = Net::post('company_id');
        $companyName    = Net::post('company_name');
        $userNo         = Net::post('user_no');
        $type           = Net::post('type');
        $remark         = Net::post('remark');
        $identityNo     = Net::post('identity_no');
        $authorize      = Net::post('authorize');

        $wx             = Net::post('wx');
        $professionType = Net::post('profession_type');

        $special        = Net::post('member_special');

        $legalPerson    = Net::post('legal_person');

        $source         = Net::post('source');

        $companyPosition = Net::post('company_position');

        $post = Yii::$app->request->post();

        if (!isset(Member::$memberType2Name[$type])) {
            throw new yii\web\BadRequestHttpException();
        }

        if ($password != $passwordAgain && $type == Member::MEMBER_TYPE_CONSULTANT) {
            return $this->fail(Yii::t('web', 'password is different with twice'), Common::DIFFERENT_PASSWORD);
        }

        if (Yii::$app->member->checkMobileExists($mobileSection, $mobile)) {
            $user = Member::findOne([
                'mobile_section'    => $mobileSection,
                'mobile'            => $mobile,
            ]);
            if ($user->member_status == Member::MEMBER_STATUS_NORMAL
                || $user->member_status == Member::MEMBER_STATUS_WAIT_AUDIT
            ) {
                return $this->fail(Yii::t('web', 'mobile already exists'), Common::ACCOUNT_EXISTS, $user);
            }
        }

        if (Yii::$app->member->checkUserExists($trueName)) {
            $user = Member::findOne([
                'true_name' => $trueName,
            ]);

            if ($user->member_status == Member::MEMBER_STATUS_NORMAL) {
                return $this->fail(Yii::t('web', 'username already exists'), Common::ACCOUNT_EXISTS, $user);
            }
        }

//        if (Yii::$app->member->checkEmailExists($email) && $type == Member::MEMBER_TYPE_CONSULTANT) {
//            $user = Member::findOne([
//                'email' => $email,
//            ]);
//            if ($user->member_status == Member::MEMBER_STATUS_NORMAL) {
//                return $this->fail(Yii::t('web', 'email already exists'), Common::ACCOUNT_EXISTS, $user);
//            }
//        }

        if ( !empty($companyId) ) {
            $companyModel = ConsultantCompany::findOne(['id' => $companyId]);
            if (empty($companyModel)) {
                return $this->fail(Yii::t('web', 'choose a unknown company'), Common::WRONG_PARAMETER);
            }
        }

//        if ( empty($companyId) ) {
//            if (!empty($companyName)) {
//                // 如果没有给任何猎头公司信息,那么是新管理员,并且创建公司
//                // 公司名称不为空,说明从公司注册页面进入
//                $type = Member::MEMBER_TYPE_ADMIN;
//            }
//        } else {
//            $companyModel = ConsultantCompany::findOne(['id' => $companyId]);
//            if (empty($companyModel)) {
//                return $this->fail(Yii::t('web', 'choose a unknown company'), Common::WRONG_PARAMETER);
//            }
//
//            if (empty($companyModel->adminUser)) {
//                $type = Member::MEMBER_TYPE_ADMIN;
//            } else {
//                $type = Member::MEMBER_TYPE_CONSULTANT;
//            }
//        }

        if ($type == Member::MEMBER_TYPE_ADMIN) {
            if (!Yii::$app->member->validation_filter_id_card($identityNo)) {
//                return $this->fail(Yii::t('web', 'identity wrong1'), Common::WRONG_PARAMETER);
            }

            if (!Yii::$app->member->validateUserName($userName)) {
//                return $this->fail(Yii::t('web', 'identity wrong2'), Common::WRONG_PARAMETER);
            }

            if (empty($email)) {
                return $this->fail(Yii::t('web', 'email is empty'), Common::WRONG_PARAMETER);
            }

            if (!empty($post['company_name'])) {
                $companyInfo = ConsultantCompany::findOne([
                    'company_name' => $post['company_name'],
                    'company_status' => [
                        ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT,
                        ConsultantCompany::CONSULTANT_COMPANY_STATUS_NORMAL,
                    ],
                ]);

                if (!empty($companyInfo)) {
                    return $this->fail(Yii::t('web', 'consultant company exists'), Common::WRONG_PARAMETER);
                }
            }

//            if (!Yii::$app->member->isAllChinese($trueName)) {
//                return $this->fail(Yii::t('web', 'truename must be chinese'), Common::WRONG_PARAMETER);
//            }
        }

        $data = [
            'user_id'       => $userId,
            'mobile_section' => $mobileSection,
            'mobile'        => $mobile,
//                'password'      => $password,
            'email'         => $email,
            'true_name'     => $trueName,
            'user_name'     => $userName,
            'english_name'  => $englishName,
            'avatar'        => $avatar,
            'user_no'       => $userNo,
            'type'          => $type,
            'company_id'    => $companyId,
            'identity_no'   => $identityNo,
            'remark'        => $remark,
            'special'       => $special,
            'authorize'     => $authorize,

            'wx'            => $wx,
            'profession_type'   => $professionType,

            'legal_person'  => $legalPerson,

            'source'        => $source,
        ];

        $verificationCode = Net::post('verificationCode');

        if (!YII_DEBUG && 1 != 1) {
            $res = Yii::$app->verificationCode->validate($data, $verificationCode, VerificationCode::TYPE_REGISTER);
        } else {
            // 测试环境去掉验证码校验
            $res = true;
        }

        if ($res === true) {
            if (!empty($user)) {
                $data['user_id'] = $user->id;
            }
            if (empty($data['user_no'])) {
                $data['user_no'] = No::create('Users', 'U');
            }
//            var_dump($data);exit;
            $member = Yii::$app->member->register($data, $password);

            if (empty($member->errors)) {

                // 保存公司信息
                if ($type == Member::MEMBER_TYPE_ADMIN) {
                    $companyModel = ConsultantCompany::findOne([
                        'id' => $member->company_id,
//                        'company_status' => [
//                            ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT,
//                            ConsultantCompany::CONSULTANT_COMPANY_STATUS_NORMAL,
//                        ],
                    ]);

                    if (empty($companyModel) && !empty($post['company_name'])) {
                        $companyModel = ConsultantCompany::find()
                        ->where([
                                'company_name' => $post['company_name']
                            ])
                            ->orderBy([
                                'id' => SORT_DESC,
                            ])
                            ->one();
                        if (empty($companyModel)) {
                            $companyModel = new ConsultantCompany();
                        }
                        $companyModel->load($post, '');
//                        $companyModel->user_id = $member->id;
                        $companyModel->company_status = ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT;

                    }
                    if (in_array($companyModel->company_status, [
                        ConsultantCompany::CONSULTANT_COMPANY_STATUS_FAIL
                    ])) {
                        $companyModel->company_status = ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT;
                    }
                    $companyModel->user_id = $member->id;

//                    $companyModel = new ConsultantCompany();
//                    $companyModel->load(Yii::$app->request->post);
//                    $companyModel->user_id = $member->id;
//                    $companyModel->company_status = ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT;
                    $companyModel->exec();

                    $member->company_id = $companyModel->id;
                    $member->save();
                } else {
                    // 非MEMBER_TYPE_ADMIN,说明是新普通成员加入
                    // 新成员加入,发送消息
                    $companyModel = ConsultantCompany::findOne([
                        'id' => $member->company_id,
                        'company_status' => [
//                                ConsultantCompany::CONSULTANT_COMPANY_STATUS_WAIT_AUDIT,
                            ConsultantCompany::CONSULTANT_COMPANY_STATUS_NORMAL,
                        ],
                    ]);
                    if (!empty($companyModel)) {
                        $tag = 'bindNewMemberToConsultantCompany';
                        $receiver = $companyModel->adminUser;
                        $params = [
                            'memberName' => $member->true_name,
                            'true_name' => $member->true_name,
                        ];
                        $ret = Yii::$app->notify->send($tag, $receiver, $params);
                    }
//                        else {
//                            return $this->fail(Yii::t('web', '注册失败，公司不存在'));
//                        }
                }


                Yii::$app->user->login($member, 86400);

                // 打点
                $pointData = [
//            'cuid'      => $identity->id,
//            'eid'       => 'loginpage_login',
                    'userid'    => $member->id,
                    'time'      => time(),
                    'username'  => $member->true_name,
                    'role'      => Member::$memberType2Name[$member->type],
                    'mobile'    => $member->mobile_section . ' ' . $member->mobile,
                    'email'     => $member->email,
                    'wechat'    => $member->wx,
                    '注册端'   => 'PC',
                    '注册方式'     => '手机号'
                ];
                Yii::$app->zhuge->put('loginpage_registersuccess', $pointData);

                Yii::$app->zhuge->setUserPoint('loginpage_registersuccess');

                return $this->success($member);
            } else {
//                var_dump($member->errors);exit;
                $errText = is_array($member->errors) ? current($member->errors) : $member->errors;
                return $this->fail(Yii::t('web', '注册失败，' . $errText[0]));
            }
//            }

        } else {
            return $this->fail($res);
        }
    }
}