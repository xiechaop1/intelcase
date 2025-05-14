<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/26
 * Time: 10:55 AM
 */

namespace backend\actions\member;

use common\models\Member;
use yii\base\Action;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use Yii;

class Edit extends Action
{
    public $memberId;

    public function run()
    {
        $member = Member::findOne($this->memberId);
        if (!$member) {
            throw new NotFoundHttpException();
        }

        $model = new \backend\forms\Member([
            'id' => $member->id,
            'username' => $member->username,
            'mobileSection' => $member->mobile_section,
            'mobile' => $member->mobile,
            'email' => $member->email
        ]);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            $model->load(Yii::$app->request->post());
            return ActiveForm::validate($model);
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

        return $this->controller->render('edit', [
            'model' => $model
        ]);
    }
}