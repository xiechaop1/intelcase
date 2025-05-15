<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Subscribed extends \common\models\gii\Subscribed
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