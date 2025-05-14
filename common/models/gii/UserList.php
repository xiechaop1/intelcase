<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%user_list}}".
 *
 * @property int $created_at
 * @property int $updated_at
 */
class UserList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['list_type', 'user_id', 'user_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['list_name', 'comment', ], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'list_name' => 'List Name',
            'comment'  => 'Comment',
            'list_type'    => 'List Type',
            'user_id'    => 'User ID',
            'status'   => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
