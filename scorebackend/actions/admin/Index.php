<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午4:33
 */

namespace backend\actions\admin;


use backend\actions\BackendAction;
use backend\models\Admin;
use yii\base\Action;
use yii;
use liyifei\base\helpers\Net;

class Index extends Action
{
    public function run()
    {
        $searchModel = new Admin();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            switch (Net::post('action')) {
                case 'delete':
                    $id = Net::post('id');
                    $article = Admin::findOne($id);
                    if ($article) {
                        $article->delete();
                    }
                    Yii::$app->session->setFlash('success', '删除成功');
                    break;
                default:
                    throw new yii\web\BadRequestHttpException();
            }

            return $this->controller->responseAjax(1, '');
        }

        return $this->controller->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
        ]);
    }
}