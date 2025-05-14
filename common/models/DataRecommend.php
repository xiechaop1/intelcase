<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/09
 * Time: 9:00 PM
 */

namespace common\models;


class DataRecommend extends \common\models\gii\DataRecommend
{

    const TYPE_RECOMMEND_BOUTIQUE_PRODUCT = 1;

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function getBoutiqueProduct()
    {
        return $this->hasOne('common\models\BoutiqueProduct', ['data_id' => 'id']);
    }
}