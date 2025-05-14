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

class Log extends \common\models\Log
{

    public $singer_name;

    public function rules()
    {
        return [
            [['ret', 'op_desc' ], 'string'],
            [['op_code', 'user_id', 'op_status', 'music_id'], 'integer'],
            [[ 'created_at', 'updated_at',], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = \common\models\Log::find();
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

        if ($this->op_code >= 0) {
            $query->andFilterWhere([
                'op_code' => $this->op_code
            ]);
        }

        if (!empty($this->music_id)) {
            $query->andFilterWhere([
                'music_id' => $this->music_id
            ]);
        }

        if ($this->op_status >= 0) {
            $query->andFilterWhere([
                'op_status' => $this->op_status
            ]);
        }

//        $sql = $query->createCommand()->getRawSql();
//
//        var_dump($sql);exit;

        return $dataProvider;
    }
}