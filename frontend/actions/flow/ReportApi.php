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
use common\models\Project;
use common\models\Report;
use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class ReportApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;
    private $_project;

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();

            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

            $this->_project = Project::find()
                ->where(['id' => $this->_projectId])
                ->one();

            $this->valToken();
            switch ($this->action) {
                case 'add':
                    $ret = $this->add();
                    break;
                case 'get_by_id':
                    $ret = $this->getById();
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

    public function getById() {
        $reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;

        if (empty($reportId)) {
            return $this->fail('需要指定报告ID', -1000);
        }

        $model = Report::find()
            ->where(['id' => $reportId])
            ->one();

        if (empty($model)) {
            return $this->fail('报告不存在', -1000);
        }

        return $this->success($model);
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
            // 获取最新一条数据ID
            $reportId = Yii::$app->db->getLastInsertID();

            $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            if (!empty($recvId)) {
                $content = [
                    'content' => '有一条新报备，客户：' . $guestName . '，时间：' . date('Y-m-d H:i:s', $visitTime) . '，请及时处理。',
                    'report_id' => $reportId,
                    'project_id' => $this->_projectId,
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }


            return $this->success([
                'report_id' => $reportId,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


}