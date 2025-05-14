<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property int $id
 * @property int $uid
 * @property int $quan_id
 * @property int $is_used
 * @property int $created_at
 * @property int $updated_at
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%images}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_id', 'data_type'], 'integer'],
            [['image', 'image_introduce'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'image'             => 'Image',
            'image_introduce'   => 'Image Introduce',
            'data_id'           => 'Data Id',
            'data_type'         => 'Data Type',
            'created_at'        => 'Created At',
            'updated_at'        => 'Updated At',
//            'status'            => 'Status',
        ];
    }

}
