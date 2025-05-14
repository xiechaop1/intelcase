<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\base;


use common\definitions\Common;
use common\models\Singer;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;

class Singers extends Action
{
    public function run()
    {
        $categoryId = Net::post('data-id');
        if ($categoryId) {
            $model = \backend\models\Singer::findOne($categoryId);
        } else {
            $model = new \backend\models\Singer();
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('data-id');
            $category = \common\models\Singer::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($category) {
                        $category->is_delete = Common::STATUS_DELETED;
                        if ($category->exec()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'reset':
                    if ($category) {
                        $category->is_delete = Common::STATUS_NORMAL;
                        if ($category->exec()) {

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

        $searchModel = new \backend\models\Singer();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

        $categoryArray = yii\helpers\ArrayHelper::map(\common\models\Category::find()->where(['is_delete' => Common::STATUS_NORMAL])->all(), 'id', 'category_name');

        return $this->controller->render('singers', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'singerModel'    => $model,
            'params'        => $_GET,
            'categoryArray' => $categoryArray
        ]);
    }
}