<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%tag_relation}}".
 *
 * @property int $id
 * @property int $type 1Post
 * @property int $tag_id
 * @property int $data_id
 * @property int $created_at
 * @property int $updated_at
 */
class TagRelation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag_relation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'tag_id', 'data_id', 'tag_value', 'created_at', 'updated_at', 'sort'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'type'          => 'Type',
            'tag_id'        => 'Tag ID',
            'data_id'       => 'Data ID',
            'sort'          => 'Sort',
            'tag_value'     => 'Tag Value',
            'created_at'    => 'Created At',
            'updated_at'    => 'Updated At',
        ];
    }
}
