<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午4:44
 */

namespace backend\models;


use yii\data\ActiveDataProvider;

class Admin extends \common\models\Admin
{
    public function search($params = null)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false
        ]);

        if (!empty($params['Admin']['role'])
            && $params['Admin']['role'] >= 0
        ) {
            $query->andWhere(['role' => $params['Admin']['role']]);
        }

//        $query->orderBy(['sort' => SORT_ASC]);

        return $dataProvider;
    }
}