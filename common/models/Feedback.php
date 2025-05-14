<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/11
 * Time: 5:25 PM
 */

namespace common\models;


class Feedback extends \common\models\gii\Feedback
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