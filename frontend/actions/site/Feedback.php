<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/12
 * Time: 10:17 AM
 */

namespace frontend\actions\site;


use liyifei\base\actions\ApiAction;
use yii;

class Feedback extends ApiAction
{
    public function run()
    {
        $this->controller->layout = '@frontend/views/layouts/main_r.php';

        $model = new \common\models\Feedback();

        if (Yii::$app->request->isPost) {
            $model->load($_POST);

            if (!$model->save()) {
                Yii::warning('Save feedback fail');
                Yii::warning(json_encode($model->errors));
            }

            if (Yii::$app->request->isAjax) {
                return $this->success();
            } else {
                return $this->controller->refresh();
            }
        }

        return $this->controller->render('feedback', [
            'model' => $model
        ]);
    }
}