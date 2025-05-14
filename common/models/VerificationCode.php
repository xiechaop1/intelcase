<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/26
 * Time: 9:53 PM
 */

namespace common\models;


class VerificationCode extends \common\models\gii\VerificationCode
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }
}