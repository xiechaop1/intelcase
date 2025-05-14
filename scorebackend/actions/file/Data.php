<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/29
 * Time: 下午8:29
 */

namespace backend\actions\data;


use backend\models\ConsultantCompany;
use common\definitions\Common;
use kartik\form\ActiveForm;
use yii\db\Query;
use liyifei\base\helpers\Net;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use Yii;

class Data extends Action
{
    public function run()
    {
        $dataType = Net::get('data_type');

        switch ($dataType) {
            case '1':
                $tpl = 'fadan';
                break;
            case '2':
                $tpl = 'jiedan';
                break;
            case '3':
                $tpl = 'tuijian';
                break;
            case '4':
                $tpl = 'offer';
                break;
        }


        $searchModel = new \backend\models\Data();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());

        return $this->controller->render($tpl, [
//            'data'  => $ret,
            'dataProvider' => $dataProvider,
//            'dataProvider' => $dataProvider,
//            'c' => $count,
            'searchModel' => $searchModel,
//            'companyModel' => $model
        ]);
    }
}