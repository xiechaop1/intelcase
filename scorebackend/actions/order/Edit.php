<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/29
 * Time: 下午8:29
 */

namespace backend\actions\order;


use common\models\Order;
use common\models\Music;
use common\models\Singer;
use liyifei\base\helpers\Net;
use yii\base\Action;
use Yii;

class Edit extends Action
{
    public function run()
    {
        $id = Net::get('id');

        if ($id) {
            $model = \backend\models\Order::find()->where(['id' => $id]);
            $model->with('musicwithoutstatus');
            $model = $model->one();
            $isNew = false;
        } else {
            $model = new \backend\models\Order();
            $isNew = true;
        }

        if (Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->order_status == Order::ORDER_STATUS_LOCK) {
                    // 锁定音乐
                    $musicInfo = Music::findOne($model->music_id);
                    if ($musicInfo) {
                        $musicInfo->music_status = Music::MUSIC_STATUS_LOCK;
                        $musicInfo->save();
                    }
                } else if ($model->order_status == Order::ORDER_STATUS_COMPLETED) {
                    $musicInfo = Music::findOne($model->music_id);
                    if ($musicInfo) {
                        $musicInfo->music_status = Music::MUSIC_STATUS_BOUGHT;
                        $musicInfo->save();
                    }
                } else {
                    $musicInfo = Music::findOne($model->music_id);
                    if ($musicInfo) {
                        $musicInfo->music_status = Music::MUSIC_STATUS_NORMAL;
                        $musicInfo->save();
                    }
                }

                if ($model->save()) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '操作成功');
                } else {
                    $transaction->rollBack();
                    $errKey = key($model->getFirstErrors());
                    $error = current($model->getFirstErrors());

                    Yii::$app->session->setFlash('danger', "操作失败：[{$errKey}] {$error}");
                }

                return $this->controller->refresh();
            } else {
                Yii::$app->session->setFlash('danger', "操作失败:" . current($model->getFirstErrors()));
            }
            return $this->controller->refresh();
        }

        return $this->controller->render('edit', [
            'orderModel'    => $model,
            'orderStatus'   => Order::$orderStatus,
        ]);
    }
}