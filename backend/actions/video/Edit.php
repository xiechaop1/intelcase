<?php
/**
 * Created by PhpStorm.
 * User: xiechao's group
 * Date: 2023/5/29
 * Time: 下午8:29
 */

namespace backend\actions\video;


use common\definitions\Common;
use common\helpers\Attachment;
use common\helpers\Time;
use common\models\Category;
use common\models\Image;
use common\models\MusicVideo;
use common\models\MusicVideoCategory;
use common\models\Singer;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;
use yii\base\Action;
use Yii;

class Edit extends Action
{

    public function run()
    {
        $id = Net::get('id');

        if ($id) {
            $model = \backend\models\MusicVideo::findOne($id);
            $isNew = false;
        } else {
            $model = new \backend\models\MusicVideo();
            $isNew = true;
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $videoModel = \backend\models\MusicVideo::findOne($id);

            switch (Net::post('action')) {
                case 'delete':
                    if ($videoModel) {
                        $videoModel->is_delete = MusicVideo::VIDEO_IS_DELETE_YES;
                        if ($videoModel->save()) {

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

            if ($model->validate()) {

                if (strpos($model->video_url, ',') !== false) {
                    $videoUrls = explode(',', $model->video_url);

                    $videoInfos = [];
                    foreach ($videoUrls as $videoUrl) {
                        $fileinfo = pathinfo($videoUrl);
                        $videoInfo['url'] = $videoUrl;
                        $videoInfo['filename'] = $fileinfo['filename'];
                        $videoInfo['file_type'] = $fileinfo['extension'];
                        $videoInfos[$videoUrl] = $videoInfo;
                    }
                    $model->video_info = json_encode($videoInfos, JSON_UNESCAPED_UNICODE);

                } else {

                    $fileinfo = pathinfo($model->video_url);
                    $model->filename = $fileinfo['filename'];
                    $model->file_type = $fileinfo['extension'];
                }

                if ($model->save()) {


                    Yii::$app->session->setFlash('success', '操作成功');
                } else {
                    $errKey = key($model->getFirstErrors());
                    $error = current($model->getFirstErrors());

                    Yii::$app->session->setFlash('danger', "操作失败：[{$errKey}] {$error}");
                }
            } else {
                Yii::$app->session->setFlash('danger', "操作失败:" . current($model->getFirstErrors()));
            }
            return $this->controller->refresh();
        }


        return $this->controller->render('edit', [
            'videoModel'    => $model,
        ]);
    }
}