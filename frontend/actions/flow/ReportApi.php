<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\definitions\Common;
use common\models\Report;
use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class ReportApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();

            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

            $this->valToken();
            switch ($this->action) {
                case 'add':
                    $ret = $this->add();
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

        $model = new Report();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $guestName = !empty($this->_get['guest_name']) ? $this->_get['guest_name'] : '';
            $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
            $guestChannel = !empty($this->_get['guest_channel']) ? $this->_get['guest_channel'] : '';
            $staffMobile = !empty($this->_get['staff_mobile']) ? $this->_get['staff_mobile'] : '';
            $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
            $visitTime = !empty($this->_get['visit_time']) ? $this->_get['visit_time'] : Date('Y-m-d 00:00:00');
            $visitType = !empty($this->_get['visit_type']) ? $this->_get['visit_type'] : 0;

            $lastReport = Report::find()
                ->where(['project_id' => $this->_projectId])
                ->andFilterWhere(['guest_mobile' => $guestMobile])
                ->andFilterWhere([
                    '<', 'visit_time', time() - 24 * 3600
                ])
                ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
                ->orderBy('id DESC')
                ->one();

            $reportCount = Report::find()
                ->select('visit_time')
                ->where([
                    'project_id' => $this->_projectId,
                    'guest_mobile' => $guestMobile,
                ])
                ->groupBy([
                    'visit_time'
                ])
                ->count();

            $visitType = empty($reportCount) ? 0 : $reportCount + 1;

            if (!empty($lastReport)) {
                $reportStatus = Report::REPORT_STATUS_INVALID;
            } else {
                $reportStatus = Report::REPORT_STATUS_PASS;
            }

            $model->project_id = $this->_projectId;
            $model->guest_name = $guestName;
            $model->guest_mobile = $guestMobile;
            $model->guest_channel = $guestChannel;
            $model->staff_mobile = $staffMobile;
            $model->staff_id = $staffId;
            $model->visit_time = $visitTime;
            $model->visit_type = $visitType;
            $model->report_status = $reportStatus;
            $model->save();

            $transaction->commit();

            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


}