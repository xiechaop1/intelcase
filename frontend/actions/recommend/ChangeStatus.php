<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/11/02
 * Time: 2:29 PM
 */

namespace frontend\actions\recommend;


use common\models\Recommend;
use common\models\Orders;
use common\models\View;
use common\models\RecommendHistory;
use liyifei\base\actions\ApiAction;
use yii\web\BadRequestHttpException;
use yii;

class ChangeStatus extends ApiAction
{

    public function run()
    {

        $getData = \Yii::$app->request->get();

        $transaction = Yii::$app->db->beginTransaction();
        if (!empty($getData['recommend_ids'])) {
            try {
                $recommendIdsParams = $getData['recommend_ids'];

                if (!is_array($recommendIdsParams)) {
                    $recommendIds = [$recommendIdsParams];
                } else {
                    $recommendIds = $recommendIdsParams;
                }
                foreach ($recommendIds as $recommendId) {
                    $model = Recommend::findOne([
                        'id' => $recommendId,
//                        'user_id' => Yii::$app->user->id,
//                    'recommend_status' => $getData['recommend_status'],
                    ]);

                    if (empty($model)) {
                        $model = new Recommend();
                    }

                    $latestHistoryFilter = 0;
                    foreach (Recommend::$recommendFilterAll2Name as $filterSet => $titleSet) {
                        if ($model->recommend_filter_detail >= $filterSet) {
                            continue;
                        }
                        if ($getData['recommend_filter_detail'] <= $filterSet) {
                            break;
                        }

                        // 如果还没有进入流程，则强制变成已阅
                        $historyTempModel = new RecommendHistory();
                        $historyTempModel->recommend_filter = $filterSet;
                        $historyTempModel->recommend_filter_detail = $filterSet;
                        $historyTempModel->recommend_id = $recommendId;
                        $historyTempModel->recommend_status = Recommend::RECOMMEND_STATUS_PASS;
                        $historyTempModel->recommend_date = Date('Y-m-d H:i:s');
                        $tempRet = $historyTempModel->save();
                    }

                    // 前端逻辑已经修改，如果拒绝变成通过会先变成通过，再进行下一步
                    // 如果是通过状态，判断上一个是不是拒绝，如果是，用新状态增加覆盖
//                    if ($getData['recommend_status'] == Recommend::RECOMMEND_STATUS_PASS) {
//                        $latestHistory = RecommendHistory::find()
//                            ->where([
//                                'recommend_id'              => $recommendId,
//                            ])
//                            ->orderBy(['id' => SORT_DESC])
//                            ->one();
//
//                        if ($latestHistory->recommend_status == Recommend::RECOMMEND_STATUS_FAILED
//                            && $latestHistory->recommend_filter_detail != $getData['recommend_filter_detail']
//                        ) {
//                            // 如果还没有进入流程，则强制变成已阅
//                            $historyTempModel = new RecommendHistory();
//                            $historyTempModel->recommend_filter = $latestHistory->recommend_filter;
//                            $historyTempModel->recommend_filter_detail = $latestHistory->recommend_filter_detail;
//                            $historyTempModel->recommend_id = $recommendId;
//                            $historyTempModel->recommend_status = Recommend::RECOMMEND_STATUS_PASS;
//                            $historyTempModel->recommend_date = Date('Y-m-d H:i:s');
//                            $tempRet = $historyTempModel->save();
//                        }
//
//                    }




//                    if (
//                        $model->recommend_filter_detail == Recommend::RECOMMEND_FILTER_CREATED
//                        && $getData['recommend_filter_detail'] != Recommend::RECOMMEND_FILTER_READ
//                    ) {
//                        // 如果还没有进入流程，则强制变成已阅
//                        $historyTempModel = new RecommendHistory();
//                        $historyTempModel->recommend_filter = Recommend::RECOMMEND_FILTER_READ;
//                        $historyTempModel->recommend_filter_detail = Recommend::RECOMMEND_FILTER_READ;
//                        $historyTempModel->recommend_id = $recommendId;
//                        $historyTempModel->recommend_status = Recommend::RECOMMEND_STATUS_PASS;
//                        $historyTempModel->recommend_date = Date('Y-m-d H:i:s');
//                        $tempRet = $historyTempModel->save();
//
//                    }

//                    if ($model->recommend_fitler_detail == Recommend::RECOMMEND_FILTER_CREATED) {
//                        // 第一次提交，更新order的RECOMMEND状态
//                        if (!empty($model->order_id)) {
//                            $orderTempModel = Orders::findOne($model->order_id);
//                            $orderTempModel->order_status =
//                        }
//                    }

                    $model->load($getData, '');

                    $model = Yii::$app->common->formatRecommend($model, $getData);




//                    $historyOldModel = RecommendHistory::findOne([
//                        'recommend_id'              => $recommendId,
//                        'recommend_filter_detail'   => $model->recommend_filter_detail,
////                        'recommend_status'          => $model->recommend_status,
//                    ]);

                    if (!empty($historyOldModel) && !empty($getData['recommend_status'])) {
                        $historyOldModel->recommend_status = $getData['recommend_status'];
                        $historyOldModel->save();
                    }

//                    if (empty($historyModel)) {
                        $historyModel = new RecommendHistory();
//                        $historyModel->user_id = Yii::$app->user->id;
                        $historyModel->recommend_filter = $model->recommend_filter;
                        $historyModel->recommend_filter_detail = $model->recommend_filter_detail;
//                    }

                    $historyModel->recommend_id = $model->id;
                    $historyModel->recommend_status = $model->recommend_status;
                    $historyModel->recommend_info = $model->recommend_info;
                    $historyModel->recommend_date = Date('Y-m-d H:i:s');

                    $recommendRet = $model->exec();
                    $historyModel->exec();

                    if ($recommendRet) {
                        $receiver = $model->member;

                        if ($model->recommend_filter_detail == $model->recommend_filter) {
                            // 临时方案：只有详细阶段和主阶段一致才发消息
                            // 也就是面试填完消息和接Offer填完消息是不发消息的

//                            switch ($model->recommend_filter_detail) {
//                            case Recommend::RECOMMEND_FILTER_AUDITION_DETAIL:
//                                $params = [
//                                    'jobName'       => !empty($model->job->job_name) ? $model->job->job_name : '',
//                                    'documentName'  => !empty($model->document->uname) ? $model->document->uname : '',
//                                    'statusName'    =>  Recommend::$recommendFilterAll2Name[$model->recommend_filter],
//                                    'time'          => !empty($model->recommend_info['audition_date']) ? $model->recommend_info['audition_date'] : '',
//                                    'address'       => !empty($model->recommend_info['audition_address']) ? $model->recommend_info['audition_address'] : '',
//                                ];
//                                $action = 'changeStatusWithFace';
//                                break;
//                                default:
//                                    break;
//                            }

                            $params = [
                                'jobName' => !empty($model->job->job_name) ? $model->job->job_name : '',
                                'documentName' => !empty($model->document->uname) ? $model->document->uname : '',
                                'statusName' => Recommend::$recommendFilterAll2Name[$model->recommend_filter],
                            ];
                            $action = 'changeStatus';
                            Yii::$app->notify->send($action, $receiver, $params);
                        }

                        // 新发短信
                        if ($model->recommend_status == Recommend::RECOMMEND_STATUS_PASS) {
                            switch ($model->recommend_filter_detail) {
                                case Recommend::RECOMMEND_FILTER_ACCEPT_OFFER_DETAIL:
                                    $action = 'orderCandidateOfferSucc';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
                                case Recommend::RECOMMEND_FILTER_ENTRY:
                                    $action = 'orderEnterSucc';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
//                                case Recommend::RECOMMEND_FILTER_TIMEOUT:
//                                    $action = 'orderEnterSucc';
//                                    $params = [
//                                        'true_name' => $model->document->uname,
//                                    ];
//                                    break;
                                case Recommend::RECOMMEND_FILTER_ACCEPT_OFFER:
                                    $action = 'orderOfferInfoSucc';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
                                case Recommend::RECOMMEND_FILTER_TIMEOUT:
                                    $action = 'orderOutOfDateSucc';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
                            }
                        } elseif ($model->recommend_status == Recommend::RECOMMEND_STATUS_FAILED) {
                            switch ($model->recommend_filter_detail) {
                                case Recommend::RECOMMEND_FILTER_ACCEPT_OFFER_DETAIL:
                                    $action = 'orderCandidateOfferFail';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
                                case Recommend::RECOMMEND_FILTER_ENTRY:
                                    $action = 'orderEnterFail';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
                                case Recommend::RECOMMEND_FILTER_AUDITION_DETAIL:
                                    $action = 'orderOfferInfoFail';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
                                case Recommend::RECOMMEND_FILTER_TIMEOUT:
                                    $action = 'orderOutOfDateFail';
                                    $params = [
                                        'true_name' => $model->document->uname,
                                    ];
                                    break;
                            }
                        }

                        if (!empty($action)) {
                            $ret = Yii::$app->notify->send($action, $receiver, $params);
                        }

                        Yii::$app->user_recommend_count->addUserRecommendCount(
                            Yii::$app->user->id,
                            $model->post_id,
                            $model->recommend_filter
                        );

                        // 记录view
                        Yii::$app->pageview->addNew(
                            View::OBJECT_TYPE_CONSULTANT,
                            $model->document
                        );

                        // 记录Order view
                        Yii::$app->pageview->addNew(
                            View::OBJECT_TYPE_PROGRESS,
                            $model->order_id
                        );


                    }
//                    else {
//                        var_dump($model->getFirstErrors());
//                    }
                }

                $transaction->commit();

                return $this->success('修改成功！');

            } catch (yii\base\Exception $e) {

                $transaction->rollBack();

                return $this->fail('修改失败：'. $e->getMessage());
//                throw $e;
            }
        }



    }
}