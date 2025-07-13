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
        $beginTime = !empty($this->_get['begin_time']) ? $this->_get['begin_time'] : '';
        $endTime = !empty($this->_get['end_time']) ? $this->_get['end_time'] : '';

        $guestMobile = !empty($this->_get['guest_mobile']) ? $this->_get['guest_mobile'] : '';
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
        $advStaffId = !empty($this->_get['adv_staff_id']) ? $this->_get['adv_staff_id'] : 0;
        $visitStatus = !empty($this->_get['visit_status']) ? $this->_get['visit_status'] : 0;
        $inter = !empty($this->_get['inter']) ? $this->_get['inter'] : 'daily';

        if (strpos($projectId, ',') !== false) {
            $projectId = explode(',', $projectId);
        }

        if (!empty($this->_staff)) {
            $staffRole = $this->_staff->role;

            if ($staffRole == Staff::STAFF_ROLE_PM) {
                $projects = Project::find()
                    ->where(['pm_staff_id' => $this->_staffId])
                    ->all();

//                $projectIds = [];
                if (!empty($projects)) {
                    foreach ($projects as $pro) {
                        $projectId[] = $pro->id;
                    }
                }
            }


        }

        if ($inter == 'daily') {
            $reportCount = Report::find()->select('DATE(visit_time) as dt, count(*) as ct');
            $visitCount = Visit::find()->select('DATE(visit_time) as dt, count(*) as ct');
            $paymentRet = Payment::find();
        } else {
            $reportCount = Report::find()->select('count(*) as ct');
            $visitCount = Visit::find()->select('count(*) as ct');
            $paymentRet = Payment::find();
        }
        if (!empty($guestMobile)) {
            $reportCount->andFilterWhere(['guest_mobile' => $guestMobile]);
            $visitCount->andFilterWhere(['guest_mobile' => $guestMobile]);
        }
        if (!empty($projectId)) {
            $reportCount->andFilterWhere(['project_id' => $projectId]);
            $visitCount->andFilterWhere(['project_id' => $projectId]);
            $paymentRet->andFilterWhere(['project_id' => $projectId]);
        }
        if (!empty($advStaffId)) {
//            $reportCount->andFilterWhere(['adv_staff_id' => $advStaffId]);
            $visitCount->andFilterWhere(['adv_staff_id' => $advStaffId]);
        }
        if (!empty($beginTime)) {
            $reportCount->andFilterWhere(['>=', 'visit_time', $beginTime]);
            $visitCount->andFilterWhere(['>=', 'visit_time', $beginTime]);
            $paymentRet->andFilterWhere(['>=', 'pay_time', $beginTime]);
        }
        if (!empty($endTime)) {
            $reportCount->andFilterWhere(['<=', 'visit_time', $endTime]);
            $visitCount->andFilterWhere(['<=', 'visit_time', $endTime]);
            $paymentRet->andFilterWhere(['<=', 'pay_time', $endTime]);
        }
        if (!empty($visitStatus)) {
            $visitCount->andFilterWhere(['visit_status' => $visitStatus]);
        }

        $reportTemp = [];
        $visitTemp = [];
        $visitRate = [];

        $paymentRet = $paymentRet->asArray()->all();

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

            $visitRateAll = round($visitAll / $reportAll, 2);
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

            $paymentData = [];
            if (!empty($paymentRet)) {
                foreach ($paymentRet as $payment) {
                    // 根据payment的pay_type进行区分，如果是1就是支付，2就是退款，记录到paymentData的pay和refund里
                    // 每天一条数据，需要规整pay_time到日
                    $payTime = date('Y-m-d', strtotime($payment['pay_time']));
                    if ($payment['pay_type'] == Payment::PAYMENT_TYPE_PAY) {
                        if (!isset($paymentData[$payTime]['pay'])) {
                            $paymentData[$payTime]['pay'] = 0;
                        }
                        $paymentData[$payTime]['pay'] += $payment['amount'];
                    } elseif ($payment['pay_type'] == Payment::PAYMENT_TYPE_REFUND) {
                        if (!isset($paymentData[$payTime]['refund'])) {
                            $paymentData[$payTime]['refund'] = 0;
                        }
                        $paymentData[$payTime]['refund'] += $payment['amount'];
                    }

                }
            }

        } else {
            $reportRet = $reportCount->asArray()->all();
            $visitRet = $visitCount->asArray()->all();

            $reportDrift = [];
            $visitDrift = [];
            $visitRateDrift = [];
            $reportAll = $reportTemp['all'] = $reportRet['ct'];
            $visitAll = $visitTemp['all'] = $visitRet['ct'];
            $visitRateAll = $visitRate['all'] = round($visitRet['ct'] / $reportRet['ct'], 2);

            if (!empty($paymentRet)) {
                foreach ($paymentRet as $payment) {
                    // 根据payment的pay_type进行区分，如果是1就是支付，2就是退款，记录到paymentData的pay和refund里
                    // 每天一条数据，需要规整pay_time到日
                    $payTime = 'all';
                    if ($payment['pay_type'] == Payment::PAYMENT_TYPE_PAY) {
                        if (!isset($paymentData[$payTime]['pay'])) {
                            $paymentData[$payTime]['pay'] = 0;
                        }
                        $paymentData[$payTime]['pay'] += $payment['amount'];
                    } elseif ($payment['pay_type'] == Payment::PAYMENT_TYPE_REFUND) {
                        if (!isset($paymentData[$payTime]['refund'])) {
                            $paymentData[$payTime]['refund'] = 0;
                        }
                        $paymentData[$payTime]['refund'] += $payment['amount'];
                    }

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
            'report_all' => $reportAll,
            'visit_all' => $visitAll,
            'visit_rate_all' => $visitRateAll,
            'report_drift' => $reportDrift,
            'visit_drift' => $visitDrift,
            'visit_rate_drift' => $visitRateDrift,
        ]);


    }


    public function exportGuestList()
    {
        try {
            $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

            // 使用 join 查询获取所有需要的数据
            $query = Visit::find()
                ->select([
                    'o_visit.*',
                    'o_project.project_name as project_name',
                    'o_subscribed.*'
                ])
                ->joinWith('project')
                ->joinWith('subscribed');

            if (!empty($projectId)) {
                $query->andWhere(['o_visit.project_id' => $projectId]);
            }

            $visits = $query->orderBy(['o_visit.created_at' => SORT_DESC])->asArray()->all();

            // 准备Excel数据
            $data = [];
            $headers = [
                // 访客基本信息
                '访客姓名',
                '访客手机号',
                '访客诉求',
                '预算',
                '到访时间',
                '到访状态',
                '确认状态',
                '到访人数',
                // 认购基本信息
                '是否认购',
                '认购类型',
                '认购人',
                '房间号',
                '建筑面积',
                '认购总价',
                '支付方式',
                '认购状态',
                '支付状态',
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
                
            ];

            foreach ($visits as $visit) {
                $project = Project::find()->where(['id' => $visit['project_id']])->one();
                $row = [
                    // 访客基本信息
                    $visit['guest_name'],
                    $visit['guest_mobile'],
                    Visit::$visitGuestAppeal2Name[$visit['guest_appeal']] ?? '',
                    $visit['budget'],
                    $visit['visit_time'],
                    Visit::$visitStatus2Name[$visit['visit_status']] ?? '',
                    Visit::$visitConfirm2Name[$visit['visit_confirm_status']] ?? '',
                    $visit['person_ct'],
                    // 认购基本信息
                    !empty($visit['sub_guest']) ? '是' : '否',
                    !empty($visit['sub_type']) ? ($visit['sub_type'] == 1 ? '全款' : '部分') : '',
                    $visit['sub_guest'] ?? '',
                    $visit['room_no'] ?? '',
                    $visit['building_area'] ?? '',
                    $visit['sub_total_price'] ?? '',
                    $visit['pay_method'] ?? '',
                    !empty($visit['sub_status']) ? Subscribed::$subscribedStatus2Name[$visit['sub_status']] ?? '' : '',
                    !empty($visit['pay_status']) ? Subscribed::$subscribedStatus2Name[$visit['pay_status']] ?? '' : '',
                    // 身份证信息
                    $visit['id_type'] ?? '',
                    $visit['id_no'] ?? '',
                    // 业主信息
                    $visit['owner'] ?? '',
                    $visit['lessor'] ?? '',
                    $visit['lessor_detail'] ?? '',
                    // 租赁信息
                    $visit['rent_date_begin'] ?? '',
                    $visit['rent_date_end'] ?? '',
                    $visit['free_rent_date'] ?? '',
                    $visit['increase_date'] ?? '',
                    $visit['increase_rate'] ?? '',
                    $visit['deposit'] ?? '',
                    // 租金信息
                    $visit['daily_amount'] ?? '',
                    $visit['monthly_amount'] ?? '',
                    $visit['yearly_amount'] ?? '',
                    $visit['rent_amount'] ?? '',
                    $visit['pro_rent_amount'] ?? '',
                    $visit['al_daily_amount'] ?? '',
                    $visit['al_amount'] ?? '',
                    $visit['al_other'] ?? '',
                    $visit['al_total_amount'] ?? '',
                    // 补充信息
                    $visit['supply_sub_guest'] ?? '',
                    $visit['supply_guest_id_type'] ?? '',
                    $visit['supply_guest_id_no'] ?? '',
                    $visit['supply_guest_mobile'] ?? '',
                    $visit['supply_total_price'] ?? '',
                    // 项目信息
                    $project->project_name ?? '',
                    // 员工信息
                    $project->pmStaff->staff_name ?? '',
                    $project->consultantStaff->staff_name ?? '',
                    $project->advisorStaff->staff_name ?? '',
                    $project->financialStaff->staff_name ?? '',
                    
                ];
                $data[] = $row;
            }

            // 生成Excel文件
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // 写入表头
            foreach ($headers as $key => $header) {
                $sheet->setCellValueByColumnAndRow($key + 1, 1, $header);
            }

            // 写入数据
            foreach ($data as $row => $rowData) {
                foreach ($rowData as $col => $value) {
                    $sheet->setCellValueByColumnAndRow($col + 1, $row + 2, $value);
                }
            }

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

        $reportList = Report::find();
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


}