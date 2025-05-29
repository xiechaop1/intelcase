<?php
/**
 * Created by PhpStorm.
 * User: Choice
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