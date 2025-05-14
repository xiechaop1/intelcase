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
class Weather extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%weather}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['city_code', 'data_date', 'weather', 'min_temper', 'max_temper'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_code' => 'City Code',
            'data_date' => 'Date of Data',
            'weather' => 'Weather',
            'min_temper' => 'Min Temper',
            'max_temper' => 'Max Temper',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
