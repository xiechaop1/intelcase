<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:49 PM
 */

namespace common\subscribes;


use common\models\Article;
use yii\helpers\ArrayHelper;

class Category
{

    public static function afterDelete($event)
    {
        /**
         * @var \common\models\Category $model
         */
        $model = $event->data['model'];
        if ($model->type == \common\definitions\Category::TYPE_ARTICLE) {
            if ($model->parent_id = 0) {
                $categories = $model->children;
            } else {
                $categories = [$model];
            }
            $categoryIds = ArrayHelper::getColumn($categories, 'id');
            if ($categoryIds) {
                Article::updateAll(['category_id' => 0], ['category_id' => $categoryIds]);
            }
        }
    }
}