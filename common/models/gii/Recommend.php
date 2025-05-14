<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%recommend}}".
 *
 * @property int $created_at
 * @property int $updated_at
 */
class Recommend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%recommend}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'post_id', 'document_id', 'order_id', 'alarm', 'recommend_filter', 'recommend_filter_detail', 'recommend_status', 'created_at', 'updated_at'], 'integer'],
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
            'user_id' => 'User Id',
            'company_id' => 'Company Id',
            'post_id' => 'Post Id',
            'order_id'  => 'Order Id',
            'document_id'   => 'Document Id',
            'alarm' => 'alarm',
            'recommend_filter'  => 'Recommend Filter',
            'recommend_filter_detail'  => 'Recommend Filter Detail',
            'recommend_status'  => 'Recommend Status',
            'recommend_info'  => 'Recommend Info',
            'recommend_date'  => 'Recommend Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
