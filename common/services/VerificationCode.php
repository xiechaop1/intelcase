<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/26
 * Time: 9:49 PM
 */

namespace common\services;


use common\definitions\Common;
use liyifei\base\helpers\Models;
use yii\base\Component;
use yii;

class VerificationCode extends Component
{
    /**
     * @desc 生成注册验证码
     * @param $data related data mobile or email
     * @param $type 注册或找回密码
     * @return \common\models\VerificationCode
     * @throws yii\base\UserException
     * @throws yii\web\BadRequestHttpException
     */
    public function generate($data, $type, $timeout = 300)
    {
        $verificationCode = YII_DEBUG ? '111111' : (string)rand(100000, 999999);

        if (is_array($data)) {
            $mobileSection = $data['mobileSection'];
            $mobile = $data['mobile'];

            $relatedData = "$mobileSection $mobile";
        } else {
            $relatedData = $data;
        }

        $model = new \common\models\VerificationCode([
            'type' => $type,
            'code' => $verificationCode,
            'expire_at' => time() + $timeout,
            'related_data' => $relatedData,
            'is_used' => Common::DISABLE
        ]);

        if ($model->save()) {
            return $model;
        } else {
            Yii::warning('Generate register verification code fail');
            Yii::warning(json_encode($model->errors));

            throw new yii\base\UserException(Models::getModelFirstError($model));
        }
    }

    /**
     * @desc 对比验证码
     * @param $data
     * @param $code
     * @param $type
     * @return bool|string
     */
    public function validate($data, $code, $type)
    {
        if (is_array($data)) {
            $mobileSection = $data['mobile_section'];
            $mobile = $data['mobile'];

            $relatedData = "$mobileSection $mobile";
        } else {
            $relatedData = $data;
        }

        /**
         * @var \common\models\VerificationCode $record
         */
        $record = \common\models\VerificationCode::find([
            'related_data' => $relatedData,
            'type' => $type
        ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(1)
            ->one();
        if (!$record) {
            return Yii::t('web', 'send verification code first');
        }

        if ($record->is_used == Common::ENABLE) {
            return Yii::t('web', 'verification code is not correct');
        }

        if ($record->expire_at < time()) {
            return Yii::t('web', 'verification code is out of date');
        }
        if ($record->code != $code) {
            return Yii::t('web', 'verification code is not correct');
        }

        $record->is_used = Common::ENABLE;
        $record->save();
        return true;
    }
}
