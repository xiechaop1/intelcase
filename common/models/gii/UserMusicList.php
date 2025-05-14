<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%user_music_list}}".
 *
 * @property int $created_at
 * @property int $updated_at
 */
class UserMusicList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_music_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['list_id', 'music_id', 'user_id', 'ct', 'expire_time', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'list_id' => 'List ID',
            'music_id'  => 'Music ID',
            'user_id'    => 'User ID',
            'expire_time' => 'Expire Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
