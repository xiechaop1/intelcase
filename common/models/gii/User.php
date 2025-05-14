<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $created_at
 * @property int $updated_at
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_status', 'user_type', 'status', 'last_login_time', 'wx_token_expire_time', 'max_lock_ct', 'created_at', 'updated_at'], 'integer'],
            [['user_name', 'wx_openid', 'wx_unionid', 'wx_token', 'mobile', 'avatar', 'remarks', ], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'wx_id'  => 'WX ID',
            'mobile'    => 'Mobile',
            'avatar'    => 'Avatar',
            'user_status'   => 'User Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
