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
use function Symfony\Component\String\u;

class Msg extends Component
{

    public function add($recvId = 0, $content, $senderId = 0, $msgType = \common\models\Msg::MSG_TYPE_SYSTEM, $msgStatus = \common\models\Msg::MSG_STATUS_UNREAD) {


        if (is_array($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        $model = new \common\models\Msg();
        $model->recv_id = $recvId;
        $model->content = $content;
        $model->sender_id = $senderId;
        $model->msg_type = $msgType;
        $model->msg_status = $msgStatus;


        try {
            $r = $model->save();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }

        return $r;
    }

    public function removeBtn($msgId) {
        $msg = \common\models\Msg::findOne($msgId);
        if (!empty($msg)) {
            $content = $msg->content;
            $cont = json_decode($content, true);
            if (isset($cont['btn'])) {
                unset($cont['btn']);
                $msg->content = json_encode($cont, JSON_UNESCAPED_UNICODE);
                try {
                    $r = $msg->save();
                } catch (\Exception $e) {
                    Yii::error($e->getMessage());
                }
            }
        }
    }

}