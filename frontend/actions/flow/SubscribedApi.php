<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\models\Msg;
use common\models\Project;
use common\models\Report;
//use common\services\Log;
use common\models\Subscribed;
use frontend\actions\ApiAction;
use Yii;

class SubscribedApi extends ApiAction
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

            $this->_project = Project::find()
                ->where(['id' => $this->_projectId])
                ->one();

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
                case 'update':
                    $ret = $this->update();
                    break;
                case 'get_by_project_id':
                    $ret = $this->getByProjectId();
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

    public function update() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;

        if (empty($subId)) {
            return $this->fail('需要指定订阅ID', -1000);
        }

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        if (empty($model)) {
            return $this->fail('订阅不存在', -1000);
        }

        $model->save();

        return $this->success();
    }

    public function getById() {
        $subId = !empty($this->_get['sub_id']) ? $this->_get['sub_id'] : 0;

        if (empty($subId)) {
            return $this->fail('需要指定订阅ID', -1000);
        }

        $model = Subscribed::find()
            ->where(['id' => $subId])
            ->one();

        if (empty($model)) {
            return $this->fail('订阅不存在', -1000);
        }

        return $this->success(['sub' => $model]);
    }

    public function getByProjectId() {
        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $pageSize = !empty($this->_get['page_size']) ? $this->_get['page_size'] : 10;

        $query = Subscribed::find()
            ->where(['project_id' => $this->_projectId])
//            ->andFilterWhere(['report_id' => $this->_reportId])
            ->orderBy([
                'id' => SORT_DESC
            ]);

        $count = $query->count();
        $list = $query->offset(($page - 1) * $pageSize)->limit($pageSize)->all();

        return $this->success([
            'list' => $list,
            'total_count' => $count,
        ]);
    }

    public function add() {

        $model = new Subscribed();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $subType = !empty($this->_get['sub_type']) ? $this->_get['sub_type'] : 0;
            $subGuest = !empty($this->_get['sub_guest']) ? $this->_get['sub_guest'] : '';
            $roomNo = !empty($this->_get['room_no']) ? $this->_get['room_no'] : '';
            $idType = !empty($this->_get['id_type']) ? $this->_get['id_type'] : 0;
            $idNo = !empty($this->_get['id_no']) ? $this->_get['id_no'] : '';
            $guestMobile = $mobile = !empty($this->_get['mobile']) ? $this->_get['mobile'] : '';
            $buildingArea = !empty($this->_get['building_area']) ? $this->_get['building_area'] : '';
            $balancePrice = !empty($this->_get['balance_price']) ? $this->_get['balance_price'] : 0;
            $subTotalPrice = !empty($this->_get['sub_total_price']) ? $this->_get['sub_total_price'] : 0;
            $payMethod = !empty($this->_get['pay_method']) ? $this->_get['pay_method'] : 0;
            $owner = !empty($this->_get['owner']) ? $this->_get['owner'] : '';
            $lessor = !empty($this->_get['lessor']) ? $this->_get['lessor'] : '';
            $lessorDetail = !empty($this->_get['lessor_detail']) ? $this->_get['lessor_detail'] : '';
            $rentDateBegin = !empty($this->_get['rent_date_begin']) ? $this->_get['rent_date_begin'] : Date('Y-m-d 00:00:00');
            $rentDateEnd = !empty($this->_get['rent_date_end']) ? $this->_get['rent_date_end'] : Date('Y-m-d 00:00:00', strtotime('+1 year'));
            $freeRentDate = !empty($this->_get['free_rent_date']) ? $this->_get['free_rent_date'] : '';
            $increaseDate = !empty($this->_get['increase_date']) ? $this->_get['increase_date'] : '';
            $increaseRate = !empty($this->_get['increase_rate']) ? $this->_get['increase_rate'] : 0;
            $deposit = !empty($this->_get['deposit']) ? $this->_get['deposit'] : 0;
            $dailyAmount = !empty($this->_get['daily_amount']) ? $this->_get['daily_amount'] : 0;
//            $monthlyAmount = !empty($this->_get['monthly_amount']) ? $this->_get['monthly_amount'] : 0;
//            $yearlyAmount = !empty($this->_get['yearly_amount']) ? $this->_get['yearly_amount'] : 0;
//            $rentAmount = !empty($this->_get['rent_amount']) ? $this->_get['rent_amount'] : 0;
            $proRentAmount = !empty($this->_get['pro_rent_amount']) ? $this->_get['pro_rent_amount'] : 0;
            $alDailyAmount = !empty($this->_get['al_daily_amount']) ? $this->_get['al_daily_amount'] : 0;
            $alAmount = !empty($this->_get['al_amount']) ? $this->_get['al_amount'] : 0;
            $alOther = !empty($this->_get['al_other']) ? $this->_get['al_other'] : 0;
            $alTotalAmount = !empty($this->_get['al_total_amount']) ? $this->_get['al_total_amount'] : 0;
            $alDateBegin = !empty($this->_get['al_date_begin']) ? $this->_get['al_date_begin'] : '';
            $alDateEnd = !empty($this->_get['al_date_end']) ? $this->_get['al_date_end'] : '';

            if (!empty($dailyAmount) && !empty($buildingArea)) {
                $monthlyAmount = $dailyAmount * $buildingArea * 30;
                $yearlyAmount = $dailyAmount * $buildingArea * 365;

                if (!empty($rendDateEnd) && !empty($rentDateBegin)) {
                    $rentAmount = $dailyAmount * $buildingArea * (int((strtotime($rentDateEnd) - strtotime($rentDateBegin)) / 86400) + 1);
                }
            }

            if (!empty($monthlyAmount) && !empty($buildingArea)) {
                $dailyAmount = $monthlyAmount / 30;
                $yearlyAmount = $monthlyAmount * 12;

                if (!empty($rendDateEnd) && !empty($rentDateBegin)) {
                    $rentAmount = $monthlyAmount * (int((strtotime($rentDateEnd) - strtotime($rentDateBegin)) / 86400) + 1);
                }
            }

            $alDailyAmount = $dailyAmount - $proRentAmount;
            if (!empty($rentAmount) && !empty($alDateBegin) && !empty($alDateEnd) && !empty($buildingArea)) {
                $alTotalAmount = $alDailyAmount * (int((strtotime($alDateEnd) - strtotime($alDateBegin)) / 86400) + 1) * $buildingArea;
            }

            $alAmount = $alTotalAmount + $alOther;

            $lastReport = Report::find()
                ->where(['project_id' => $this->_projectId])
                ->andFilterWhere(['guest_mobile' => $guestMobile])
                ->andFilterWhere([
                    '<', 'visit_time', time() - 24 * 3600
                ])
                ->andFilterWhere(['report_status' => Report::REPORT_STATUS_PASS])
                ->orderBy('id DESC')
                ->one();

            $model->project_id = $this->_projectId;
            $model->report_id = $this->_reportId;
            $model->sub_type = $subType;
            $model->sub_guest = $subGuest;
            $model->room_no = $roomNo;
            $model->id_type = $idType;
            $model->id_no = $idNo;
            $model->mobile = $mobile;
            $model->building_area = $buildingArea;
            $model->balance_price = $balancePrice;
            $model->sub_total_price = $subTotalPrice;
            $model->pay_method = $payMethod;
            $model->owner = $owner;
            $model->lessor = $lessor;
            $model->lessor_detail = $lessorDetail;
            $model->rent_date_begin = $rentDateBegin;
            $model->rent_date_end = $rentDateEnd;
            $model->free_rent_date = $freeRentDate;
            $model->increase_date = $increaseDate;
            $model->increase_rate = $increaseRate;
            $model->deposit = $deposit;
            $model->daily_amount = $dailyAmount;
            $model->monthly_amount = $monthlyAmount;
            $model->yearly_amount = $yearlyAmount;
            $model->rent_amount = $rentAmount;
            $model->pro_rent_amount = $proRentAmount;
            $model->al_daily_amount = $alDailyAmount;
            $model->al_amount = $alAmount;
            $model->al_other = $alOther;
            $model->al_total_amount = $alTotalAmount;
            $model->al_date_begin = $alDateBegin;
            $model->al_date_end = $alDateEnd;


            $model->save();

            $transaction->commit();

            // 获取最新一条数据ID
            $subId = Yii::$app->db->getLastInsertID();

            $recvId = !empty($this->_project->pm_staff_id) ? $this->_project->pm_staff_id : 0;
            if (!empty($recvId)) {
                $content = [
                    'content' => '有一条新到访，客户：' . $subGuest . '，时间：' . date('Y-m-d H:i:s', time()) . '，请及时处理。',
                    'project_id' => $this->_projectId,
                ];
                Yii::$app->msg->add($recvId, $content, Msg::MSG_SENDER_SYSTEM);
            }

            return $this->success([
                'sub_id' => $subId,
                'project_id' => $this->_projectId,
                'report_id' => $this->_reportId,
                'subscribed' => $model,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


}