<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/29
 * Time: 下午8:29
 */

namespace scorebackend\actions\data;


use backend\models\ConsultantCompany;
use common\definitions\Common;
use kartik\form\ActiveForm;
use yii\db\Query;
use liyifei\base\helpers\Net;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use Yii;

class Group extends Action
{
    public function run()
    {
        exit;
        $dataType = Net::get('data_type');
        $tpl = 'group';

//        $searchModel = new \backend\models\Data();
//        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

        return $this->controller->render($tpl, [
//            'data'  => $ret,
//            'dataProvider' => $dataProvider,
//            'dataProvider' => $dataProvider,
//            'c' => $count,
//            'searchModel' => $searchModel,
//            'companyModel' => $model
        ]);
    }
}