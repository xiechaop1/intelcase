<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\log;


use common\definitions\Common;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;
use yii\helpers\ArrayHelper;

class Logs extends Action
{
    public function run()
    {
//        $musicId = Net::post('id');
//        if ($musicId) {
//            $model = \common\models\Music::findOne($musicId);
//        } else {
//            $model = new \common\models\Music();
//        }
//
//        if (Yii::$app->request->isAjax) {
//            $id = Net::post('id');
//            $music = \common\models\Music::findOne($id);
//            switch (Net::post('action')) {
//                case 'delete':
//                    if ($music) {
//                        $music->is_delete = Common::STATUS_DELETED;
//                        if ($music->save()) {
//
//                        }
//                    }
//                    Yii::$app->session->setFlash('success', '操作成功');
//                    break;
//                case 'reset':
//                    if ($music) {
//                        $music->is_delete = Common::STATUS_NORMAL;
//                        if ($music->save()) {
//
//                        }
//                    }
//                    Yii::$app->session->setFlash('success', '操作成功');
//                    break;
//                default:
//                    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
//                    $model->load(Yii::$app->request->post());
//                    return ActiveForm::validate($model);
//            }
//
//            return $this->controller->responseAjax(1, '');
//        }
//
//        if (Yii::$app->request->isPost) {
//            $model->load(Yii::$app->request->post());
//
//            if ($model->save()) {
//                Yii::$app->session->setFlash('success', '操作成功');
//            } else {
//                Yii::$app->session->setFlash('danger', '操作失败');
//            }
//            return $this->controller->refresh();
//        }

        $searchModel = new \backend\models\Log();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

//        $categories = yii\helpers\ArrayHelper::map(Category::find()->where([])->all(), 'id', 'category_name');

        return $this->controller->render('logs', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
//            'logModel'    => $model,
            'params'        => $_GET,
//            'categories'    => $categories,
        ]);
    }
}