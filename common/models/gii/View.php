<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%view}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 */
class View extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%view}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['object_id', 'object_type', 'view_ct', 'user_id', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'object_type' => 'Object Type',
            'view_ct' => 'View Ct',
            'user_id'   => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
//            'status'    => 'Status',
        ];
    }

}
