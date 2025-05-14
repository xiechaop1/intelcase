<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/4
 * Time: 下午9:30
 */

namespace backend\models\searches;


use common\definitions\Common;
use yii\data\ActiveDataProvider;

class Tag extends \common\models\Tag
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
            [['is_recommend'], 'in', 'range' => [Common::ENABLE, Common::DISABLE]]
        ];
    }

    public function search($params)
    {

        $query = static::find();
        $queryParams = $params;
        unset($queryParams['page']);
        $query->andFilterWhere($queryParams);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['is_recommend' => $this->is_recommend]);

        return $dataProvider;
    }
}