<?php
/**
 * Created by PhpStorm.
 * User: Choice
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

    public function write($code, $opStatus = 1, $staffId, $guestMobile = '', $opPara = '', $opdesc = '', $ret = '') {
        $model = new \common\models\Log();
        $model->op_code     = $code;
        $model->staff_id    = $staffId;
//        $model->user_id     = $userId;
        $model->guest_mobile = $guestMobile;
        $model->op_parameters = is_string($opPara) ? $opPara : json_encode($opPara, JSON_UNESCAPED_UNICODE);
        $model->op_desc     = $opdesc;
        $model->op_status   = $opStatus;
        $model->ret         = is_string($ret) ? $ret : json_encode($ret, JSON_UNESCAPED_UNICODE);


        try {
            $r = $model->save();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }

        return $r;
    }

    public function read($code = 0, $beginTime = 0, $endTime = 0, $page = 1, $pageSize = 20) {
        $model = \common\models\Log::find();

        if (!empty($code)) {
            $model->andWhere(['op_code' => $code]);
        }
        if (!empty($beginTime)) {
            $model->andWhere(['>=', 'created_at', $beginTime]);
        }
        if (!empty($endTime)) {
            $model->andWhere(['<=', 'created_at', $endTime]);
        }
        $model = $model->orderBy(['id' => SORT_DESC])
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->all();

        return $model;

    }

}