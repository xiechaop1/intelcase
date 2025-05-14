<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%music}}".
 *
 */
class UploadMusicScore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%upload_music_score}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['remarks', ], 'string'],
            [['upload_music_id', 'ver_id', 'user_id', 'op_user_id', 'score'], 'integer' ],
            [['created_at', 'updated_at', 'status'], 'integer'],
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
