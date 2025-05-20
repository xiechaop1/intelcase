<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\definitions\Common;
use common\models\Msg;
use common\models\Payment;
use common\models\Report;
use common\models\Subscribed;
use common\models\Visit;
//use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class MsgApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;
    private $_reportId;

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();

            $recvId = !empty($this->_get['recv_id']) ? $this->_get['recv_id'] : 0;

            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

            $this->valToken();
            switch ($this->action) {
                case 'get_by_recv_id':
                    $ret = $this->getByRecvId();
                    break;
                case 'read':
                    $ret = $this->read();
                    break;
                default:
                    $ret = [];
                    break;

            }
        } catch (\Exception $e) {
            $ret = $this->fail($e->getCode() . ': ' . $e->getMessage());
        }

        return $ret;
    }

    public function read() {
        $msgId = !empty($this->_get['msg_id']) ? $this->_get['msg_id'] : 0;

        if (empty($msgId)) {
            return $this->fail('需要指定消息', -1000);
        }

        $model = new \common\models\Msg();
        $model = $model::findOne($msgId);

        if (empty($model)) {
            return $this->fail('消息不存在', -1000);
        }

        $model->msg_status = Msg::MSG_STATUS_READ;
        $model->save();

        return $this->success();
    }

    public function getByRecvId()
    {
        $recvId = !empty($this->_get['recv_id']) ? $this->_get['recv_id'] : 0;
        $size = !empty($this->_get['size']) ? $this->_get['size'] : 10;

        if (empty($recvId)) {
            return $this->fail('需要指定接收人', -1000);
        }

        $model = new \common\models\Msg();
        $msgList = $model::find()
            ->where(['recv_id' => $recvId])
            ->andWhere(['<>', 'msg_status', Msg::MSG_STATUS_DELETE])
            ->orderBy('created_at desc')
            ->limit($size)
            ->asArray()
            ->all();

        return $this->success($msgList);

    }


}