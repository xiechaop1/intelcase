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
use common\models\Project;
use common\models\Report;
use common\models\Staff;
use common\models\Subscribed;
use common\models\Visit;
//use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class DataApi extends ApiAction
{
    public $action;
    private $_get;
    private $_staffId;
    private $_staff;

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

            $this->_staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
            if (!empty($this->_staffId)) {
                $this->_staff = Staff::find()
                    ->where(['id' => $this->_staffId])
                    ->one();
            }

            $this->valToken();

            $this->checkRule($this->action, $this->_staff);

            switch ($this->action) {
                case 'get_data':
                    $ret = $this->getData();
                    break;
                case 'get_logs':
                    $ret = $this->getLogs();
                    break;
                case 'report_list':
                    $ret = $this->getReportList();
                    break;
                case 'guest_list':
                    $ret = $this->guestList();
                    break;
                case 'export_guest_list':
                    $ret = $this->exportGuestList();
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

    public function getLogs()
    {
        $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] : '';
        $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] : '';
        $opCode = !empty($this->_get['op_code']) ? $this->_get['op_code'] : '';
        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $pageSize = !empty($this->_get['page_size']) ? $this->_get['page_size'] : 20;

        $logList = \common\models\Log::find();
        if (!empty($opCode)) {
            $logList->andFilterWhere(['op_code' => $opCode]);
        }
        if (!empty($beginTime)) {
            $logList->andFilterWhere(['>=', 'created_at', $beginTime]);
        }
        if (!empty($endTime)) {
            $logList->andFilterWhere(['<=', 'created_at', $endTime]);
        }
        $logList = $logList->orderBy(['id' => SORT_DESC]);
        $count = $logList->count();
        $data = $logList->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->all();

        $ret = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $one = $row->toArray();
                $one['op_parameters'] = json_decode($one['op_parameters'], true);
                $one['ret'] = json_decode($one['ret'], true);
                $one['op_status_name'] = !empty(\common\models\Log::$opStatusMap[$one['op_status']]) ? \common\models\Log::$opStatusMap[$one['op_status']] : '未知';
                $one['op_code_name'] = !empty(\common\models\Log::$opCodeMap[$one['op_code']]) ? \common\models\Log::$opCodeMap[$one['op_code']] : '未知';
                $one['created_at'] = date('Y-m-d H:i:s', $one['created_at']);
                if (!empty($one['staff_id'])) {
                    $one['staff'] = $row->staff;
                    if (!empty($staff)) {
                        $one['staff_name'] = $staff->staff_name;
                    }
                }
                $ret[] = $one;
            }
        }

        return $this->success([
            'list' => $ret,
            'total_count' => $count,
            'page' => $page,
            'page_size' => $pageSize,
        ]);
    }

    public function getData()
    {
//        $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] : Date('Y-m-d 00:00:00', strtotime('-7 days'));
//        $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] : Date('Y-m-d H:i:s');

        $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] : '';
        $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] : '';


        $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
        $advStaffId = !empty($this->_get['adv_staff_id']) ? $this->_get['adv_staff_id'] : 0;
        $visitStatus = !empty($this->_get['visit_status']) ? $this->_get['visit_status'] : 0;
        $inter = !empty($this->_get['inter']) ? $this->_get['inter'] : 'daily';

        $reportChannel = !empty($this->_get['report_channel']) ? $this->_get['report_channel'] : '';
        $guestType = !empty($this->_get['guest_type']) ? $this->_get['guest_type'] : '';

        if (strpos($projectId, ',') !== false) {
            $projectId = explode(',', $projectId);
        } else {
            $projectId = [$projectId];
        }

//        if (!empty($this->_staff)) {
//            $staffRole = $this->_staff->role;
//
//            switch ($staffRole) {
//                case Staff::STAFF_ROLE_PM:
//                    $projects = Project::find()
//                        ->where(['pm_staff_id' => $this->_staffId])
//                        ->all();
//                    if (!empty($projects)) {
//                        foreach ($projects as $pro) {
//                            $projectId[] = $pro->id;
//                        }
//                    } else {
//                        $projectId = [-1];
//                    }
//                    break;
//                case Staff::STAFF_ROLE_ADMIN_PART:
//                    $projects = Project::find()
//                        ->where(['like', 'senior_pm_staff_id', ',' . $this->_staffId . ','])
//                        ->all();
//                    if (!empty($projects)) {
//                        foreach ($projects as $pro) {
//                            $projectId[] = $pro->id;
//                        }
//                    } else {
//                        $projectId = [-1];
//                    }
//                    break;
//                case Staff::STAFF_ROLE_SALES:
//                    $projects = Report::find()
//                        ->where(['staff_id' => $this->_staffId])
//                        ->all();
//                    if (!empty($projects)) {
//                        foreach ($projects as $pro) {
//                            $projectId[] = $pro->project_id;
//                        }
//                    } else {
//                        $projectId = [-1];
//                    }
//                    break;
//                case Staff::STAFF_ROLE_ADVISOR:
//                    $projects = Report::find()
//                        ->where(['advisor_staff_id' => $this->_staffId])
//                        ->all();
//                    if (!empty($projects)) {
//                        foreach ($projects as $pro) {
//                            $projectId[] = $pro->project_id;
//                        }
//                    } else {
//                        $projectId = [-1];
//                    }
//                    break;
//                case Staff::STAFF_ROLE_CONSULTANT:
//                    $projects = Report::find()
//                        ->where(['consultant_staff_id' => $this->_staffId])
//                        ->all();
//                    if (!empty($projects)) {
//                        foreach ($projects as $pro) {
//                            $projectId[] = $pro->project_id;
//                        }
//                    } else {
//                        $projectId = [-1];
//                    }
//                    break;
//                case Staff::STAFF_ROLE_ADMIN:
////                case Staff::STAFF_ROLE_ADMIN_PART:
//                case Staff::STAFF_ROLE_ADMIN_CHILD:
//                case Staff::STAFF_ROLE_FINANCE:
//                    break;
//                default:
//                    $projectId = [-1];
//                    break;
//            }
//
//
//        } else {
//            $projectId = [-1];
//        }

        $projectId = $this->_getRoleProduct($projectId);

        $reportIds = [];
        if (!empty($reportChannel)) {
            $reportTmps = Report::find()
                ->where([
                    'guest_channel' => $reportChannel,
                ])
                ->all();

            if (!empty($reportTmps)) {
                foreach ($reportTmps as $rt) {
                    $reportIds[] = $rt->id;
                }
            }
        }

        if ($inter == 'daily') {
            $reportCount = Report::find()->select('DATE(visit_time) as dt, count(*) as ct');
            $visitCount = Visit::find()->select('DATE(visit_time) as dt, count(*) as ct');
            $subscribedRet = Subscribed::find();
            $paymentRet = Payment::find();
        } else {
            $reportCount = Report::find()->select('count(*) as ct');
            $visitCount = Visit::find()->select('count(*) as ct');
            $subscribedRet = Subscribed::find();
            $paymentRet = Payment::find();
        }

        if (!empty($reportIds)) {
            $reportCount->andFilterWhere(['id' => $reportIds]);
            $visitCount->andFilterWhere(['report_id' => $reportIds]);
            $subscribedRet->andFilterWhere(['report_id' => $reportIds]);
        }

        if (!empty($guestMobile)) {
            $reportCount->andFilterWhere(['guest_mobile' => $guestMobile]);
            $visitCount->andFilterWhere(['guest_mobile' => $guestMobile]);
            $subscribedRet->andFilterWhere(['mobile' => $guestMobile]);
        }
        if (!empty($projectId)) {
            $reportCount->andFilterWhere(['project_id' => $projectId]);
            $visitCount->andFilterWhere(['project_id' => $projectId]);
            $paymentRet->andFilterWhere(['project_id' => $projectId]);
            $subscribedRet->andFilterWhere(['project_id' => $projectId]);
        }
        if (!empty($advStaffId)) {
//            $reportCount->andFilterWhere(['adv_staff_id' => $advStaffId]);
            $visitCount->andFilterWhere(['adv_staff_id' => $advStaffId]);
        }
        if (!empty($beginTime)) {
            $reportCount->andFilterWhere(['>=', 'visit_time', $beginTime]);
            $visitCount->andFilterWhere(['>=', 'visit_time', $beginTime]);
            $paymentRet->andFilterWhere(['>=', 'pay_time', strtotime($beginTime)]);
            $subscribedRet->andFilterWhere(['>=', 'created_at', strtotime($beginTime)]);
        }
        if (!empty($endTime)) {
            $reportCount->andFilterWhere(['<=', 'visit_time', $endTime]);
            $visitCount->andFilterWhere(['<=', 'visit_time', $endTime]);
            $paymentRet->andFilterWhere(['<=', 'pay_time', strtotime($endTime)]);
            $subscribedRet->andFilterWhere(['<=', 'created_at', strtotime($endTime)]);
        }
        if (!empty($visitStatus)) {
            $visitCount->andFilterWhere(['visit_status' => $visitStatus]);
        }
        if (!empty($guestType)) {
            $visitCount->andFilterWhere(['guest_type' => $guestType]);
        }

        $reportTemp = [];
        $visitTemp = [];
        $visitRate = [];

        $subscribedRet = $subscribedRet->all();

        if ($inter == 'daily') {
            $reportCount->groupBy('DATE(visit_time)');
            $visitCount->groupBy('DATE(visit_time)');
            $reportCount->orderBy('DATE(visit_time) ASC');
            $visitCount->orderBy('DATE(visit_time) ASC');

            $reportRet = $reportCount->asArray()->all();
            $visitRet = $visitCount->asArray()->all();

            //        $reportCt = $reportRet['ct'];
            //        $visitCt = $visitRet['ct'];

            $reportDrift = [];
            $visitDrift = [];
            $visitRateDrift = [];

            $reportAll = 0;
            if (!empty($reportRet)) {
                $lastReport = 0;
                $idx = 0;
                foreach ($reportRet as $reportOne) {
                    $reportTemp[$reportOne['dt']] = $reportOne['ct'];
                    $reportAll += $reportOne['ct'];
                    if ($idx > 0) {
                        if ($lastReport > 0) {
                            $reportDrift[$reportOne['dt']] = round(($reportOne['ct'] - $lastReport) / $lastReport, 2);
                        } else {
                            $reportDrift[$reportOne['dt']] = 1;
                        }
                    } else {
                        $reportDrift[$reportOne['dt']] = 0;
                    }
                    $lastReport  = $reportOne['ct'];
                    $idx++;
                }
            }

            $visitAll = 0;
            if (!empty($visitRet)) {
                $lastVisit = 0;
                $idx = 0;
                foreach ($visitRet as $visitOne) {
                    $visitTemp[$visitOne['dt']] = $visitOne['ct'];
                    $visitAll += $visitOne['ct'];
                    if ($idx > 0) {
                        if ($lastVisit > 0) {
                            $visitDrift[$visitOne['dt']] = round(($visitOne['ct'] - $lastVisit) / $lastVisit, 2);
                        } else {
                            $visitDrift[$visitOne['dt']] = 1;
                        }
                    } else {
                        $visitDrift[$visitOne['dt']] = 0;
                    }
                    $lastVisit  = $visitOne['ct'];
                    $idx++;
                }
            }

            if ($reportAll != 0) {
                $visitRateAll = round($visitAll / $reportAll, 2);
            } else {
                $visitRateAll = 0;
            }
            if (!empty($reportTemp)) {
                $lastVisitRate = 0;
                $idx = 0;
                foreach ($reportTemp as $rdt => $rct) {
                    if (isset($visitTemp[$rdt])) {
                        $visitRate[$rdt] = round($visitTemp[$rdt] / $rct, 2);
                    } else {
                        $visitRate[$rdt] = 0;
                    }
                    if ($idx > 0) {
                        if ($lastVisitRate > 0) {
                            $visitRateDrift[$rdt] = round(($visitRate[$rdt] - $lastVisitRate) / $lastVisitRate, 2);
                        } else {
                            $visitRateDrift[$rdt] = 1;
                        }
                    } else {
                        $visitRateDrift[$rdt] = 0;
                    }
                    $lastVisitRate = $visitRate[$rdt];
                    $idx++;
                }
            }

            $subscribedData = [];
            $subIds = [];
            if (!empty($subscribedRet)) {
                $subCount = 0;
                $subArea = 0;
                foreach ($subscribedRet as $subscribed) {
                    $subTime = Date('Y-m-d', $subscribed->created_at);
                    $projectName = !empty($subscribed->project->project_name) ? $subscribed->project->project_name : '未知项目';
                    $subscribedTmp = $subscribed->toArray();
                    $subIds[] = $subscribed->id;
//                    $subCount++;
//                    $subArea += $subscribedTmp['building_area'];
                    if (!isset($subscribedData['time'][$subTime][$subscribed->sub_type]['count'])) {
                        $subscribedData['time'][$subTime][$subscribed->sub_type]['count'] = 0;
                    }
                    $subscribedData['time'][$subTime][$subscribed->sub_type]['count'] += 1;

                    if (!isset($subscribedData['project'][$projectName][$subscribed->sub_type]['count'])) {
                        $subscribedData['project'][$projectName][$subscribed->sub_type]['count'] = 0;
                    }
                    $subscribedData['project'][$projectName][$subscribed->sub_type]['count'] += 1;

                    if (!isset($subscribedData['total'][$subscribed->sub_type]['count'])) {
                        $subscribedData['total'][$subscribed->sub_type]['count'] = 0;
                    }
                    $subscribedData['total'][$subscribed->sub_type]['count'] += 1;

                    if (!isset($subscribedData['time'][$subTime][$subscribed->sub_type]['building_area'])) {
                        $subscribedData['time'][$subTime][$subscribed->sub_type]['building_area'] = 0;
                    }
                    $subscribedData['time'][$subTime][$subscribed->sub_type]['building_area'] += $subscribedTmp['building_area'];

                    if (!isset($subscribedData['project'][$projectName][$subscribed->sub_type]['building_area'])) {
                        $subscribedData['project'][$projectName][$subscribed->sub_type]['building_area'] = 0;
                    }
                    $subscribedData['project'][$projectName][$subscribed->sub_type]['building_area'] += $subscribedTmp['building_area'];

                    if (!isset($subscribedData['total'][$subscribed->sub_type]['building_area'])) {
                        $subscribedData['total'][$subscribed->sub_type]['building_area'] = 0;
                    }
                    $subscribedData['total'][$subscribed->sub_type]['building_area'] += $subscribedTmp['building_area'];

                    if (!isset($subscribedData['time'][$subTime][$subscribed->sub_type]['sub_total_price'])) {
                        $subscribedData['time'][$subTime][$subscribed->sub_type]['sub_total_price'] = 0;
                    }
                    $subscribedData['time'][$subTime][$subscribed->sub_type]['sub_total_price'] += $subscribedTmp['sub_total_price'];

                    if (!isset($subscribedData['project'][$projectName][$subscribed->sub_type]['sub_total_price'])) {
                        $subscribedData['project'][$projectName][$subscribed->sub_type]['sub_total_price'] = 0;
                    }
                    $subscribedData['project'][$projectName][$subscribed->sub_type]['sub_total_price'] += $subscribedTmp['sub_total_price'];

                    if (!isset($subscribedData['total'][$subscribed->sub_type]['sub_total_price'])) {
                        $subscribedData['total'][$subscribed->sub_type]['sub_total_price'] = 0;
                    }
                    $subscribedData['total'][$subscribed->sub_type]['sub_total_price'] += $subscribedTmp['sub_total_price'];

                }
            }

            $paymentRet->andFilterWhere([
                'sub_id' => $subIds
            ]);
            $paymentRet = $paymentRet->all();

            $paymentData = [];
            if (!empty($paymentRet)) {
                foreach ($paymentRet as $payment) {
                    // 根据payment的pay_type进行区分，如果是1就是支付，2就是退款，记录到paymentData的pay和refund里
                    // 每天一条数据，需要规整pay_time到日
                    $payTime = date('Y-m-d', $payment['pay_time']);
                    $projectName = !empty($payment->project->project_name) ? $payment->project->project_name : '未知项目';
                    $payment = $payment->toArray();
                    if ($payment['pay_type'] == Payment::PAYMENT_TYPE_PAY) {
                        if (!isset($paymentData['time'][$payTime]['pay'])) {
                            $paymentData['time'][$payTime]['pay'] = 0;
                        }
                        $paymentData['time'][$payTime]['pay'] += $payment['amount'];

                        if (!isset($paymentData['project'][$projectName]['pay'])) {
                            $paymentData['project'][$projectName]['pay'] = 0;
                        }
                        $paymentData['project'][$projectName]['pay'] += $payment['amount'];

                        if (!isset($paymentData['total']['pay'])) {
                            $paymentData['total']['pay'] = 0;
                        }
                        $paymentData['total']['pay'] += $payment['amount'];
                    } elseif ($payment['pay_type'] == Payment::PAYMENT_TYPE_REFUND) {
                        if (!isset($paymentData['time'][$payTime]['refund'])) {
                            $paymentData['time'][$payTime]['refund'] = 0;
                        }
                        $paymentData['time'][$payTime]['refund'] += $payment['amount'];

                        if (!isset($paymentData['project'][$projectName]['refund'])) {
                            $paymentData['project'][$projectName]['refund'] = 0;
                        }
                        $paymentData['project'][$projectName]['refund'] += $payment['amount'];

                        if (!isset($paymentData['total']['refund'])) {
                            $paymentData['total']['refund'] = 0;
                        }
                        $paymentData['total']['refund'] += $payment['amount'];
                    }

                }
            }


        } else {
            $reportRet = $reportCount->asArray()->all();
            $visitRet = $visitCount->asArray()->all();

            $reportDrift = [];
            $visitDrift = [];
            $visitRateDrift = [];
            $reportAll = $reportTemp['all'] = $reportRet[0]['ct'];
            $visitAll = $visitTemp['all'] = $visitRet[0]['ct'];
            if (!empty($reportRet[0]['ct'])) {
                $visitRateAll = $visitRate['all'] = round($visitRet[0]['ct'] / $reportRet[0]['ct'], 2);
            } else {
                $visitRateAll = $visitRate['all'] = 0;
            }

            $subscribedData = [];
            $subIds = [];
            if (!empty($subscribedRet)) {
                $subCount = 0;
                $subArea = 0;
                foreach ($subscribedRet as $subscribed) {
                    $projectName = !empty($subscribed->project->project_name) ? $subscribed->project->project_name : '未知项目';
                    $subscribedTmp = $subscribed->toArray();
                    $subIds[] = $subscribed->id;
//                    $subCount++;
//                    $subArea += $subscribedTmp['building_area'];

                    if (!isset($subscribedData['project'][$projectName][$subscribed->sub_type]['count'])) {
                        $subscribedData['project'][$projectName][$subscribed->sub_type]['count'] = 0;
                    }
                    $subscribedData['project'][$projectName][$subscribed->sub_type]['count'] += 1;

                    if (!isset($subscribedData['total'][$subscribed->sub_type]['count'])) {
                        $subscribedData['total'][$subscribed->sub_type]['count'] = 0;
                    }
                    $subscribedData['total'][$subscribed->sub_type]['count'] += 1;

                    if (!isset($subscribedData['project'][$projectName][$subscribed->sub_type]['building_area'])) {
                        $subscribedData['project'][$projectName][$subscribed->sub_type]['building_area'] = 0;
                    }
                    $subscribedData['project'][$projectName][$subscribed->sub_type]['building_area'] += $subscribedTmp['building_area'];

                    if (!isset($subscribedData['total'][$subscribed->sub_type]['building_area'])) {
                        $subscribedData['total'][$subscribed->sub_type]['building_area'] = 0;
                    }
                    $subscribedData['total'][$subscribed->sub_type]['building_area'] += $subscribedTmp['building_area'];

                    if (!isset($subscribedData['project'][$projectName][$subscribed->sub_type]['sub_total_price'])) {
                        $subscribedData['project'][$projectName][$subscribed->sub_type]['sub_total_price'] = 0;
                    }
                    $subscribedData['project'][$projectName][$subscribed->sub_type]['sub_total_price'] += $subscribedTmp['sub_total_price'];

                    if (!isset($subscribedData['total'][$subscribed->sub_type]['sub_total_price'])) {
                        $subscribedData['total'][$subscribed->sub_type]['sub_total_price'] = 0;
                    }
                    $subscribedData['total'][$subscribed->sub_type]['sub_total_price'] += $subscribedTmp['sub_total_price'];

                    if (!isset($subscribedData['project'][$projectName]['all']['sub_total_price'])) {
                        $subscribedData['project'][$projectName]['all']['sub_total_price'] = 0;
                    }
                    $subscribedData['project'][$projectName]['all']['sub_total_price'] += $subscribedTmp['sub_total_price'];

                }
            }

            $paymentRet->andFilterWhere(['sub_id' => $subIds]);
            $paymentRet = $paymentRet->all();
            $paymentData = [];
            if (!empty($paymentRet)) {
                foreach ($paymentRet as $payment) {
                    // 根据payment的pay_type进行区分，如果是1就是支付，2就是退款，记录到paymentData的pay和refund里
                    // 每天一条数据，需要规整pay_time到日
//                    $payTime = 'all';
                    $projectName = !empty($payment->project->project_name) ? $payment->project->project_name : '未知项目';
                    $payment = $payment->toArray();
                    if ($payment['pay_type'] == Payment::PAYMENT_TYPE_PAY) {
                        if (!isset($paymentData['project'][$projectName]['pay'])) {
                            $paymentData['project'][$projectName]['pay'] = 0;
                        }
                        $paymentData['project'][$projectName]['pay'] += $payment['amount'];
                        if (!empty($subscribedData['project'][$projectName]['all']['sub_total_price'])) {
                            $paymentData['project'][$projectName]['wait_pay'] = $subscribedData['project'][$projectName]['all']['sub_total_price'] - $paymentData['project'][$projectName]['pay'];
                        }

                        if (!isset($paymentData['total']['pay'])) {
                            $paymentData['total']['pay'] = 0;
                        }
                        $paymentData['total']['pay'] += $payment['amount'];
                    } elseif ($payment['pay_type'] == Payment::PAYMENT_TYPE_REFUND) {
                        if (!isset($paymentData['project'][$projectName]['refund'])) {
                            $paymentData['project'][$projectName]['refund'] = 0;
                        }
                        $paymentData['project'][$projectName]['refund'] += $payment['amount'];

                        if (!isset($paymentData['total']['refund'])) {
                            $paymentData['total']['refund'] = 0;
                        }
                        $paymentData['total']['refund'] += $payment['amount'];
                    }

                }
            }
        }

//        $reportCt = $reportCount->count();
//        $visitCt = $visitCount->count();

//        $visitRate = $visitCt / $reportCt * 100;
//        $visitRate = round($visitRate, 2);

        $data = [
            'report_count' => $reportTemp,
            'visit_count' => $visitTemp,
            'visit_rate' => $visitRate,
            'report_all' => $reportAll,
            'visit_all' => $visitAll,
            'visit_rate_all' => $visitRateAll,
            'report_drift' => $reportDrift,
            'visit_drift' => $visitDrift,
            'visit_rate_drift' => $visitRateDrift,
            'payment_data' => $paymentData,
            'subscribed_data' => $subscribedData,
        ];

//        $rule = Staff::$staffRole2rule[Staff::STAFF_ROLE_ADMIN_PART];
        $ruleJson = $this->_staff->rules;
        if (!empty($ruleJson)) {
            $rule = json_decode($ruleJson, true);
        }

        $data = $this->_filterByRule($data, $rule);
//        var_dump($data);

        return $this->success($data);

    }


    private function _filterByRule($data, $rules, $prevKeys = []) {
        $ret = [];

        if (empty($rules)) {
            return [];
        }
        
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_array($item)) {
                    // 如果是数组，递归调用
                    $subResult = $this->_filterByRule($item, $rules, array_merge($prevKeys, [$key]));
                    // 只有当子结果不为空时，才添加到ret中
                    if (!empty($subResult)) {
                        $ret[$key] = $subResult;
                    }
                } else {
                    // 如果是值，判断是否在规则中
                    // 构建匹配键：第一个字段名_最里层字段名
                    $firstKey = !empty($prevKeys) ? $prevKeys[0] : $key;
                    $lastKey = $key;
                    // 用正则判断，如果lastKey是日期，firstKey就只取原来的
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastKey)) {
                        // 如果是日期格式，lastKey就不需要了$firstKey
                        $filterKey = $firstKey;
                    } else if ($firstKey == $lastKey) {
                        $filterKey = $firstKey;
                    } else {
                        $filterKey = $firstKey . '_' . $lastKey;
                    }
                    
                    if (in_array($filterKey, $rules)) {
                        // 如果在规则中，就记录到ret里
                        // 按照原来的层级结构重建数据
                        $tmp = &$ret;
//                        foreach ($prevKeys as $setKey) {
//                            if (!isset($tmp[$setKey])) {
//                                $tmp[$setKey] = [];
//                            }
//                            $tmp = &$tmp[$setKey];
//                        }
                        $tmp[$key] = $item;
                    }
                }
            }
        }
        
        return $ret;
    }

    public function exportGuestList()
    {
        try {
            $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
            $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] . ' 00:00:00' : '';
            $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] . ' 23:59:59' : '';

            // 使用 join 查询获取所有需要的数据
            $query = Visit::find();
//                ->select([
//                    'o_visit.*',
//                    'o_project.project_name as project_name',
//                    'o_subscribed.*'
//                ])
//                ->joinWith('project')
//                ->joinWith('subscribed');

            if (!empty($projectId)) {
                $query->andWhere(['project_id' => $projectId]);
            }
            if (!empty($beginTime)) {
                $query->andFilterWhere(['>', 'visit_time', $beginTime]);
            }
            if (!empty($endTime)) {
                $query->andFilterWhere(['<', 'visit_time', $endTime]);
            }
            switch ($this->_staff->role) {
                case Staff::STAFF_ROLE_SALES:
                    $query->andFilterWhere(['staff_id' => $this->_staff->id]);
                    break;
                default:
                    break;
            }

            $visits = $query->orderBy(['created_at' => SORT_DESC])->asArray()->all();

            // 准备Excel数据
            $data = [];
            $headers = [
                '序号',
                // 访客基本信息
                '访客姓名',
                '访客手机号',
                '访客诉求',
                '预算',
                '到访时间',
                '到访状态',
                '确认状态',
                '到访人数',
                '客户描摹',
                // 认购基本信息
                '所属渠道',
                '经纪人姓名',
                '经纪人手机号',
                '认购类型',
                '认购人',
                '房间号',
                '建筑面积',
                '认购总价',
                '支付方式',
                '认购状态',
//                '支付状态',
                // 身份证信息
                '证件类型',
                '证件号码',
                // 业主信息
                '业主',
                '出租方',
                '出租方详情',
                // 租赁信息
                '租赁开始日期',
                '租赁结束日期',
                '免租期',
                '递增日期',
                '递增比例',
                '押金',
                // 租金信息
                '日租金',
                '月租金',
                '年租金',
                '租金总额',
                '优惠租金',
                '实际日租金',
                '实际租金总额',
                '其他费用',
                '总费用',
                // 补充信息
                '补充认购人',
                '补充证件类型',
                '补充证件号码',
                '补充手机号',
                '补充总价',
                // 项目信息
                '项目名称',
                // 员工信息
                '项目经理',
                '招商顾问',
                '投资顾问',
                '财务',
                // 支付信息
                '付款人',
                '付款类型',
                '付款时间',
                '支付方式',
                '付款金额',
                '款项性质',
                '付款账户',
                '收款户名',
                '收据编号',
                '到账金额',
                '手续费',
                '到账时间',
                '付款状态',
                
            ];

            $i = 0;
            foreach ($visits as $visit) {
                $project = Project::find()->where(['id' => $visit['project_id']])->one();
                $report = Report::find()->where(['id' => $visit['report_id']])->one();
                switch ($this->_staff->role) {
                    case Staff::STAFF_ROLE_SALES:
                        break;
                    default:
                        $sub = Subscribed::find()->where(['visit_id' => $visit['id']])->one();
                        $payments = !empty($sub->payments) ? $sub->payments : [];
                        break;
                }

//                if (!empty($visit['guest_mobile'])) {
//                    $guestMobiles = \common\helpers\Common::splitMobile($visit['guest_mobile']);
//                    if (!empty($guestMobiles)) {
//                        foreach ($guestMobiles as $guestMobile) {
//                            $sub = Subscribed::find()->where(['mobile' => $guestMobile])->all();
//                        }
//                    }
//                }
                $row = [
                    ++$i,
                    // 访客基本信息
                    $visit['guest_name'],
                    $visit['guest_mobile'],
                    Visit::$visitGuestAppeal2Name[$visit['guest_appeal']] ?? '',
                    $visit['budget'],
                    $visit['visit_time'],
                    Visit::$visitStatus2Name[$visit['visit_status']] ?? '',
                    Visit::$visitConfirm2Name[$visit['visit_confirm_status']] ?? '',
                    $visit['person_ct'] + 1,
                    $visit['visit_status_comment'],
                    // 认购基本信息
                    $report->guest_channel ?? '',
                    $report->staff_name ?? '',
                    $report->staff_mobile ?? '',
                    !empty($sub->sub_type) ? ($sub->sub_type == 1 ? '全款' : '部分') : '',
                    $sub->sub_guest ?? '',
                    $sub->room_no ?? '',
                    $sub->building_area ?? '',
                    $sub->sub_total_price ?? '',
                    $sub->pay_method ?? '',
                    !empty($sub->sub_status) ? Subscribed::$subscribedStatus2Name[$sub->sub_status] ?? '' : '',
                    // 身份证信息
                    $sub->id_type ?? '',
                    $sub->id_no ?? '',
                    // 业主信息
                    $sub->owner ?? '',
                    $sub->lessor ?? '',
                    $sub->lessor_detail ?? '',
                    // 租赁信息
                    $sub->rent_date_begin ?? '',
                    $sub->rent_date_end ?? '',
                    $sub->free_rent_date ?? '',
                    $sub->increase_date ?? '',
                    $sub->increase_rate ?? '',
                    $sub->deposit ?? '',
                    // 租金信息
                    $sub->daily_amount ?? '',
                    $sub->monthly_amount ?? '',
                    $sub->yearly_amount ?? '',
                    $sub->rent_amount ?? '',
                    $sub->pro_rent_amount ?? '',
                    $sub->al_daily_amount ?? '',
                    $sub->al_amount ?? '',
                    $sub->al_other ?? '',
                    $sub->al_total_amount ?? '',
                    // 补充信息
                    $sub->supply_sub_guest ?? '',
                    $sub->supply_guest_id_type ?? '',
                    $sub->supply_guest_id_no ?? '',
                    $sub->supply_guest_mobile ?? '',
                    $sub->supply_total_price ?? '',
                    // 项目信息
                    $project->project_name ?? '',
                    // 员工信息
                    $project->pmStaff->staff_name ?? '',
                    $report->consultantStaff->staff_name ?? '',
                    $report->advisorStaff->staff_name ?? '',
                    $project->financialStaff->staff_name ?? '',
                    
                ];
                if (!empty($payments)) {
                    foreach ($payments as $pay) {
                        $tmp = $row;
                        $tmp[] = $pay->payer ?? '';
                        $tmp[] = !empty(Payment::$paymentType2Name[$pay->pay_type]) ? Payment::$paymentType2Name[$pay->pay_type] : '';
                        $tmp[] = $pay->pay_time ? date('Y-m-d H:i:s', $pay->pay_time) : '';
                        $tmp[] = !empty(Payment::$paymentWay2Name[$pay->pay_way]) ? Payment::$paymentWay2Name[$pay->pay_way] : '';
                        $tmp[] = $pay->amount ?? '';
                        $tmp[] = $pay->amount_type ?? '';
                        $tmp[] = $pay->pay_account ?? '';
                        $tmp[] = $pay->recv_account ?? '';
                        $tmp[] = $pay->receipt_no ?? '';
                        $tmp[] = $pay->recv_amount ?? '';
                        $tmp[] = $pay->fee ?? '';
                        $tmp[] = $pay->recv_time ? date('Y-m-d H:i:s', $pay->recv_time) : '';
                        $tmp[] = !empty(Payment::$paymentStatus2Name[$pay->status]) ? Payment::$paymentStatus2Name[$pay->status] : '';
                    }
                    $data[] = $tmp;
                } else {
                    $data[] = $row;
                }
            }

            
            // 生成Excel文件
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // 计算总行数和列数
            $totalRows = count($data) + 1;
            $totalColumns = count($headers);
            $highestColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);

            // 写入表头
            foreach ($headers as $key => $header) {
                $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
            }

            // 写入数据
            foreach ($data as $row => $rowData) {
                foreach ($rowData as $col => $value) {
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . ($row + 2);
                    $sheet->setCellValue($cellCoordinate, $value);
                    
                    // 特殊处理身份证号码字段（第18列和第39列）
                    if ($col == 18 || $col == 39) { // 身份证号码和补充身份证号码
//                        $sheet->getStyle($cellCoordinate)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                        // 强制设置为文本格式，在值前加单引号
                        $sheet->setCellValueExplicitByColumnAndRow($col + 1, $row + 2, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
//                        if (!empty($value) && is_numeric($value)) {
//                            $sheet->setCellValue($cellCoordinate, $value);
//                        }
                    }
                }
            }

            // 设置所有单元格为文本格式
            $sheet->getStyle('A1:' . $highestColumn . $totalRows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            // 创建保存目录
            $saveDir = Yii::getAlias('@frontend/web/xls');
            if (!file_exists($saveDir)) {
                mkdir($saveDir, 0777, true);
            }

            // 生成文件名
            $fileName = '访客列表_' . date('YmdHis') . '.xlsx';
            $filePath = $saveDir . '/' . $fileName;

            // 保存Excel文件
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);

            // 返回文件URL
            $fileUrl = Yii::$app->request->baseUrl . '/xls/' . $fileName;
            
            return $this->success([
                'file_url' => $fileUrl,
                'file_name' => $fileName
            ]);

        } catch (\Exception $e) {
            Yii::error('导出Excel失败: ' . $e->getMessage());
            return $this->fail('导出失败：' . $e->getMessage());
        }
    }

    public function getReportList() {
        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $pageSize = !empty($this->_get['page_size']) ? $this->_get['page_size'] : 20;

        $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
        $advStaffId = !empty($this->_get['adv_staff_id']) ? $this->_get['adv_staff_id'] : 0;
        $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] : '';
        $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] : '';

        if (strpos($projectId, ',') !== false) {
            $projectId = explode(',', $projectId);
        } else {
            $projectId = [$projectId];
        }
        $projectId = $this->_getRoleProduct($projectId);


        $reportList = Report::find();
        switch ($this->_staff->role) {
            case Staff::STAFF_ROLE_SALES:
                $reportList->andFilterWhere(['staff_id' => $this->_staff->id]);
                break;
            case Staff::STAFF_ROLE_ADVISOR:
                $reportList->andFilterWhere(['advisor_staff_id' => $this->_staff->id]);
                break;
            case Staff::STAFF_ROLE_CONSULTANT:
                $reportList->andFilterWhere(['consultant_staff_id' => $this->_staff->id]);
                break;
            default:
                break;
        }
        if (!empty($guestMobile)) {
            $reportList->andFilterWhere(['guest_mobile' => $guestMobile]);
        }
        if (!empty($projectId)) {
            $reportList->andFilterWhere(['project_id' => $projectId]);
        }
        if (!empty($advStaffId)) {
            $reportList->andFilterWhere(['adv_staff_id' => $advStaffId]);
        }
        if (!empty($beginTime)) {
            $reportList->andFilterWhere(['>=', 'visit_time', $beginTime]);
        }
        if (!empty($endTime)) {
            $reportList->andFilterWhere(['<=', 'visit_time', $endTime]);
        }
        $reportListCount = $reportList = $reportList->orderBy(['id' => SORT_DESC]);

        $count = $reportListCount->count();
        $data = $reportList->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->all();

        $ret = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $one = $row->toArray();
                $lastReports = $row->lastReports;
                if (!empty($lastReports)) {
                    foreach ($lastReports as $lastReport) {
                        if ($lastReport->guest_mobile != $row->guest_mobile) {
                            $one['guest_mobile_tag'] = 1;
                            break;
                        } else {
                            $one['guest_mobile_tag'] = 0;
                        }
                    }
                }

                $one['guest_mobile']  = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $one['guest_mobile']);
                $ret[] = $one;
            }
        }

        return $this->success([
            'list' => $ret,
            'total_count' => $count,
            'page' => $page,
            'page_size' => $pageSize,
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

        if (strpos($projectId, ',') !== false) {
            $projectId = explode(',', $projectId);
        } else {
            $projectId = [$projectId];
        }
        $projectId = $this->_getRoleProduct($projectId);


        $reportList = Report::find();
        switch ($this->_staff->role) {
            case Staff::STAFF_ROLE_SALES:
                $reportList->andFilterWhere(['staff_id' => $this->_staff->id]);
                $repTag = 1;
                break;
            case Staff::STAFF_ROLE_ADVISOR:
                $reportList->andFilterWhere(['advisor_staff_id' => $this->_staff->id]);
                $repTag = 1;
                break;
            case Staff::STAFF_ROLE_CONSULTANT:
                $reportList->andFilterWhere(['consultant_staff_id' => $this->_staff->id]);
                $repTag = 1;
                break;
            default:
                $repTag = 0;
                break;
        }
        $reportList = $reportList->all();

        if (!empty($reportList) && $repTag == 1) {
            foreach ($reportList as $rep) {
                $reportIds[] = $rep->id;
            }
        }

        $visitList = Visit::find();
        if (!empty($guestMobile)) {
            $visitList->andFilterWhere(['guest_mobile' => $guestMobile]);
        }
        if (!empty($projectId)) {
            $visitList->andFilterWhere(['project_id' => $projectId]);
        }
//        if (!empty($advStaffId)) {
//            $visitList->andFilterWhere(['adv_staff_id' => $advStaffId]);
//        }
        if (!empty($reportIds)) {
            $visitList->andFilterWhere(['report_id' => $reportIds]);
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
            ->limit($pageSize)
            ->all();

        $ret = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $one = $row->toArray();
                $report = $row->report;
                if ($report->guest_mobile != $row->guest_mobile) {
                    $one['guest_mobile_tag'] = 1;
                } else {
                    $one['guest_mobile_tag'] = 0;
                }
                $one['guest_mobile']  = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $one['guest_mobile']);
                $ret[] = $one;
            }
        }

        return $this->success([
            'visit' => $ret,
            'total_count' => $count,
            'page' => $page,
            'page_size' => $pageSize,
        ]);

    }

    private function _getRoleProduct($oldProjectId = []) {
        $projectId = [];
        if (!empty($this->_staff)) {
            $staffRole = $this->_staff->role;

            switch ($staffRole) {
                case Staff::STAFF_ROLE_PM:
                    $projects = Project::find()
                        ->where(['pm_staff_id' => $this->_staffId])
                        ->all();
                    if (!empty($projects)) {
                        foreach ($projects as $pro) {
                            $projectId[] = $pro->id;
                        }
                    } else {
                        $projectId = [-1];
                    }
                    break;
                case Staff::STAFF_ROLE_ADMIN_PART:
                    $projects = Project::find()
                        ->where(['like', 'senior_pm_staff_id', ',' . $this->_staffId . ','])
                        ->all();
                    if (!empty($projects)) {
                        foreach ($projects as $pro) {
                            $projectId[] = $pro->id;
                        }
                    } else {
                        $projectId = [-1];
                    }
                    break;
                case Staff::STAFF_ROLE_SALES:
                    $projects = Report::find()
                        ->where(['staff_id' => $this->_staffId])
                        ->all();
                    if (!empty($projects)) {
                        foreach ($projects as $pro) {
                            $projectId[] = $pro->project_id;
                        }
                    } else {
                        $projectId = [-1];
                    }
                    break;
                case Staff::STAFF_ROLE_ADVISOR:
                    $projects = Report::find()
                        ->where(['advisor_staff_id' => $this->_staffId])
                        ->all();
                    if (!empty($projects)) {
                        foreach ($projects as $pro) {
                            $projectId[] = $pro->project_id;
                        }
                    } else {
                        $projectId = [-1];
                    }
                    break;
                case Staff::STAFF_ROLE_CONSULTANT:
                    $projects = Report::find()
                        ->where(['consultant_staff_id' => $this->_staffId])
                        ->all();
                    if (!empty($projects)) {
                        foreach ($projects as $pro) {
                            $projectId[] = $pro->project_id;
                        }
                    } else {
                        $projectId = [-1];
                    }
                    break;
                case Staff::STAFF_ROLE_ADMIN:
//                case Staff::STAFF_ROLE_ADMIN_PART:
                case Staff::STAFF_ROLE_ADMIN_CHILD:
                case Staff::STAFF_ROLE_FINANCE:
                    break;
                default:
                    $projectId = [-1];
                    break;
            }


        } else {
            $projectId = [-1];
        }

        if (!empty($oldProjectId) && !empty($projectId)) {
            if (is_array($oldProjectId) > 0 && $oldProjectId[0] != 0) {
                $projectId = array_intersect($oldProjectId, $projectId);
            }
        }
        if (empty($projectId)) {
            $projectId = [-1];
        }

        return $projectId;
    }


}