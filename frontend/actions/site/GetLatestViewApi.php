<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 4:17 PM
 */

namespace frontend\actions\site;


use liyifei\base\actions\ApiAction;
use common\models\View;
use yii;

class GetLatestViewApi extends ApiAction
{
    public function run()
    {
        $latestViewLimit = 5;
        $maxLimit = 500 * $latestViewLimit;

        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('limit', $latestViewLimit);

        $latestViewModel = View::find()
            ->where(['user_id' => Yii::$app->user->id, 'object_type' => View::OBJECT_TYPE_JOB]);

        $count = $latestViewModel->count() > $maxLimit ? $maxLimit : $latestViewModel->count();
        $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => $count, 'pageSize' => $limit, 'route' => 'javascript:void(0);']);
        $pagination->setPage($page - 1);


        $latestView = $latestViewModel

            ->orderBy(['updated_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
//            ->with('job')
            ->all();


        return $this->controller->renderPartial(
            'latest_view', [
                'latest_view'               => $latestView,
                'pagination'                => $pagination,
            ]
        );
    }

}