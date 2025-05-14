<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%search_history}}".
 *
 * @property int $id
 * @property int $type 1:cms搜索
 * @property int $uid 用户id
 * @property string $keyword
 * @property int $times 检索次数
 * @property int $created_at
 * @property int $updated_at
 */
class SearchHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%search_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'times', 'created_at', 'updated_at'], 'integer'],
//            [['keyword'], 'required'],
//            [['keyword'], 'string', 'max' => 64],
            [['title', 'uri'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'user_id' => 'User Id',
//            'keyword' => 'Keyword',
            'title' => 'Title',
            'uri'   => 'Uri',
            'times' => 'Times',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
