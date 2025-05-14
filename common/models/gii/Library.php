<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property string $library_name
 * @property string $image
 * @property int $category_id
 * @property int $type
 * @property int $created_at
 * @property int $updated_at
 */
class Library extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%library}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['library_name', ], 'string', 'max' => 100],
            [['image', ], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'library_name' => 'Library Name',
            'image' => 'Image',
            'category_id' => 'Category ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
