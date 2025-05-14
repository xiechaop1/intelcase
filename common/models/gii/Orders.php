<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $created_at
 * @property int $updated_at
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'post_id', 'alarm', 'order_status', 'created_at', 'updated_at'], 'integer'],
            [['snapshot'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'user_id'       => 'User Id',
            'company_id'    => 'Company Id',
            'post_id'       => 'Post Id',
            'alarm'         => 'Alarm',
            'snapshot'      => 'Snapshot',
            'order_status'  => 'Order Status',
            'created_at'    => 'Created At',
            'updated_at'    => 'Updated At',
        ];
    }
}
