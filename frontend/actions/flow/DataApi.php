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

class DataApi extends ApiAction
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
                case 'get_data':
                    $ret = $this->getData();
                    break;
                case 'guest_list':
                    $ret = $this->guestList();
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

    public function getData()
    {
        
    }

    public function guestList()
    {
        $visitList = Visit::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->success($visitList);

    }


}