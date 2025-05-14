<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property string $category_name
 * @property string $category_image
 * @property int $sort_by
 * @property int $created_at
 * @property int $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort_by', 'tab_sort_by', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['category_name', ], 'string', 'max' => 100],
            [['category_image', ], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'category_image' => 'Category Image',
            'sort_by' => 'Sort By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
