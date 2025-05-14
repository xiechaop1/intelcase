<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\video;


use common\definitions\Common;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;
use yii\helpers\ArrayHelper;

class Video extends Action
{

    public function run()
    {
        $videoId = Net::post('id');
        if ($videoId) {
            $model = \common\models\MusicVideo::findOne($videoId);
        } else {
            $model = new \common\models\MusicVideo();
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $video = \common\models\MusicVideo::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($video) {
                        if ($video->delete()) {
                            Yii::$app->session->setFlash('success', '操作成功');
                        }
                    }
                    break;
                case 'offline':
                    if ($video) {
                        $video->is_delete = Common::STATUS_ENABLE;
                        if ($video->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'reset':
                    if ($video) {
                        $video->is_delete = Common::STATUS_NORMAL;
                        if ($video->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                default:
                    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                    $model->load(Yii::$app->request->post());
                    return ActiveForm::validate($model);
            }

            return $this->controller->responseAjax(1, '');
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功');
            } else {
                Yii::$app->session->setFlash('danger', '操作失败');
            }
            return $this->controller->refresh();
        }

        $searchModel = new \backend\models\MusicVideo();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());



        return $this->controller->render('videolist', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'videoModel'    => $model,
            'params'        => $_GET,
        ]);
    }
}