<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/09
 * Time: 下午21:09
 */

namespace backend\models;
use yii\data\ActiveDataProvider;


// 设置文章推荐位置
class DataRecommend extends \common\models\DataRecommend
{


//    public function rules()
//    {
//        return [
//            [['data_id', 'data_type', 'pos'], 'integer'],
//            [['recommend_title'], 'string'],
//        ];
//    }

    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => false
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

        return $dataProvider;
    }
//
//    public function exec()
//    {
//        if ($this->validate()) {
//            foreach ($this->positions as $position) {
//                if (!\common\models\TagRelation::find()
//                    ->where(['tag_id' => $this->tagId, 'data_id' => $this->articleId, 'type' => TagRelation::TYPE_ARTICLE])
//                    ->exists()) {
//                    $tr = new \common\models\TagRelation([
//                        'tag_id' => $this->tagId,
//                        'data_id' => $this->articleId,
//                        'type' => TagRelation::TYPE_ARTICLE
//                    ]);
//
//                    if (!$tr->save()) {
//                        Yii::warning(json_encode($tr->errors));
//                    }
//                }
//            }
//        }
//    }
}