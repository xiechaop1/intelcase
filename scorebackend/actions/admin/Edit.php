<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午4:58
 */

namespace backend\actions\admin;


use common\models\Admin;
use yii\base\Action;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use Yii;

class Edit extends Action
{
    public function run()
    {
        $model = new Admin();

        $adminId = Yii::$app->request->get('admin_id');

        if ($adminId) {
            $model = Admin::findOne($adminId);

            if (!$model) {
                throw new NotFoundHttpException();
            }
        } else {
            $model = new Admin();
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            $model->load(Yii::$app->request->post());
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->exec()) {
                Yii::$app->session->setFlash('success', '操作成功');
                return $this->controller->redirect('/admin');
            } else {
                Yii::$app->session->setFlash('danger', '操作失败');
                return $this->controller->refresh();
            }
        }

        if ($adminId) {
            $admin = Admin::findOne($adminId);

            if (!$admin) {
                throw new NotFoundHttpException();
            }
        } else {
            $admin = new Admin();
        }

        return $this->controller->render('edit', [
            'model' => $model,
            'admin' => $admin
        ]);
    }
}