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

class Banner extends \common\models\Banner
{

    public $online_time_str;
    public $offline_time_str;

    public function rules()
    {
        return [
            [['sort', 'banner_status', 'online_time', 'offline_time', 'created_at', 'updated_at'], 'integer'],
            [['page', 'subject', 'target', 'image'], 'string'],
        ];
    }

    public function exec() {

        return $this->save();
    }

    public function search($params)
    {
        $query = \common\models\Music::find();
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

        $query->andFilterWhere([
            'like', 'title', $this->title
        ]);


        return $dataProvider;
    }
}