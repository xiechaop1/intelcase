<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/7
 * Time: 下午9:20
 */

namespace frontend\controllers;


use liyifei\base\helpers\Net;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class AccountController extends Controller
{
    public $enableCsrfValidation = false;

    public $layout = '@frontend/views/layouts/main_w.php';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => ['get_invite_code'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => [
                    'information' => ['GET'],
                    'bind_mobile' => ['POST'],
                    'bind_email' => ['POST'],
                ],
            ]
        ]);
    }

    public function actions()
    {
        $request = \Yii::$app->request;

        return ArrayHelper::merge(parent::actions(), [
            // 个人中心首页
            'information' => 'frontend\actions\account\Information',
            'safe' => 'frontend\actions\account\Safe',
            // 详细资料
            'profile' => 'frontend\actions\account\Profile',
            // 下载电子合同
            'download-contract' => 'frontend\actions\account\DownloadContract',
            // 绑定手机
            'bind_mobile' => [
                'class' => 'frontend\actions\account\BindMobile',
//                'emailVerificationCode' => Net::post('email_verification_code'),
                'mobileSection' => Net::post('mobile_section'),
                'mobile' => Net::post('mobile'),
                'mobileVerificationCode' => Net::post('mobile_verification_code')
            ],
            // 绑定邮箱
            'bind_email' => [
                'class' => 'frontend\actions\account\BindEmail',
//                'mobileVerificationCode' => Net::post('mobile_verification_code'),
                'email' => Net::post('email'),
                'emailVerificationCode' => Net::post('email_verification_code')
            ],
            // 绑定猎企
            'bind_consultant_company' => [
                'class' => 'frontend\actions\account\BindConsultantCompany',
                'companyId' => Net::post('company_id'),
                'companyName' => Net::post('company_name'),
                'trueName' => Net::post('true_name'),
                'mail' => Net::post('mail'),
            ],
            'change_password' => 'frontend\actions\account\ChangePassword',
            'change_member'  => 'frontend\actions\account\ChangeMember',
            'change_member_special_tags'  => [
                'class'     => 'frontend\actions\account\ChangeMemberExtend',
                'action'    => 'special_tags',
            ],
            'change_member_industry'  => [
                'class'     => 'frontend\actions\account\ChangeMemberExtend',
                'action'    => 'member_industry',
            ],
            'change_member_city'  => [
                'class'     => 'frontend\actions\account\ChangeMemberExtend',
                'action'    => 'member_city',
            ],
            'change_member_post'  => [
                'class'     => 'frontend\actions\account\ChangeMemberExtend',
                'action'    => 'member_post',
            ],
            'create_invite_code' => [
                'class'     => 'frontend\actions\account\InviteCode',
                'action'    => 'create',
            ],
            'get_invite_code' => [
                'class'     => 'frontend\actions\account\InviteCode',
                'action'    => 'get',
            ],
            'get_invite_code_by_user' => [
                'class'     => 'frontend\actions\account\InviteCode',
                'action'    => 'get_by_user',
            ],
        ]);
    }
}