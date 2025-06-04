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
        $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] : '';
        $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] : '';

        $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
        $advStaffId = !empty($this->_get['adv_staff_id']) ? $this->_get['adv_staff_id'] : 0;
        $visitStatus = !empty($this->_get['visit_status']) ? $this->_get['visit_status'] : 0;
        $inter = !empty($this->_get['inter']) ? $this->_get['inter'] : 'daily';


        if ($inter == 'daily') {
            $reportCount = Report::find()->select('DATE(visit_time) as dt, count(*) as ct');
            $visitCount = Visit::find()->select('DATE(visit_time) as dt, count(*) as ct');
        } else {
            $reportCount = Report::find()->select('count(*) as ct');
            $visitCount = Visit::find()->select('count(*) as ct');
        }
        if (!empty($guestMobile)) {
            $reportCount->andFilterWhere(['guest_mobile' => $guestMobile]);
            $visitCount->andFilterWhere(['guest_mobile' => $guestMobile]);
        }
        if (!empty($projectId)) {
            $reportCount->andFilterWhere(['project_id' => $projectId]);
            $visitCount->andFilterWhere(['project_id' => $projectId]);
        }
        if (!empty($advStaffId)) {
            $reportCount->andFilterWhere(['adv_staff_id' => $advStaffId]);
            $visitCount->andFilterWhere(['adv_staff_id' => $advStaffId]);
        }
        if (!empty($beginTime)) {
            $reportCount->andFilterWhere(['>=', 'visit_time', $beginTime]);
            $visitCount->andFilterWhere(['>=', 'visit_time', $beginTime]);
        }
        if (!empty($endTime)) {
            $reportCount->andFilterWhere(['<=', 'visit_time', $endTime]);
            $visitCount->andFilterWhere(['<=', 'visit_time', $endTime]);
        }
        if (!empty($visitStatus)) {
            $visitCount->andFilterWhere(['visit_status' => $visitStatus]);
        }

        if ($inter == 'daily') {
            $reportCount->groupBy('DATE(visit_time)');
            $visitCount->groupBy('DATE(visit_time)');
        }

        $reportRet = $reportCount->asArray()->all();
        $visitRet = $visitCount->asArray()->all();

//        $reportCt = $reportRet['ct'];
//        $visitCt = $visitRet['ct'];

        $reportTemp = [];
        if (!empty($reportRet)) {
            foreach ($reportRet as $reportOne) {
                $reportTemp[$reportOne['dt']] = $reportOne['ct'];
            }
        }

        $visitTemp = [];
        if (!empty($visitRet)) {
            foreach ($visitRet as $visitOne) {
                $visitTemp[$visitOne['dt']] = $visitOne['ct'];
            }
        }

        $visitRate = [];
        if (!empty($reportTemp)) {
            foreach ($reportTemp as $rdt => $rct) {
                if (isset($visitTemp[$rdt])) {
                    $visitRate[$rdt] = round($visitTemp[$rdt] / $rct, 2);
                } else {
                    $visitRate[$rdt] = 0;
                }
            }
        }

//        $reportCt = $reportCount->count();
//        $visitCt = $visitCount->count();

//        $visitRate = $visitCt / $reportCt * 100;
//        $visitRate = round($visitRate, 2);

        return $this->success([
            'report_count' => $reportTemp,
            'visit_count' => $visitTemp,
            'visit_rate' => $visitRate,
        ]);


    }

    public function guestList()
    {
        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $pageSize = !empty($this->_get['page_size']) ? $this->_get['page_size'] : 20;

        $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
        $advStaffId = !empty($this->_get['adv_staff_id']) ? $this->_get['adv_staff_id'] : 0;
        $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] : '';
        $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] : '';

        $visitList = Visit::find();
        if (!empty($guestMobile)) {
            $visitList->andFilterWhere(['guest_mobile' => $guestMobile]);
        }
        if (!empty($projectId)) {
            $visitList->andFilterWhere(['project_id' => $projectId]);
        }
        if (!empty($advStaffId)) {
            $visitList->andFilterWhere(['adv_staff_id' => $advStaffId]);
        }
        if (!empty($beginTime)) {
            $visitList->andFilterWhere(['>=', 'visit_time', $beginTime]);
        }
        if (!empty($endTime)) {
            $visitList->andFilterWhere(['<=', 'visit_time', $endTime]);
        }
        $visitList = $visitList->orderBy(['id' => SORT_DESC]);

        $count = $visitList->count();
        $data = $visitList->offset(($page - 1) * $pageSize)
            ->all();

        return $this->success([
            'visit' => $data,
            'total_count' => $count,
            'page' => $page,
            'page_size' => $pageSize,
        ]);

    }


}