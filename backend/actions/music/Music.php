<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\music;


use common\definitions\Common;
use yii\base\Action;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;

use Yii;
use yii\helpers\ArrayHelper;

class Music extends Action
{

    public $musicType;

    public function run()
    {
        $musicId = Net::post('id');
        if ($musicId) {
            $model = \common\models\Music::findOne($musicId);
        } else {
            $model = new \common\models\Music();
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $music = \common\models\Music::findOne($id);
            switch (Net::post('action')) {
                case 'delete':
                    if ($music) {
                        $music->is_delete = Common::STATUS_ENABLE;
                        if ($music->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'reset':
                    if ($music) {
                        $music->is_delete = Common::STATUS_NORMAL;
                        if ($music->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
                    break;
                case 'sync_s':
                    if ($music) {
                        $music->music_type = \common\models\Music::MUSIC_TYPE_STATIC;
                        if ($music->save()) {

                        }
                    }
                    Yii::$app->session->setFlash('success', '操作成功');
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

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '操作成功');
            } else {
                Yii::$app->session->setFlash('danger', '操作失败');
            }
            return $this->controller->refresh();
        }

        $searchModel = new \backend\models\Music();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams(), $this->musicType);

        $categories = yii\helpers\ArrayHelper::map(\common\models\Category::find()->where([])->all(), 'id', 'category_name');
        $categories = array_merge(['0' => '全部'], $categories);

        $musicStatusList = [
            \common\models\Music::MUSIC_STATUS_ALL      => \common\models\Music::$musicStatus[\common\models\Music::MUSIC_STATUS_ALL],
            \common\models\Music::MUSIC_STATUS_NORMAL   => \common\models\Music::$musicStatus[\common\models\Music::MUSIC_STATUS_NORMAL],
            \common\models\Music::MUSIC_STATUS_LOCK     => \common\models\Music::$musicStatus[\common\models\Music::MUSIC_STATUS_LOCK],
        ];

        $musicTypeList = \common\models\Music::$musicNormalType;

        return $this->controller->render('musiclist', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'musicModel'    => $model,
            'params'        => $_GET,
            'categories'    => $categories,
            'musicStatusList' => $musicStatusList,
            'musicTypeList' => $musicTypeList,
            'musicType'     => $this->musicType,
        ]);
    }
}