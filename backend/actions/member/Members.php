<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:51 PM
 */

namespace backend\actions\member;


use backend\models\Member;
use liyifei\base\actions\ApiAction;
use Yii;

class Members extends ApiAction
{
    public function run()
    {
//        $searchModel = new Member();

//        $dataProvider = $searchModel->searchList(Yii::$app->request->queryParams);
        $searchModel = new Member();
        $dataProvider = $searchModel->searchList(\Yii::$app->request->getQueryParams());

        return $this->controller->render('members', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
}