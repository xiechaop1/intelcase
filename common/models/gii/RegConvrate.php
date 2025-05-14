<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%reg_convrate}}".
 *
 * @property int $id
 * @property string $token
 * @property int $uid
 * @property int $created_at
 * @property int $updated_at
 */
class RegConvrate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reg_convrate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'created_at', 'updated_at'], 'integer'],
            [['token'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'uid' => 'Uid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
