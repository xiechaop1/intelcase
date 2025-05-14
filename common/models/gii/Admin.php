<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property string $name
 * @property string $mobile_section
 * @property string $mobile
 * @property string $email
 * @property string $authkey
 * @property string $password
 * @property string $avatar 头像
 * @property int $role 1平台管理员 2商家
 * @property int $type 1总管理员 2管理员
 * @property int $status 1启用 2禁用
 * @property int $created_at
 * @property int $updated_at
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'mobile'], 'string', 'max' => 32],
            [['mobile_section'], 'string', 'max' => 10],
            [['email', 'password'], 'string', 'max' => 64],
            [['authkey'], 'string', 'max' => 8],
            [['avatar'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'mobile_section' => 'Mobile Section',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'authkey' => 'Authkey',
            'password' => 'Password',
            'avatar' => 'Avatar',
            'role' => 'Role',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
