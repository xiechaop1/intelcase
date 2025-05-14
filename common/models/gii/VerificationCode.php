<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%verification_code}}".
 *
 * @property int $id
 * @property int $type 1注册 2找回密码
 * @property int $uid 用户id
 * @property string $code 验证码
 * @property string $related_data 关联的数据
 * @property int $is_used 1已使用 3未使用
 * @property int $expire_at 过期时间戳
 * @property int $created_at
 * @property int $updated_at
 */
class VerificationCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%verification_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'uid', 'is_used', 'expire_at', 'created_at', 'updated_at'], 'integer'],
            [['expire_at'], 'required'],
            [['code'], 'string', 'max' => 6],
            [['related_data'], 'string', 'max' => 64],
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
            'uid' => 'Uid',
            'code' => 'Code',
            'related_data' => 'Related Data',
            'is_used' => 'Is Used',
            'expire_at' => 'Expire At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
