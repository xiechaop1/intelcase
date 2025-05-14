<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\base;


use common\definitions\Common;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;

class Categories extends Action
{
    public function run()
    {
        $categoryId = Net::post('id');
        if ($categoryId) {
            $model = \backend\models\Category::findOne($categoryId);
        } else {
            $model = new \backend\models\Category();
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $category = \common\models\Category::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($category) {
                        $category->is_delete = Common::STATUS_DELETED;
                        if ($category->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'reset':
                    if ($category) {
                        $category->is_delete = Common::STATUS_NORMAL;
                        if ($category->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                default:
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
                Yii::$app->session->setFlash('danger', '操作失败');
            }
            return $this->controller->refresh();
        }

        $searchModel = new \backend\models\Category();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

        return $this->controller->render('categories', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'categoryModel'    => $model,
            'params'        => $_GET,
        ]);
    }
}