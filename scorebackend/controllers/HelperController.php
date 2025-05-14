<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/11
 * Time: 下午10:35
 */

namespace backend\controllers;


use liyifei\base\controllers\ViewController;
use yii;

class HelperController extends ViewController
{
    public function actions()
    {
        return yii\helpers\ArrayHelper::merge(parent::actions(), [
            'issue-categories' => 'backend\actions\helper\IssueCategories',
            'issue-questions' => 'backend\actions\helper\IssueQuestions'
        ]);
    }
}