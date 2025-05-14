<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property int $id
 * @property string $page
 * @property int $quan_id
 * @property int $is_used
 * @property int $created_at
 * @property int $updated_at
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'banner_status', 'online_time', 'offline_time', 'created_at', 'updated_at'], 'integer'],
            [['page', 'subject', 'target', 'image'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'page'          => 'Page',
            'subject'       => 'Subject',
            'target'        => 'Target',
            'image'         => 'Image',
            'sort'          => 'Sort',
            'online_time'   => 'Online Time',
            'offline_time'  => 'Offline Time',
            'created_at'    => 'Created At',
            'updated_at'    => 'Updated At',
        ];
    }

}
