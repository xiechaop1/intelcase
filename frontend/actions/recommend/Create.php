<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/11/02
 * Time: 2:29 PM
 */

namespace frontend\actions\recommend;


use common\models\Documents;
use common\models\Orders;
use common\models\Recommend;
use common\models\RecommendHistory;
use liyifei\base\actions\ApiAction;
use common\models\View;
use yii\web\BadRequestHttpException;
use yii;

class Create extends ApiAction
{

    public function run()
    {

        $get = \Yii::$app->request->get();

        $get['post_id'] = !empty($get['job_id']) ? $get['job_id'] : 0;

        $order = Orders::findOne([
            'user_id'       => Yii::$app->user->id,
            'post_id'       => $get['post_id']
        ]);


        $transaction = Yii::$app->db->beginTransaction();
        if (!empty($get['document_ids'])) {
            if (!is_array($get['document_ids'])) {
                $documentIds = [$get['document_ids']];
            } else {
                $documentIds = $get['document_ids'];
            }
            try {
                foreach ($documentIds as $documentId) {

                    $documentModel = Documents::findOne($documentId);

                    if ($documentModel->push_status == Documents::DOCUMENT_RECOMMENDED) {
                        $transaction->rollBack();

                        return $this->fail('已经完成过推荐');
                    }


                    $recommendModel = Recommend::findOne([
                        'user_id'       => Yii::$app->user->id,
                        'document_id'   => $documentId,
                        'post_id'       => $get['job_id'],
                        'order_id'      => !empty($order->id) ? $order->id : 0,
                    ]);
                    if (empty($recommendModel)) {
                        $recommendModel = new Recommend();
                    }

                    $recommendModel->load($get, '');
                    $recommendModel->user_id = Yii::$app->user->id;
                    $recommendModel->post_id = $get['job_id'];
                    $recommendModel->document_id = $documentId;
                    $recommendModel->order_id = !empty($order->id) ? $order->id : 0;
                    $recommendModel->recommend_filter = Recommend::RECOMMEND_FILTER_CREATED;
                    $recommendModel->recommend_filter_detail = Recommend::RECOMMEND_FILTER_CREATED;
                    $recommendModel->recommend_status = Recommend::RECOMMEND_STATUS_PASS;

                    if (!empty($documentModel)) {
                        $documentModel->push_status = Documents::DOCUMENT_RECOMMENDED;
                        $documentModel->save();
                    }

                    $recommendRet = $recommendModel->save();

                    if (!empty($order->id)) {
                        $order->order_status = Orders::ORDER_STATUS_RECOMMEND;
                        $order->save();
                    }

                    if ($recommendRet) {
                        $recommendHistoryModel = new RecommendHistory();
                        $recommendHistoryModel->load($get, '');
//                        $recommendHistoryModel->user_id = Yii::$app->user->id;
                        $recommendHistoryModel->recommend_id = $recommendModel->id;
                        $recommendHistoryModel->recommend_filter = Recommend::RECOMMEND_FILTER_CREATED;
                        $recommendHistoryModel->recommend_filter_detail = Recommend::RECOMMEND_FILTER_CREATED;
                        $recommendHistoryModel->recommend_status = Recommend::RECOMMEND_STATUS_PASS;
                        $recommendHistoryRet = $recommendHistoryModel->save();
                    } else {
                        $transaction->rollBack();

                        return $this->fail('推荐保存错误');
                    }

                    // 记录view
                    Yii::$app->pageview->addNew(
                        View::OBJECT_TYPE_CONSULTANT,
                        $documentId
                    );

                    $job = $order->job;
                    $document = Documents::findOne($documentId);
                    $jobUser = !empty($job->user) ? $job->user : [];
                    $params = [
                        Yii::$app->user->identity->true_name,
                        $document->uname,
                        $job->job_name,
                    ];
                    Yii::$app->notify->send('jobBeRecommend', $jobUser, $params);
                }

                $transaction->commit();

                return $this->success('创建成功！');

            } catch (yii\base\Exception $e) {

                $transaction->rollBack();

                throw $e;
            }
        }



    }
}