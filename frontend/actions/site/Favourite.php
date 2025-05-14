<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 4:17 PM
 */

namespace frontend\actions\site;


use common\helpers\Common;
use common\helpers\Cookie;
use common\models\Banner;
use common\models\Industry;
use common\models\Job;
use common\models\Messages;
use common\models\Tag;
use common\models\PostBasic;
use common\models\SearchHistory;
use common\models\View;
use common\models\City;
use common\models\Member;
use common\services\HewaApi;
use yii\base\Action;
use yii;

class Favourite extends Action
{
    public function run()
    {
        $this->controller->layout = '@frontend/views/layouts/main_r.php';

        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('limit', 20);
        $userId = !empty(Yii::$app->user->id) ? Yii::$app->user->id : 2;
        $searchContent = Yii::$app->request->get('search_content', '');

        $page = !empty($page) ? $page : 1;

        $param = [
            'user_id'   => $userId,
            'page'      => $page,
            'search_content' => $searchContent,
            'limit'     => $limit,
        ];

        $data = Yii::$app->hewaApi->getFavList($param);

        return $this->controller->render(
            'favorite', [
                'data'                      => $data['data']['list'],
//                'qr_code'                   => Common::createQrWithUrl(),
            ]
        );
    }

}