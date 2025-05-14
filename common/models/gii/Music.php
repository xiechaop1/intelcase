<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%music}}".
 *
 */
class Music extends \yii\db\ActiveRecord
{

    public $lyricJson;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%music}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'comment', 'composer', 'singer', 'lyricist', 'duration', 'verse_url', 'lyric', 'lyric_url', 'resource_download_url', 'resource_download_file', 'chorus_start_time', 'chorus_end_time', 'background_image', 'chorus_url', 'cover_image', 'cover_thumbnail', 'music_rate'], 'string'],
            [['singer_id', 'upload_user_id', 'music_type', 'music_status', 'op_user_id', 'status'], 'integer'],
            [['price'], 'number'],
            [['is_delete', 'created_at', 'updated_at',], 'integer'],
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
