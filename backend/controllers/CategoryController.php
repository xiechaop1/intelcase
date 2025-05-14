<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:11 PM
 */

namespace backend\controllers;


use common\definitions\Category;
use liyifei\base\controllers\ApiController;
use liyifei\base\controllers\ViewController;
use liyifei\base\helpers\Net;
use yii;

class CategoryController extends ViewController
{

    public function actions()
    {
        return yii\helpers\ArrayHelper::merge(parent::actions(), [
            'categories' => [
                'class' => 'backend\actions\category\Categories',
                'type' => Net::get('type', Category::TYPE_ARTICLE)
            ],
            'category' => [
                'class' => 'backend\actions\category\Category',
                'categoryId' => Net::get('category_id')
            ],
            'edit' => [
                'class' => 'backend\actions\category\Edit',
                'type' => Net::get('type')
            ],
            'delete' => [
                'class' => 'backend\actions\category\Delete',
                'categoryId' => Net::get('category_id')
            ],
        ]);
    }
}