<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/29
 * Time: 下午8:29
 */

namespace backend\actions\banner;


use common\models\Banner;
use common\definitions\Common;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;
use liyifei\chinese2pinyin\Chinese2pinyin;
use yii\base\Action;
use yii;
use common\services\Ocr;

class Edit extends Action
{
    public function run()
    {
        $bannerId = Net::get('data-id');
        if ($bannerId) {
            $model = \backend\models\Banner::findOne($bannerId);
        } else {
            $model = new \backend\models\Banner();
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $banner = \common\models\Banner::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($banner) {
                        if ($banner->delete()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                default:
                    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                    $posts = Yii::$app->request->post();

                    $posts['online_time'] = strtotime(Yii::$app->request->post('online_time_str'));
                    $posts['offline_time'] = strtotime(Yii::$app->request->post('offline_time_str'));

                    $model->load($posts);
                    return ActiveForm::validate($model);
            }

            return $this->controller->responseAjax(1, '');
        }

        if (Yii::$app->request->isPost) {
            $posts = Yii::$app->request->post();

            $posts['Banner']['online_time'] = strtotime($posts['Banner']['online_time_str']);
            $posts['Banner']['offline_time'] = strtotime($posts['Banner']['offline_time_str']);

            $model->load($posts);
            if ($model->exec()) {
                Yii::$app->session->setFlash('success', '操作成功');
            } else {
                $errKey = key($model->getFirstErrors());
                $error = current($model->getFirstErrors());

                Yii::$app->session->setFlash('danger', "操作失败：[{$errKey}] {$error}" );
            }
            return $this->controller->refresh();
        }

        return $this->controller->render('edit', [
//            'dataProvider' => $dataProvider,
            'bannerModel' => $model,
        ]);
    }
}