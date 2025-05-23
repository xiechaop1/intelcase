<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%payment}}".
 *
 */
class Payment extends \yii\db\ActiveRecord
{

    public $lyricJson;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pay_account', 'recv_account', 'receipt_no', 'payer',  ], 'string'],
            [['project_id', 'sub_id', ], 'integer'],
            [['pay_way', 'pay_type', 'amount_type', 'pay_status' ], 'integer'],
            [['amount', 'recv_amount', 'fee', ], 'number'],
            [['pay_time', 'recv_time', ], 'integer'],
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
