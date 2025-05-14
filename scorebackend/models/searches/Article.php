<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/3
 * Time: 下午8:46
 */

namespace backend\models\searches;


use common\models\ArticleCategory;
use common\models\ArticlePosition;
use yii\data\ActiveDataProvider;

// 文章筛选模型
class Article extends \common\models\Article
{

    public $position;


    public function rules()
    {
        return [
            [['category_id', 'position'], 'integer'],
            [['title'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = static::find()
            ->alias('t')
            ->with([
                'categories' => function ($query) {
                    $query->with('category');
                }
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
//        $query->orderBy(['created_at' => SORT_DESC]);

        if ($this->position) {
            $articleIds = ArticlePosition::find()->select('article_id')->where(['pos' => $this->position])->column();
            $query->andWhere(['t.id' => $articleIds]);
        }

        if ($this->category_id) {
            $subQuery = ArticleCategory::find()->select('article_id')->where(['category_id' => $this->category_id]);
            $query->andWhere(['t.id' => $subQuery]);
        }
        // $query->andFilterWhere(['t.category_id' => $this->category_id]);
        $query->andFilterWhere(['like', 't.title', $this->title]);


        return $dataProvider;
    }
}