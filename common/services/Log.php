<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/20
 * Time: 2:12 PM
 */

namespace common\services;


use common\services\Curl;
use common\models\User;
use yii\base\Component;
use yii;

class Log extends Component
{

    public function write($code, $opStatus = 1, $userId = 0, $musicId = 0, $opdesc = '', $ret = '') {
        $model = new \common\models\Log();
        $model->op_code     = $code;
        $model->user_id     = $userId;
        $model->music_id    = $musicId;
        $model->op_desc     = $opdesc;
        $model->op_status   = $opStatus;
        $model->ret         = (string)$ret;


        try {
            $r = $model->save();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }

        return $r;
    }

}