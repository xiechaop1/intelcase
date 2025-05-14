<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/11
 * Time: 5:03 PM
 */

namespace common\models;


class HelpIssue extends \common\models\gii\HelpIssue
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function getCategory()
    {
        return $this->hasOne('common\models\Category', ['id' => 'category_id']);
    }
}