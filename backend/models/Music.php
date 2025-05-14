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

class Music extends \common\models\Music
{
    public $date_range;

    public $category_ids;

    public function rules()
    {
        return [
            [['title', 'singer', 'composer', 'lyricist', 'duration', 'comment', 'lyric', 'lyric_url', 'resource_download_url', 'resource_download_file', 'chorus_start_time', 'chorus_end_time', 'verse_url', 'chorus_url', 'background_image', 'cover_image', 'cover_thumbnail', 'music_rate'], 'string'],
            [['singer_id', 'upload_user_id', 'music_type', 'music_status', 'op_user_id', 'status'], 'integer'],
            [['price'], 'number'],
            [['category_ids'], 'each', 'rule' => ['integer']],
            [['title'], 'required'],
            [['is_delete', 'created_at', 'updated_at',], 'integer'],
        ];
    }

    public function search($params, $musicType = \common\models\Music::MUSIC_TYPE_NORMAL)
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

//        if (!$this->validate()) {
//            return $dataProvider;
//        }

        if (empty($musicType)) {
            $musicType = [
                Music::MUSIC_TYPE_NORMAL,
                Music::MUSIC_TYPE_UNAUTHORIZATION
            ];
        } else {
//            $musicType = $this->music_type;
        }

        $query->andFilterWhere([
            'music_type' => $musicType
        ]);

        $query->andFilterWhere([
            'like', 'title', $this->title
        ]);

        $query->andFilterWhere([
            'like', 'singer', $this->singer
        ]);
        $query->andFilterWhere([
            'like', 'composer', $this->composer
        ]);
        $query->andFilterWhere([
            'like', 'lyricist', $this->lyricist
        ]);
        $query->andFilterWhere([
            'like', 'lyric', $this->lyric
        ]);

        if ($this->music_status !== null
        && $this->music_status >= 0
        ) {
            $query->andFilterWhere([
                'music_status' => $this->music_status
            ]);
        } else {
            $query->andFilterWhere([
                'music_status' => [
                    Music::MUSIC_STATUS_NORMAL,
                    Music::MUSIC_STATUS_LOCK
                ]
            ]);
        }

        if ($this->is_delete !== null
            && $this->is_delete >= 0
        ) {
            $query->andFilterWhere([
                'is_delete' => $this->is_delete
            ]);
        }

        if (!empty($params['Music']['date_range'])) {
            $query->andFilterWhere([
                '>', 'created_at', time() - $params['Music']['date_range'] * 24 * 3600
            ]);
        }

        if (!empty($params['Music']['category_id'])) {
            $query->joinWith('categories');
            $query->andFilterWhere([
                'o_music_category.category_id' => $params['Music']['category_id']
            ]);
        }

//        $sql = $query->createCommand()->getRawSql();
//        var_dump($sql);die;


        return $dataProvider;
    }
}