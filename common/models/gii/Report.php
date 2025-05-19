<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%report}}".
 *
 */
class Report extends \yii\db\ActiveRecord
{

    public $lyricJson;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%report}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guest_name', 'guest_mobile', 'guest_channel', 'staff_mobile', ], 'string'],
            [['staff_id', 'project_id', 'report_status', ], 'integer'],
            [[
//                'visit_time',
                'visit_type', ], 'integer'],
            [['visit_time'], 'string'],
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
