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

class MusicVideo extends \common\models\MusicVideo
{
    public $date_range;

    public function rules()
    {
        return [
            [['music_title', 'video_title', 'video_info', 'filename', 'video_url', 'file_type'], 'string'],
            [[ 'status'], 'integer'],
            [['is_delete', 'created_at', 'updated_at',], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = \common\models\MusicVideo::find();
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

        $query->andFilterWhere([
            'like', 'music_title', $this->music_title
        ]);

        $query->andFilterWhere([
            'like', 'video_title', $this->video_title
        ]);


        if ($this->is_delete !== null
            && $this->is_delete >= 0
        ) {
            $query->andFilterWhere([
                'is_delete' => $this->is_delete
            ]);
        }

        if (!empty($params['Video']['date_range'])) {
            $query->andFilterWhere([
                '>', 'created_at', time() - $params['Video']['date_range'] * 24 * 3600
            ]);
        }



        return $dataProvider;
    }
}