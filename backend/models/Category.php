<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/02
 * Time: 下午5:30
 */

namespace backend\models;


use common\definitions\Common;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class Category extends \common\models\Category
{


    public function rules()
    {
        return [
            [['sort_by', 'is_delete', 'tab_sort_by', 'created_at', 'updated_at'], 'integer'],
            [['category_name', ], 'string', 'max' => 100],
            [['category_image', ], 'string', 'max' => 500],
        ];
    }

    public function exec()
    {
        return $this->save();
    }

    public function search($params)
    {
        $query = \common\models\Category::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => false
            'sort' => [
                'defaultOrder' => [
                    'sort_by' => SORT_ASC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'like', 'category_name', $this->category_name
        ]);


        return $dataProvider;
    }
}