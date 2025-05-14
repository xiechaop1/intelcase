<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\base;


use common\definitions\Common;
use common\models\Music;
use common\models\Singer;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;

class SingerEdit extends Action
{
    public function run()
    {
        $id = Net::get('id');

        if ($id) {
            $model = \common\models\Singer::findOne($id);
            $isNew = false;
        } else {
            $model = new \common\models\Singer();
            $isNew = true;
        }

        if (Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());

            if ($model->validate()) {

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '操作成功');
                } else {
                    $errKey = key($model->getFirstErrors());
                    $error = current($model->getFirstErrors());

                    Yii::$app->session->setFlash('danger', "操作失败：[{$errKey}] {$error}");
                }

                return $this->controller->refresh();
            } else {
                Yii::$app->session->setFlash('danger', "操作失败:" . current($model->getFirstErrors()));
            }
            return $this->controller->refresh();
        }

        $categories = yii\helpers\ArrayHelper::map(\common\models\Category::find()->where(['is_delete' => Common::STATUS_NORMAL])->all(), 'id', 'category_name');


        return $this->controller->render('singer_edit', [
            'singerModel'   => $model,
            'categories'    => $categories,
        ]);
    }
}