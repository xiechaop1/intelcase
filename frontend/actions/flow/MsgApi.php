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
            if (Yii::$app->request->method == 'POST') {
                $this->_get = Yii::$app->request->post();
            } else {
                $this->_get = Yii::$app->request->get();
            }

            $recvId = !empty($this->_get['recv_id']) ? $this->_get['recv_id'] : 0;

//            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
//
//            if (empty($this->_projectId)) {
//                return $this->fail('需要指定项目', -1000);
//            }

            $this->valToken();
            switch ($this->action) {
                case 'get_by_recv_id':
                    $ret = $this->getByRecvId();
                    break;
                case 'read':
                    $ret = $this->read();
                    break;
                case 'add':
                    $ret = $this->add();
                    break;
                case 'msg_count':
                    $ret = $this->msgCount();
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

    public function add() {
        $recvId = !empty($this->_get['recv_id']) ? $this->_get['recv_id'] : '';
        $content = !empty($this->_get['content']) ? $this->_get['content'] : '';
        $title = !empty($this->_get['title']) ? $this->_get['title'] : '';
        $btn = !empty($this->_get['btn']) ? $this->_get['btn'] : [];

        $cont = [
            'title' => $title,
            'content' => $content,
            'btn'   => $btn
        ];

        return Yii::$app->msg->add($recvId, $cont);
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

    public function msgCount() {
        $recvId = !empty($this->_get['recv_id']) ? $this->_get['recv_id'] : 0;
        $msgStatus = !empty($this->_get['msg_status']) ? $this->_get['msg_status'] : -1;

        if (empty($recvId)) {
            return $this->fail('需要指定接收人', -1000);
        }

        $model = new \common\models\Msg();
        $count = $model::find()
            ->where(['recv_id' => $recvId]);
        if ($msgStatus >= 0) {
            $count = $count->andWhere(['msg_status' => $msgStatus]);
        }
        $count = $count->count();

        return $this->success(['msg_count' => $count]);
    }

    public function getByRecvId()
    {
        $recvId = !empty($this->_get['recv_id']) ? $this->_get['recv_id'] : 0;
        $k = !empty($this->_get['k']) ? $this->_get['k'] : '';
        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $pageSize = !empty($this->_get['page_size']) ? $this->_get['page_size'] : 10;

        if (empty($recvId)) {
            return $this->fail('需要指定接收人', -1000);
        }

        $model = new \common\models\Msg();
        $msgList = $model::find()
            ->where(['recv_id' => $recvId]);
        if (!empty($k)) {
            $msgList->andFilterWhere(['like', 'content', $k]);
        }
        $msgList = $msgList->andFilterWhere(['<>', 'msg_status', Msg::MSG_STATUS_DELETE])
            ->orderBy('created_at desc')
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->asArray()
            ->all();

        if (!empty($msgList)) {
            foreach ($msgList as &$msg) {
                if (!empty($msg['content'])) {
                    $msg['content'] = json_decode($msg['content'], true);
                }
            }
        }

        return $this->success($msgList);

    }


}