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
//            $reportCount->andFilterWhere(['adv_staff_id' => $advStaffId]);
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

        $reportTemp = [];
        $visitTemp = [];
        $visitRate = [];

        if ($inter == 'daily') {
            $reportCount->groupBy('DATE(visit_time)');
            $visitCount->groupBy('DATE(visit_time)');


            $reportRet = $reportCount->asArray()->all();
            $visitRet = $visitCount->asArray()->all();

            //        $reportCt = $reportRet['ct'];
            //        $visitCt = $visitRet['ct'];

            if (!empty($reportRet)) {
                foreach ($reportRet as $reportOne) {
                    $reportTemp[$reportOne['dt']] = $reportOne['ct'];
                }
            }

            if (!empty($visitRet)) {
                foreach ($visitRet as $visitOne) {
                    $visitTemp[$visitOne['dt']] = $visitOne['ct'];
                }
            }

            if (!empty($reportTemp)) {
                foreach ($reportTemp as $rdt => $rct) {
                    if (isset($visitTemp[$rdt])) {
                        $visitRate[$rdt] = round($visitTemp[$rdt] / $rct, 2);
                    } else {
                        $visitRate[$rdt] = 0;
                    }
                }
            }
        } else {
            $reportRet = $reportCount->asArray()->all();
            $visitRet = $visitCount->asArray()->all();

            $reportTemp['all'] = $reportRet['ct'];
            $visitTemp['all'] = $visitRet['ct'];
            $visitRate['all'] = round($visitRet['ct'] / $reportRet['ct'], 2);
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


    public function exportGuestList()
    {
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

        // 使用 join 查询获取所有需要的数据
        $query = Visit::find()
            ->select([
                'visit.*',
                'project.name as project_name',
                'staff.name as staff_name',
                'subscribed.*'
            ])
            ->joinWith('project')
            ->joinWith('subscribed');
//            ->joinWith('staff');
            // ->leftJoin('o_project', 'visit.project_id = o_project.id')
            // ->leftJoin('o_staff', 'visit.adv_staff_id = o_staff.id')
            // ->leftJoin('o_subscribed', 'visit.guest_mobile = o_subscribed.mobile AND o_subscribed.project_id = o_project.id');

        if (!empty($projectId)) {
            $query->andWhere(['visit.project_id' => $projectId]);
        }

        $visits = $query->orderBy(['visit.id' => SORT_DESC])->all();

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
            $row = [
                // 访客基本信息
                $visit->guest_name,
                $visit->guest_mobile,
                Visit::$visitGuestAppeal2Name[$visit->guest_appeal] ?? '',
                $visit->budget,
                $visit->visit_time,
                $visit->visitStatus2Name[$visit->visit_status] ?? '',
                Visit::$visitConfirm2Name[$visit->visit_confirm_status] ?? '',
                $visit->person_ct,
                // 认购基本信息
                !empty($visit->sub_guest) ? '是' : '否',
                !empty($visit->sub_type) ? ($visit->sub_type == 1 ? '全款' : '部分') : '',
                $visit->sub_guest ?? '',
                $visit->room_no ?? '',
                $visit->building_area ?? '',
                $visit->sub_total_price ?? '',
                $visit->pay_method ?? '',
                !empty($visit->sub_status) ? Subscribed::$subscribedStatus2Name[$visit->sub_status] ?? '' : '',
                !empty($visit->pay_status) ? Subscribed::$subscribedStatus2Name[$visit->pay_status] ?? '' : '',
                // 身份证信息
                $visit->id_type ?? '',
                $visit->id_no ?? '',
                // 业主信息
                $visit->owner ?? '',
                $visit->lessor ?? '',
                $visit->lessor_detail ?? '',
                // 租赁信息
                $visit->rent_date_begin ?? '',
                $visit->rent_date_end ?? '',
                $visit->free_rent_date ?? '',
                $visit->increase_date ?? '',
                $visit->increase_rate ?? '',
                $visit->deposit ?? '',
                // 租金信息
                $visit->daily_amount ?? '',
                $visit->monthly_amount ?? '',
                $visit->yearly_amount ?? '',
                $visit->rent_amount ?? '',
                $visit->pro_rent_amount ?? '',
                $visit->al_daily_amount ?? '',
                $visit->al_amount ?? '',
                $visit->al_other ?? '',
                $visit->al_total_amount ?? '',
                // 补充信息
                $visit->supply_sub_guest ?? '',
                $visit->supply_guest_id_type ?? '',
                $visit->supply_guest_id_no ?? '',
                $visit->supply_guest_mobile ?? '',
                $visit->supply_total_price ?? '',
                // 项目信息
                $visit->project_name,
                // 员工信息
                $visit->project->pm_staff->staff_name ?? '',
                $visit->project->consultant_staff->staff_name ?? '',
                $visit->project->advisor_staff->staff_name ?? '',
                $visit->project->financial_staff->staff_name ?? '',
                
            ];
            $data[] = $row;
        }

        // 生成Excel文件
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 写入表头
        foreach ($headers as $key => $header) {
            $sheet->setCellValue(chr(65 + $key) . '1', $header);
        }

        // 写入数据
        foreach ($data as $row => $rowData) {
            foreach ($rowData as $col => $value) {
                $sheet->setCellValue(chr(65 + $col) . ($row + 2), $value);
            }
        }

        // 设置响应头
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="访客列表_' . date('YmdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        // 输出Excel文件
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
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