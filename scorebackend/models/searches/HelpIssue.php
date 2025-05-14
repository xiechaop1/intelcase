<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/11
 * Time: 下午11:28
 */

namespace backend\models\searches;


use yii\data\ActiveDataProvider;

class HelpIssue extends \common\models\HelpIssue
{
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = \common\models\HelpIssue::find();
        $query->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['category_id' => $this->category_id]);

        return $dataProvider;
    }
}