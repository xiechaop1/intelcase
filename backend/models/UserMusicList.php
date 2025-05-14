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

class UserMusicList extends \common\models\UserMusicList
{

    public function rules()
    {
        return [
            [['list_id', 'music_id', 'user_id', 'expire_time', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = \common\models\UserMusicList::find();
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

        $query->andFilterWhere(['user_id' => $params['id']]);
        $query->andFilterWhere(['list_type' => $params['list_type']]);

        return $dataProvider;
    }
}