<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 3:13 PM
 */

namespace frontend\actions\passport;


use common\models\Tag;
use common\models\Member;
use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii;

class Webregister extends Action
{

    const REGISTER_TYPE_PERSON   = 1;
    const REGISTER_TYPE_COMPANY  = 2;

    public static $registerType2Tpl = [
        self::REGISTER_TYPE_PERSON  => 'register_person',
        self::REGISTER_TYPE_COMPANY => 'register_company',
    ];

    public function init(){
        parent::init();

        $this->controller->layout = '@frontend/views/layouts/main_register.php';
    }

    public function run()
    {
        $get = Yii::$app->request->get();
        if ( !empty($get['type'])) {
            $type = $get['type'];
        } else {
            $type = self::REGISTER_TYPE_PERSON;
        }

        $special = Tag::find()
            ->where(['tag_type' => Tag::TAG_TYPE_MEMBER_SPECIAL])
            ->all();

        $tpl = self::$registerType2Tpl[$type];

        $userId = !empty($get['id']) ? $get['id'] : !empty(Yii::$app->user) ? Yii::$app->user->id : 0;
        $user = null;
        $userSpecial = [];
        if (!empty($userId) ) {
            $user = Member::findOne($userId);

            if (!empty($user->special)) {
                foreach ($user->special as $us) {
                    $userSpecial[] = $us->tag->id;
                }
            }
        }

        $cityArea = Yii::$app->city->getAllCity();

        list($provinceList, $cityList) = $cityArea;

        $cityTree = [];

        $cityArea = [];
        foreach ($cityList as $pId => $cities) {
            foreach ($cities as $city) {
                $cityArea[$pId][] = [
                    'name' => $city->city_name,
                ];
            }
        }

        foreach ($provinceList as $province) {
            $cityTree[] = [
                'name'      => $province->city_name,
                'cityList' => !empty($cityArea[$province->id]) ? $cityArea[$province->id] : [],
            ];
        }


        return $this->controller->render($tpl, [
            'special_list'  => $special,
            'user'          => $user,
            'user_special'  => $userSpecial,
            'city_json'     => json_encode($cityTree, JSON_UNESCAPED_UNICODE),
            'source'        => yii\helpers\ArrayHelper::getValue($get, 'source', 'normal'),
        ]);

    }
}