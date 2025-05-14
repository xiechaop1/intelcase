<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%recommend}}".
 *
 * @property int $created_at
 * @property int $updated_at
 */
class RecommendHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%recommend_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recommend_id', 'recommend_filter', 'recommend_status', 'is_latest', 'created_at', 'updated_at'], 'integer'],
            [['recommend_info', 'recommend_date'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recommend_id' => 'Recommend Id',
            'recommend_filter'  => 'Recommend Filter',
            'recommend_status'  => 'Recommend Status',
            'recommend_info'  => 'Recommend Info',
            'recommend_date'  => 'Recommend Date',
            'is_latest' => 'Is Latest',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
