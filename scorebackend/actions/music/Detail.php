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

class Detail extends Action
{
    public $musicType;

    public function run()
    {
        $id = Net::get('id');

        if ($id) {
            $model = \backend\models\Music::findOne($id);
        }

//        $model->category_ids = MusicCategory::find()
//            ->select('category_id')
//            ->where(['music_id' => $model->id])
//            ->column();
//
//        $singers = yii\helpers\ArrayHelper::map(Singer::find()->where([])->all(), 'id', 'singer_name');
//        $categories = yii\helpers\ArrayHelper::map(Category::find()->where([])->all(), 'id', 'category_name');
//
//        $musicTypes = Music::$musicType;
//
//        if (!empty($this->musicType)) {
//            $musicTypes = [
//                $this->musicType => Music::$musicType[$this->musicType]
//            ];
//        }

        return $this->controller->render('detail', [
            'musicModel'    => $model,
//            'singers'       => $singers,
//            'categories'    => $categories,
//            'musicTypes'    => $musicTypes,
        ]);
    }
}