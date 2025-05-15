<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%subscribed}}".
 *
 */
class Subscribed extends \yii\db\ActiveRecord
{

    public $lyricJson;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscribed}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_no', 'building_area', 'sub_guest', 'id_no', 'mobile', 'owner', 'lessor', 'lessor_detail', 'free_rent_date', 'increase_date', ], 'string'],
            [['project_id', 'report_id', 'visit_id', 'pay_method', 'id_type', 'sub_type', 'increase_rate', ], 'integer'],
            [['balance_price', 'sub_total_price', 'daily_amount', 'monthly_amount', 'yearly_amount', 'deposit', 'rent_amount',
                'pro_rent_amount', 'al_daily_amount', 'al_amount', 'al_other', 'al_total_amount'], 'number'],
            [['rent_date_begin', 'rent_date_end', 'al_date_begin', 'al_date_end', ], 'string'],
            [['status', 'created_at', 'updated_at',], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $rules = $this->rules();
        $ret = [];
        foreach ($rules as $rule) {
            if (!empty($rule[0])) {
                foreach ($rule[0] as $r) {
                    $ret[$r] = preg_replace_callback('/[^|\s]+([a-z])/',function($matches){
//                        print_r($matches);  //Array ( [0] => _b [1] => b )
                        return strtoupper($matches[1]);
                    },$r);
                }
            }
        }

        return $ret;

    }
}
