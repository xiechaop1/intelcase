<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\user;


use common\definitions\Common;
use common\models\User;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;

class Users extends Action
{
    public function run()
    {
        $userId = Net::post('data-id');
        if ($userId) {
            $model = \backend\models\User::findOne($userId);
            $isNew = false;
        }

        if (empty($model)) {
            $model = new \backend\models\User();
            $isNew = true;
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $user = \backend\models\User::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($user) {
                        $user->is_delete = Common::STATUS_DELETED;
                        if ($user->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'reset':
                    if ($user) {
                        $user->is_delete = Common::STATUS_NORMAL;
                        if ($user->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'forbidden':
                    if ($user) {
                        $user->user_status = User::USER_STATUS_FORBIDDEN;
                        if ($user->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'normal':
                    if ($user) {
                        $user->user_status = User::USER_STATUS_NORMAL;
                        if ($user->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                default:
                    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                    if ($isNew) {
                        $count = User::find()
                            ->where(['mobile' => $model->mobile])
                            ->andFilterWhere(['is_delete' => Common::STATUS_NORMAL])
                            ->count();
                        if ($count > 0) {
                            Yii::$app->session->setFlash('danger', '手机号已存在');
                            return $this->controller->responseAjax(1, '');
                        }

//                        $count = User::find()
//                            ->where(['user_name' => $model->user_name])
//                            ->andFilterWhere(['is_delete' => Common::STATUS_NORMAL])
//                            ->count();
//                        if ($count > 0) {
//                            Yii::$app->session->setFlash('danger', '用户已存在');
//                            return $this->controller->responseAjax(1, '');
//                        }
                    }
                    $model->load(Yii::$app->request->post());
                    return ActiveForm::validate($model);
            }

            return $this->controller->responseAjax(1, '');
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($isNew) {
                $count = User::find()
                    ->where(['mobile' => $model->mobile])
                    ->andFilterWhere(['is_delete' => Common::STATUS_NORMAL])
                    ->count();
                if ($count > 0) {
                    Yii::$app->session->setFlash('danger', '手机号已存在');
                    return $this->controller->refresh();
                }

//                $count = User::find()
//                    ->where(['user_name' => $model->user_name])
//                    ->andFilterWhere(['is_delete' => Common::STATUS_NORMAL])
//                    ->count();
//                if ($count > 0) {
//                    Yii::$app->session->setFlash('danger', '用户已存在');
//                    return $this->controller->refresh();
//                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功');
            } else {
                Yii::$app->session->setFlash('danger', '操作失败');
            }
            return $this->controller->refresh();
        }

        $searchModel = new \backend\models\User();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

        return $this->controller->render('users', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'userModel'    => $model,
            'params'        => $_GET,
        ]);
    }
}