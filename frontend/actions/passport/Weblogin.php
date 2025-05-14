<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 3:13 PM
 */

namespace frontend\actions\passport;


use common\helpers\Cookie;
use common\models\Member;
use common\models\MemberInvite;
use common\models\MemberInviteCode;
use common\services\HewaApi;
use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii;

class Weblogin extends Action
{

    public function run()
    {

//        $params = [
//            'activity_position' => HewaApi::BANNER_POSITION_LOGIN,
//            'check_time'        => strtotime('now'),
//            'banner_status'     => HewaApi::BANNER_STATUS_OPEN,
//        ];
//        $bannerData = Yii::$app->hewaApi->getBanner($params);

        $banners = !empty($bannerData['data']) ? $bannerData['data'] : [];

        $keepLogin = Cookie::getCookie('keep_login', '');
        if (!empty($keepLogin)) {
            $keepLoginJson = json_decode($keepLogin, true);
        } else {
            $keepLoginJson = '';
        }

        $refUrl = Yii::$app->request->get('ref');

        $inviteCode = Yii::$app->request->get('invite_code');

        $memberInviteCode = null;
        if (!empty($inviteCode)) {
            $memberInviteCode = MemberInviteCode::find()
                ->where([
                    'invite_code' => $inviteCode
                ])
                ->orderBy(['id' => SORT_DESC])
                ->one();
        }

        $isPass = 0;
        if (!empty($memberInviteCode)) {
            $isPass = 0;
            if ($memberInviteCode->created_at < time() - MemberInvite::MAX_TIME
                || $memberInviteCode->invite_ct >= MemberInvite::MAX_INVITE
            ) {
                $memberInviteCode = null;
                $isPass = 1;
            }
        }

//var_dump($banners);
        return $this->controller->render('login', [
            'banners'       => $banners,
            'keep_login'    => $keepLoginJson,
            'ref_url'       => $refUrl,
            'source'        => Yii::$app->request->get('source', ''),
            'member_invite_code' => $memberInviteCode,
            'invite_code_timeout'       => $isPass,
        ]);

    }
}