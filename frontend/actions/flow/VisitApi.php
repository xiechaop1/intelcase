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
use common\models\Visit;
//use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class VisitApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;
    private $_reportId;

    public function run()
    {
        try {
            $this->_get = Yii::$app->request->get();

            $this->_projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
            $this->_reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;

            if (empty($this->_projectId)) {
                return $this->fail('需要指定项目', -1000);
            }

            if (empty($this->_reportId)) {
                return $this->fail('需要指定报备', -1000);
            }

            $report = Report::find()
                ->where([
                    'id' => $this->_reportId,
                ])
                ->andFilterWhere([
                    'between', 'visit_time', strtotime(date('Y-m-d 00:00:00')), strtotime(date('Y-m-d 23:59:59'))
                ])
                ->orderBy([
                    'id' => SORT_DESC
                ])
                ->one();

            if (empty($report)) {
                return $this->fail('报备不存在', -1000);
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

        $model = new Visit();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $guestName = !empty($this->_get['guest_name']) ? $this->_get['guest_name'] : '';
            $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
            $personCt = !empty($this->_get['person_ct']) ? $this->_get['person_ct'] : 0;
            $guestAppeal = !empty($this->_get['guest_appeal']) ? $this->_get['guest_appeal'] : '';
            $budget = !empty($this->_get['budget']) ? $this->_get['budget'] : '';
            $staffMobile = !empty($this->_get['staff_mobile']) ? $this->_get['staff_mobile'] : '';
            $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
            $visitTime = !empty($this->_get['visit_time']) ? $this->_get['visit_time'] : Date('Y-m-d 00:00:00');
            $visitType = !empty($this->_get['visit_type']) ? $this->_get['visit_type'] : 0;
            $visitStatus = !empty($this->_get['visit_status']) ? $this->_get['visit_status'] : 0;
            $visitStatusComment = !empty($this->_get['visit_status_comment']) ? $this->_get['visit_status_comment'] : '';
            $reportId = !empty($this->_get['report_id']) ? $this->_get['report_id'] : 0;
            $visitCt = !empty($this->_get['visit_ct']) ? $this->_get['visit_ct'] : 0;
            $visitConfirmStatus = !empty($this->_get['visit_confirm_status']) ? $this->_get['visit_confirm_status'] : 0;


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

            $model->project_id = $this->_projectId;
            $model->report_id = $reportId;
            $model->guest_name = $guestName;
            $model->guest_mobile = $guestMobile;
            $model->guest_appeal = $guestAppeal;
            $model->budget = $budget;
            $model->staff_mobile = $staffMobile;
            $model->staff_id = $staffId;
            $model->visit_time = $visitTime;
            $model->visit_type = $visitType;
            $model->visit_status = $visitStatus;
            $model->visit_status_comment = $visitStatusComment;
            $model->visit_confirm_status = $visitConfirmStatus;
            $model->visit_ct = $visitCt;
            $model->person_ct = $personCt;


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