<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/3
 * Time: 下午3:12
 */

namespace common\services;


use common\models\Documents;
use common\models\Member;
use common\models\Recommend;
use common\models\UserCompany;
use yii\base\Component;
use common\models\Company;
use yii\helpers\ArrayHelper;

class Common extends Component
{
    const SHOW_COL_VAL_MODE_OBJECT  = 1;
    const SHOW_COL_VAL_MODE_ARRAY   = 2;

    const SHOW_SELECT_MODE_EXCEPT_ZERO  = 1;
    const SHOW_SELECT_MODE_INCLUDE_ZERO = 2;

    public function showLoginBtnHref($href) {
        if (empty(\Yii::$app->user->id)) {
            return 'javascript:void(0);';
        }
        return $href;
    }

    public function showLoginBtnClass() {
        if (empty(\Yii::$app->user->id)) {
            return ' loginBtn';
        }
        return '';
    }

    public function showUploadFilePath($file, $default = '') {
        $fullPath = $default;
        if (!empty($file)) {
//            $fullPath = \Yii::$app->upload->uploadShowDir . '/' . $file;
            if (strpos($file, \Yii::$app->upload->uploadShowDir) === false
                && strpos($file, 'http://') === false
                && strpos($file, 'https://') === false)
            {
                $fullPath = \Yii::$app->upload->uploadShowDir . '/' . $file;
            } else {
                $fullPath = $file;
            }
        }
        return $fullPath;
    }

    public function showSalaryRange($min, $max){
        $minSalary = $this->formatAmountWithWan($min);
        $maxSalary = $this->formatAmountWithWan($max);

        if ($minSalary == $maxSalary && $minSalary == 0) {
            return '面议';
        }

        return $minSalary . '-' . $maxSalary;
    }

    public function showSalaryRangeBySingleUnit($min, $max){
        $minSalary = $this->formatAmountWithWan($min, '');
        $maxSalary = $this->formatAmountWithWan($max);

        if ($minSalary == str_replace('万', '', $maxSalary) && $minSalary == 0) {
            return '面议';
        }

        return $minSalary . '-' . $maxSalary;
    }

    public function showSalaryRangeWithK($min, $max){
        $minSalary = $this->formatAmountWithK($min);
        $maxSalary = $this->formatAmountWithK($max);

        if ($minSalary == $maxSalary && $minSalary == 0) {
            return '面议';
        }

        return $minSalary . '-' . $maxSalary;
    }

    public function formatAmountWithWan($amount, $unit = '万') {
        if (is_numeric($amount)) {
            if ($amount < 10000) {
                return (int)$amount;
            } else {
                return number_format($amount / 10000, 2) . $unit;
            }
        } else {
            return $amount;
        }
    }

    public function formatAmountWithK($amount) {
        if ($amount < 1000) {
            return number_format($amount, 2);
        } else {
            return number_format($amount / 1000, 2) . 'K';
        }
    }

    public function formatAmountCent($amount) {
        if (!$amount) {
            return 0;
        } else {
            return $amount / 100;
        }
    }

    public function formatTimestamp($timestamp) {
        $nowTime = strtotime('now');
        $interval = $nowTime - $timestamp;
        if ($interval < 60) {
            return '1分钟前';
        } elseif ($interval < 3600) {
            return intval($interval / 60) . '分钟前';
        } elseif ($interval < 24 * 3600) {
            return intval( $interval / 3600) . '小时前';
        } elseif ($interval < 30 * 24 * 3600) {
            return intval( $interval / 86400) . '天前';
        } else {
            //return intval( $interval / 86400) . '天前';
            return Date('Y-m-d', $timestamp);
        }
    }

    public function showMessageCount($ct, $defaultValue = 0, $maxValue = 999) {
        if (empty($ct)) {
            return $defaultValue;
        } else {
            if ($ct > $maxValue) {
                return $maxValue . '+';
            } else {
                return $ct;
            }
        }
    }

    public function jobIsMore($job) {
        if ($job->head_count > 1) {
            return true;
        } else {
            return false;
        }
    }

    public function jobIsHigher($job) {
        if ($job->estimated_share >= 80000) {
            return true;
        } else {
            return false;
        }

    }

    public function showDocumentAvatar(Documents $document) {
        if (!empty($document->avatar)) {
            return $document->avatar;
        } else {
            if ($document->gender == '男') {
                return '../../static/image/header-male.png';
            } else {
                return '../../static/image/header-female.png';
            }
        }
    }

    public function showCompany($job) {
        $jobCustomerCompany = !empty($job->customerCompany) ? $job->customerCompany : null;
        if (!empty($job->show_column)) {
            if ($job->show_column == 'group_name') {
                $showModel = $jobCustomerCompany;
            } elseif ($job->show_column == 'product_name') {
                $showModel = $job;
            }
            return $this->showColVal($showModel, $job->show_column, '-');
        } else {
            return !empty($jobCustomerCompany->company_name) ? $jobCustomerCompany->company_name : '未知';
        }
    }

    public function countCandidatesWithMode($jobCandidates) {
        $ct = 0;
        if (!empty($jobCandidates)) {
            foreach ($jobCandidates as $candidate) {
                if ($candidate->recommend_status == Recommend::RECOMMEND_STATUS_PASS
                    && $candidate->recommend_filter == Recommend::RECOMMEND_FILTER_CREATED
//                    && ($candidate->recommend_filter != Recommend::RECOMMEND_FILTER_TIMEOUT)
                ) {
                    $ct++;
                }
            }
        }
        return $ct;
    }

    public function countConsultantWithMode($jobCandidates) {
        $ret = [];
        if (!empty($jobCandidates)) {
            foreach ($jobCandidates as $candidate) {
//                if ($candidate->recommend_status == Recommend::RECOMMEND_STATUS_PASS
//                    && $candidate->recommend_filter == Recommend::RECOMMEND_FILTER_CREATED
////                    && ($candidate->recommend_filter != Recommend::RECOMMEND_FILTER_TIMEOUT)
//                ) {
                    if (empty($ret[$candidate->user_id])) {
                        $ret[$candidate->user_id] = 1;
                    } else {
                        $ret[$candidate->user_id]++;
                    }
//
//                }
            }
        }
        return count($ret);
    }

    public function showArray($model, $column, $split = '、') {
        $temp = [];
        if (!empty($model)) {
            foreach ($model as $one) {
                if (!is_array($column)) {
                    $val = $one->$column;
                } else {
                    $val = $one;
                    foreach ($column as $col) {
                        $val = $this->_createColName($val, $col);
                    }
                }
                if (!empty($val)) {
                    $temp[] = $val;

                }
            }
        }
        return implode($split, $temp);
    }

    private function _createColName($obj, $col) {
        if (!empty($obj) && !empty($col)) {
            if (is_object($obj)) {
                $obj = $obj->$col;
            }
        }
        return $obj;
    }

    public function showColVal($model, $column, $defaultValue = '', $dict = [], $mode = self::SHOW_COL_VAL_MODE_OBJECT) {

        if ($column == 'company_logo' &&
            $model instanceof Company) {
            $defaultValue = '未';
        }

        if ($mode == self::SHOW_COL_VAL_MODE_OBJECT) {
            $ret = !empty($model->$column)
                ? $model->$column
                : $defaultValue;
        } else {
            $ret = !empty($model[$column])
                ? $model[$column]
                : $defaultValue;
        }

        if (!empty($dict)) {
            $ret = !empty($dict[$ret]) ? $dict[$ret] : $ret;
        }
        return $ret;
    }

    public function hasRefresh($refreshTime) {
        $hasRefresh = false;
        if (!empty($refreshTime)) {
            if (!is_int($refreshTime)) {
                $refreshTime = strtotime($refreshTime);
            }
            $refreshDate = Date('Y-m-d', $refreshTime);
            if ($refreshDate == Date('Y-m-d')) {
                $hasRefresh = true;
            }
        }

        return $hasRefresh;

    }

    public function showSelect($model, $column, $val, $defaultValue = 'selected', $mode = self::SHOW_SELECT_MODE_EXCEPT_ZERO) {
        if ($mode == self::SHOW_SELECT_MODE_EXCEPT_ZERO) {
            if (!empty($model->$column) && !empty($val) && $model->$column == $val) {
                return $defaultValue;
            } else {
                return '';
            }
        } else {
            if (isset($model->$column) && isset($val) && $model->$column == $val) {
                return $defaultValue;
            } else {
                return '';
            }
        }
    }

    public function showJsonStr($json, $splitStr = '<br>', $headerStr = '', $footerStr = '') {
        $str = json_decode($json, true);
        $str = str_replace("\n", $splitStr, $str);
        $str = $headerStr . $str . $footerStr;
        return $str;
    }

    public function getLatestRecommend($infoArray) {
        if (empty($infoArray)) {
            return null;
        }

        $latestTime = 0;
        foreach ($infoArray as $subArray) {
            if (!empty($subArray)) {
                foreach ($subArray as $arr) {
                    if (!empty($arr->updated_at) && $arr->updated_at > $latestTime) {
                        $ret = $arr;
                        $latestTime = $arr->updated_at;
                    }
                }
            }
        }

//        $currentKey = key($infoArray);
//        $subRecommend = Recommend::$recommendFilterShowInfo;
//
//        if (!empty($subRecommend[$currentKey])) {
//            $subKey = $subRecommend[$currentKey];
//        } else {
//            $subKey = $currentKey;
//        }
//
//        $count = count($infoArray[$subKey]);
//        $ret = !empty($infoArray[$subKey][$count - 1])
//            ? $infoArray[$subKey][$count - 1]
//            : null;

        return $ret;
    }

    public function formatRecommendInfoByArray($infoArray) {
        if (empty($infoArray)) {
            return null;
        }
        $currentKey = key($infoArray);
//        var_dump($currentKey);exit;
//        $currentVal = $infoArray[$currentKey];

        $subRecommend = Recommend::$recommendFilterShowInfo;
        if (!empty($subRecommend[$currentKey])
            && !empty($infoArray[$subRecommend[$currentKey]])
        ) {
            $currentArray = $infoArray[$subRecommend[$currentKey]];
        } else {
            $currentArray = $infoArray[$currentKey];
        }

//        var_dump($currentArray);
//        exit;

        $maxIndex = count($currentArray) - 1;

        $currentInfo = $currentArray[$maxIndex];

        return $this->formatRecommendInfo($currentInfo);
    }

    public function formatRecommendInfo($info) {
        $ret = $info->recommend_info;
        if (!empty($ret)) {
            if ($info['recommend_status'] == Recommend::RECOMMEND_STATUS_PASS) {
                switch ($info['recommend_filter_detail']) {
                    case Recommend::RECOMMEND_FILTER_AUDITION:
                        $retJson = json_decode($ret, true);
                        if ($info['recommend_status'] == \common\models\Recommend::RECOMMEND_STATUS_PASS) {
                            $ret = '面试时间：' . $retJson['audition_date'] . '<br>地点：' . $retJson['audition_address'];
                        }
                        break;
                    case Recommend::RECOMMEND_FILTER_ACCEPT_OFFER:
                        $retJson = json_decode($ret, true);
                        $ret = '<div >
                        <span>入职时间：</span>
                        ' . ArrayHelper::getValue($retJson, 'join_date', '-') . '
                        </div><div class=\'m-t-10\'>
                        <span>入职地点：</span>
                        ' . ArrayHelper::getValue($retJson, 'join_address', '-') . '
                        </div>
                        <div class=\'m-t-10\'>
                        <span>试用期：</span>
                        ' . ArrayHelper::getValue($retJson, 'trial_peroid', '-') . '
                        </div>
                        <div class=\'m-t-10\'>
                        <span>附件：</span>
                        <a href=\'' . ArrayHelper::getValue($retJson, 'attachement', '-') . '\' class=\'text-F6 a-underline\'>下载</a>
                        </div><div class=\'m-t-10\'>
                        <span>备注：</span>
                        ' . ArrayHelper::getValue($retJson, 'remarks', '-') . '</div>';
                        break;

                }
            } else {
                $retJson = json_decode($ret, true);
                $ret = '<div >
                <span>理由：</span>';
                $ret .= !empty($retJson['reason']) ? $retJson['reason'] : '';


            }
        }
//        switch ($info['recommend_filter_detail']) {
//            case \common\models\Recommend::RECOMMEND_FILTER_AUDITION:
//                $retJson = json_decode($ret, true);
//                if ($info['recommend_status'] == \common\models\Recommend::RECOMMEND_STATUS_PASS) {
////                    $ret = '面试时间：' . $retJson['audition_date'] . '<br>地点：' . $retJson['audition_address'];
//                } else {
//                    $ret = '理由：';
//                    $ret .= !empty($retJson['refuse_audition_reason']) ? $retJson['refuse_audition_reason'] : '';
//                }
//                break;
//            case \common\models\Recommend::RECOMMEND_FILTER_ENTRY:
//                $retJson = json_decode($ret, true);
//                if ($info['recommend_status'] == Recommend::RECOMMEND_STATUS_PASS) {
//                    $ret = '<div >
//                        <span>原因：</span>
//                        ' . ArrayHelper::getValue($retJson, 'reason', '-') . '
//                        </div><div class=\'m-t-10\'>
//                        <span>Offer金额：</span>
//                        ' . ArrayHelper::getValue($retJson, 'offer_amount_offer', '-') . '
//                        </div>
//                        <div class=\'m-t-10\'>
//                        <span>分成金额：</span>
//                        ' . ArrayHelper::getValue($retJson, 'divide_amount', '-') . '
//                        </div>';
//                }
//                break;
//
//            case \common\models\Recommend::RECOMMEND_FILTER_ACCEPT_OFFER:
//                $retJson = json_decode($ret, true);
//                if ($info['recommend_status'] == Recommend::RECOMMEND_STATUS_PASS) {
//                    $ret = '<div >
//                        <span>入职时间：</span>
//                        ' . ArrayHelper::getValue($retJson, 'join_date', '-') . '
//                        </div><div class=\'m-t-10\'>
//                        <span>入职地点：</span>
//                        ' . ArrayHelper::getValue($retJson, 'join_address', '-') . '
//                        </div>
//                        <div class=\'m-t-10\'>
//                        <span>试用期：</span>
//                        ' . ArrayHelper::getValue($retJson, 'trial_peroid', '-') . '
//                        </div>
//                        <div class=\'m-t-10\'>
//                        <span>附件：</span>
//                        <a href=\'' . ArrayHelper::getValue($retJson, 'attachement', '-') . '\' class=\'text-F6 a-underline\'>下载</a>
//                        </div><div class=\'m-t-10\'>
//                        <span>备注：</span>
//                        ' . ArrayHelper::getValue($retJson, 'remarks', '-') . '</div>';
//                } elseif ($info['recommend_status'] == Recommend::RECOMMEND_STATUS_FAILED) {
//                    $ret = '<div >
//                        <span>理由：</span>';
//                    $ret .= !empty($retJson['refuse_audition_reason']) ? $retJson['refuse_audition_reason'] : '';
//                    $ret .= '
//                        </div><div class=\'m-t-10\'>
//                        <span>Offer金额：</span>
//                        ' . ArrayHelper::getValue($retJson, 'offer_amount', '-') . '
//                        </div>
//                        <div class=\'m-t-10\'>
//                        <span>分成金额：</span>
//                        ' . ArrayHelper::getValue($retJson, 'divide_amount', '-') . '
//                        </div>';
//                }
//                break;
//            case Recommend::RECOMMEND_FILTER_COMPANY_FILTER:
////                if ($info['recommend_status'] == Recommend::RECOMMEND_STATUS_FAILED) {
//                    $retJson = json_decode($ret, true);
//                    $ret = $retJson['reason'];
////                }
//                break;
//            case Recommend::RECOMMEND_FILTER_RECOMMEND:
//            case Recommend::RECOMMEND_FILTER_READ:
//            case Recommend::RECOMMEND_FILTER_COMPANY_FILTER:
//            case Recommend::RECOMMEND_FILTER_TIMEOUT:
////                $retJson = json_decode($ret, true);
////                $ret = $retJson['reason'];
//                break;
//        }
        return $ret;
    }

    public function formatRecommend(Recommend $model, $req) {
//        var_dump($req);
        if (!empty($model->recommend_status)) {
            if ($model->recommend_status == Recommend::RECOMMEND_STATUS_PASS) {
                if (!empty($model->recommend_filter_detail)) {
                    switch ($model->recommend_filter_detail) {
                        case Recommend::RECOMMEND_FILTER_AUDITION:
                            $info = [
                                'reason' => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
                                'audition_date' => !empty($req['audition_date']) ? $req['audition_date'] : '',
                                'audition_address' => !empty($req['audition_address']) ? $req['audition_address'] : '',
                            ];
                            break;
                        case Recommend::RECOMMEND_FILTER_ACCEPT_OFFER:
                            $info = [
                                'reason' => !empty($req['reason']) ? $req['reason'] : '',
                                'ticket_amount' => !empty($req['ticket_amount']) ? $req['ticket_amount'] : '',
                                'share_amount' => !empty($req['share_amount']) ? $req['share_amount'] : '',
                                'offer_amount_offer'     => !empty($req['offer_amount_offer']) ? $req['offer_amount_offer'] : '',
                                'divide_amount'     => !empty($req['divide_amount']) ? $req['divide_amount'] : '',
                                'join_date' => !empty($req['join_date']) ? $req['join_date'] : '',
                                'join_address' => !empty($req['join_address']) ? $req['join_address'] : '',
                                'trial_peroid' => !empty($req['trial_peroid']) ? $req['trial_peroid'] : '',
                                'attachement' => !empty($req['attachement']) ? $req['attachement'] : '',
                                'remarks' => !empty($req['remark']) ? $req['remark'] : '',
                            ];
                            break;
                    }
                }
            } else {
                $info = [
                    'reason'    => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
                ];
            }
        }
        if (!empty($info)) {
            $model->recommend_info = json_encode($info, JSON_UNESCAPED_UNICODE);
        } else {
            $model->recommend_info = '';
        }

        if (!empty($model->recommend_filter_detail)) {
            $model->recommend_filter = !empty(Recommend::$recommendFilterDetail[$model->recommend_filter_detail])
                ? Recommend::$recommendFilterDetail[$model->recommend_filter_detail]
                : $model->recommend_filter_detail;
        }
//        if (!empty($model->recommend_filter)) {
//            $info = [];
//            if ($model->recommend_filter == Recommend::RECOMMEND_FILTER_REFUSED) {
//                $model->recommend_status = Recommend::RECOMMEND_STATUS_FAILED;
//            }
//
//            if ($model->recommend_filter == Recommend::RECOMMEND_FILTER_COMPANY_FILTER
//                && $model->recommend_status == Recommend::RECOMMEND_STATUS_FAILED
//            ) {
//                $info = [
//                    'reason'    => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
//                ];
//
//            }
//
//            if ($model->recommend_filter == Recommend::RECOMMEND_FILTER_RECOMMEND
//                || $model->recommend_filter == Recommend::RECOMMEND_FILTER_READ
//                || $model->recommend_filter == Recommend::RECOMMEND_FILTER_COMPANY_FILTER
//                || $model->recommend_filter == Recommend::RECOMMEND_FILTER_TIMEOUT
//            ) {
////                $info = [
////                    'reason'    => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
////                ];
//            }
//
//            if ($model->recommend_filter == Recommend::RECOMMEND_FILTER_AUDITION_DETAIL) {
//                if ($model->recommend_status == Recommend::RECOMMEND_STATUS_PASS) {
//                    $info = [
//                        'reason' => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
//                        'audition_date' => !empty($req['audition_date']) ? $req['audition_date'] : '',
//                        'audition_address' => !empty($req['audition_address']) ? $req['audition_address'] : '',
//                    ];
//                }
//            }
//
//            if ($model->recommend_filter == Recommend::RECOMMEND_FILTER_AUDITION) {
//                if ($model->recommend_status == Recommend::RECOMMEND_STATUS_PASS) {
////                    $info = [
////                        'reason' => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
////                        'audition_date' => !empty($req['audition_date']) ? $req['audition_date'] : '',
////                        'audition_address' => !empty($req['audition_address']) ? $req['audition_address'] : '',
////                    ];
//                } elseif ($model->recommend_status == Recommend::RECOMMEND_STATUS_FAILED) {
//                    $info = [
//                        'refuse_audition_reason' => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
//                    ];
//                }
//            }
//
//            if ($model->recommend_filter == Recommend::RECOMMEND_FILTER_ACCEPT_OFFER) {
//                if ($model->recommend_status == Recommend::RECOMMEND_STATUS_PASS) {
//                    $info = [
//                        'reason' => !empty($req['reason']) ? $req['reason'] : '',
//                        'ticket_amount' => !empty($req['ticket_amount']) ? $req['ticket_amount'] : '',
//                        'share_amount' => !empty($req['share_amount']) ? $req['share_amount'] : '',
//                        'join_date' => !empty($req['join_date']) ? $req['join_date'] : '',
//                        'join_address' => !empty($req['join_address']) ? $req['join_address'] : '',
//                        'trial_peroid' => !empty($req['trial_peroid']) ? $req['trial_peroid'] : '',
//                        'attachement' => !empty($req['attachement']) ? $req['attachement'] : '',
//                        'remarks' => !empty($req['remark']) ? $req['remark'] : '',
//                    ];
//                } elseif ($model->recommend_status == Recommend::RECOMMEND_STATUS_FAILED) {
//                    $info = [
//                        'refuse_offer_reason' => !empty($req['refuse_offer_reason']) ? $req['refuse_offer_reason'] : '',
//                        'offer_amount' => !empty($req['offer_amount']) ? $req['offer_amount'] : '',
//                        'divide_amount' => !empty($req['divide_amount']) ? $req['divide_amount'] : '',
//                    ];
//                }
//            }
//
//            if ($model->recommend_filter == Recommend::RECOMMEND_FILTER_ENTRY) {
//                if ($model->recommend_status == Recommend::RECOMMEND_STATUS_PASS) {
//                    $info = [
//                        'reason' => !empty($req['refuse_audition_reason']) ? $req['refuse_audition_reason'] : '',
//                        'offer_amount_offer' => !empty($req['offer_amount_offer']) ? $req['offer_amount_offer'] : '',
//                        'divide_amount' => !empty($req['divide_amount']) ? $req['divide_amount'] : '',
//                    ];
//                } elseif ($model->recommend_status == Recommend::RECOMMEND_STATUS_FAILED) {
//                    $info = [
//                        'refuse_offer_reason' => !empty($req['refuse_offer_reason']) ? $req['refuse_offer_reason'] : '',
//                        'offer_amount' => !empty($req['offer_amount']) ? $req['offer_amount'] : '',
//                        'divide_amount' => !empty($req['divide_amount']) ? $req['divide_amount'] : '',
//                    ];
//                }
//            }
//
//            if (!empty($info)) {
//                $model->recommend_info = json_encode($info, JSON_UNESCAPED_UNICODE);
//            } else {
//                $model->recommend_info = '';
//            }
//        }
        return $model;
    }

    public function formatInterview(Documents $model) {
        $ret = $model;
        if (!empty($model->interview)) {
            $interviewJson = json_decode($model->interview, true);
            $ret->interview = $interviewJson;
        }
        return $ret;
    }

    // 暂时没用
    // job里
    // 主要是为了如果user_company和customer_company无法同时获取,只能先获取user_company_list再循环时候用
    public function showContract($job, $userCompanyList, $customerCompanyId, $itemName = '', $defaultValue = '') {
        if ($job->created_at < strtotime('2020-04-20') && in_array($itemName, ['pay_time'])) {
            if (!empty($job->$itemName)) {
                return $job->$itemName;
            } else {
                return $defaultValue;
            }
        }
        if (!empty($userCompanyList)) {
            foreach ($userCompanyList as $uc) {
                if ($uc->customer_company_id == $customerCompanyId
//                    && $uc->user_company_status == UserCompany::USER_COMPANY_STATUS_NORMAL
                ) {
                    if ( empty($itemName) ) {
                        return $uc;
                    } else {
                        if (!empty($uc->$itemName)) {
                            return $uc->$itemName;
                        } else {
                            return $defaultValue;
                        }
                    }
                }
            }
        }
        return $defaultValue;
    }

    public function showJobText($job, $itemName) {
        $content = ArrayHelper::getValue($job, $itemName, ' ');
        $content = nl2br($content);
        return $content;
    }

    public function showJobItem($job, $itemName, $itemModel = []) {
//        var_dump($job->$itemName);
        $showVal = ArrayHelper::getValue($job, $itemName, ' ');
        if (!empty($itemModel)) {
            $showVal = !empty($itemModel[$showVal]) ? $itemModel[$showVal] : '-';
        }
        if (!empty($job->$itemName)) {
            return '<span class="fs-14 text-99">' . $showVal . '</span>';
        } else {
            return '<label><span class="no-content-gray"></span><i class="iconfont iconicon-wentifankui fs-14 text-F6 d-inline-block vertical-mid" data-toggle="tooltip" data-placement="top" title="" data-original-title="此栏发单方未填写信息"></i></label>';
        }
    }

    public function showJobItemForDetail($job, $label = '', $itemName, $itemModel = []) {
        $ret = '';
        if (!empty($job->$itemName)) {
            $ret = '<div class="col-6 m-b-15">
                                        <div class="d-flex ">
                                            <div class="w-30">
                                                <p class="fs-14 text-6A medium "> ' . $label . ' ：</p>
                                            </div>
                                            <div class="w-70 text-99">' . $this->showJobItem($job, $itemName, $itemModel) . '
                                            </div>
                                        </div>
                                    </div>';
        }
        return $ret;
    }
    public function showJobItemForDetailStyle2($job, $label = '', $itemName, $itemModel = []) {
        $ret = '';
        if (!empty($job->$itemName)) {
            $ret = '<div class="col-12 m-b-15">
                                        <div class="d-flex ">
                                            <div class="w-15">
                                                <p class="fs-14 text-6A  medium"> ' . $label . ' :</p>
                                            </div>
                                            <div class="w-85 text-99">' . $this->showJobItem($job, $itemName, $itemModel) . '
                                            </div>
                                        </div>
                                    </div>';
        }
        return $ret;
    }
    public function showJobPreviewItem($job, $itemName, $label, $itemModel = [], $unit = '') {
        if (!empty($job->$itemName)) {
            $val = ArrayHelper::getValue($job, $itemName, '');
            if (!empty($itemModel)) {
                $val = $itemModel[$val];
            }
            $val .= $unit;
            $className = 'border-EA';
            $content = '<p class="fs-14 text-44">' . $val . '</p>';
        } else {
            $className = 'border-red';
            $content = '<span class="text-red">无（此项内容未填写）</span>';
        }

        return '<div class="bg-fa  p-5-20 m-t-10 ' . $className . '">
                                <div class="row ">
                                    <div class="col-2">
                                        <p class="fs-14 text-33 ">' . $label . '</p>
                                    </div>
                                    <div class="col-10 s-14 text-44">' . $content . '

</div>
</div>
</div>';
    }

    public function showCompanyMore($company, $colName, $category = 'COMPANY_INFORMATION') {
        if (!empty($company->more_information)) {
            $moreInformation = $company->more_information;
            $moreInfo = json_decode($moreInformation, true);
            if (!empty($moreInfo[$category][$colName])) {
                return $moreInfo[$category][$colName];
            }
        }
        return null;
    }

    public function showResumeLabel($val, $pos = 'middle', $after = '') {
        if (empty($val)) {
            return '';
        }
        $class = ($pos == 'first') ? ' m-l-20' : '';
        $tpl = '<label class="fs-16 m-r-20' . $class . ' text-33">' . $val . $after . '</label>';
        if ($pos != 'last') {
            $tpl .= '<span class="text-E9 fs-16">|</span>';
        }
        return $tpl;
    }

    public function getIdWithChildrenTree($model){
        $ret = [];
        if (!empty($model->children)) {
            foreach ($model->children as $child) {
                $ret[] = $child->id;
                $ret = array_merge($ret, $this->getIdWithChildrenTree($child));
            }
        }

        return $ret;
    }
}