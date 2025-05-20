<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Project extends \common\models\gii\Project
{

    const PROJECT_CLASS_DEFAULT       = 0;      // 默认

    const PROJECT_TAR_PRODUCE_SHOP    = 1;      // 商铺

    public function behaviors()
    {

        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }


}