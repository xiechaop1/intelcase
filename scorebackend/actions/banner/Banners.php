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

class Banners extends Action
{
    public function run()
    {
        $bannerId = Net::get('data-id');
        if ($bannerId) {
            $model = \common\models\Banner::findOne($bannerId);
        } else {
            $model = new \common\models\Banner();
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $banner = \common\models\Banner::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($banner) {
                        if ($banner->delete()) {
                            Yii::$app->session->setFlash('success', '操作成功');
                        } else {
                            Yii::$app->session->setFlash('danger', '操作失败');
                        }
                    } else {
                        Yii::$app->session->setFlash('danger', '操作失败');
                    }
                    break;
                case 'change_status':
                    if ($banner) {
                        if (!empty(Net::post('banner_status'))) {
                            $banner->banner_status = Net::post('banner_status');
                            $banner->save();
                        }
                        Yii::$app->session->setFlash('success', '操作成功');
                    } else {
                        Yii::$app->session->setFlash('danger', '操作失败');
                    }
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
            if ($model->exec()) {
                Yii::$app->session->setFlash('success', '操作成功');
            } else {
                $errKey = key($model->getFirstErrors());
                $error = current($model->getFirstErrors());

                Yii::$app->session->setFlash('danger', "操作失败：[{$errKey}] {$error}" );
            }
            return $this->controller->refresh();
        }

        $query = Banner::find();

        $dataProvider = new yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        $dataProvider->setSort(false);

        return $this->controller->render('banner', [
            'dataProvider' => $dataProvider,
            'bannerModel' => $model,
        ]);
    }
}