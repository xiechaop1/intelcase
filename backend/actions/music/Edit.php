<?php
/**
 * Created by PhpStorm.
 * User: xiechao's group
 * Date: 2023/5/29
 * Time: 下午8:29
 */

namespace backend\actions\music;


use common\definitions\Common;
use common\helpers\Attachment;
use common\helpers\Time;
use common\models\Category;
use common\models\Image;
use common\models\Music;
use common\models\MusicCategory;
use common\models\Singer;
use kartik\form\ActiveForm;
use liyifei\base\helpers\Net;
use yii\base\Action;
use Yii;

class Edit extends Action
{
    public $musicType;

    public function run()
    {
        $id = Net::get('id');

        if ($id) {
            $model = \backend\models\Music::findOne($id);
            $isNew = false;
        } else {
            $model = new \backend\models\Music();
            $isNew = true;
        }

        if (Yii::$app->request->isAjax) {
            $id = Net::post('id');
            $musicModel = \backend\models\Music::findOne($id);

            switch (Net::post('action')) {
                case 'delete':
                    if ($musicModel) {
                        $musicModel->is_delete = Music::MUSIC_IS_DELETE_YES;
                        if ($musicModel->save()) {

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

            if ($model->validate()) {
                $model->cover_thumbnail = $model->cover_image;
                if (!empty($model->lyric_url)
                    && empty($model->lyric)
                ) {
                    $model->lyric = file_get_contents(Attachment::completeUrl($model->lyric_url, false));
                }

//                if (!empty($model->verse_url)) {
//                    $fileName = basename($model->verse_url);
//                    $fcontent = file_get_contents(Attachment::completeUrl($model->verse_url, false));
//                    $tfile = Yii::$app->basePath.'/../musictmp/' . $fileName;
//                    $f2 = fopen( $tfile,'c+');
//                    fwrite($f2,$fcontent,strlen($fcontent));
//                    fclose($f2);
//                    var_dump(Yii::$app->getid3->get($tfile));
//                    exit;
//                }

                if ($model->save()) {

                    if (!empty($model->category_ids)) {
                        MusicCategory::deleteAll(['music_id' => $model->id]);
                        foreach ($model->category_ids as $categoryId) {
                            $musicCategory = new MusicCategory();
                            $musicCategory->music_id = $model->id;
                            $musicCategory->category_id = $categoryId;
                            $musicCategory->save();
                        }
                    }

                    Yii::$app->session->setFlash('success', '操作成功');
                } else {
                    $errKey = key($model->getFirstErrors());
                    $error = current($model->getFirstErrors());

                    Yii::$app->session->setFlash('danger', "操作失败：[{$errKey}] {$error}");
                }
            } else {
                Yii::$app->session->setFlash('danger', "操作失败:" . current($model->getFirstErrors()));
            }
            return $this->controller->refresh();
        }

        $model->category_ids = MusicCategory::find()
            ->select('category_id')
            ->where(['music_id' => $model->id])
            ->column();

        $singers = yii\helpers\ArrayHelper::map(Singer::find()->where([])->all(), 'id', 'singer_name');
        $categories = yii\helpers\ArrayHelper::map(Category::find()->where([])->all(), 'id', 'category_name');

        $musicTypes = Music::$musicType;

        if (!empty($this->musicType)) {
            if ($this->musicType == Music::MUSIC_TYPE_NORMAL) {
                $musicTypes = Music::$musicNormalType;
            } else {

                $musicTypes = [
                    $this->musicType => Music::$musicType[$this->musicType]
                ];
            }
        }

        return $this->controller->render('edit', [
            'musicModel'    => $model,
            'singers'       => $singers,
            'categories'    => $categories,
            'musicTypes'    => $musicTypes,
        ]);
    }
}