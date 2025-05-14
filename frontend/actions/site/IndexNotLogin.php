<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 4:17 PM
 */

namespace frontend\actions\site;


use common\helpers\Client;
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

class IndexNotLogin extends Action
{
    public function run()
    {
        $this->controller->layout = '@frontend/views/layouts/main_r.php';

        $code = Yii::$app->request->get('code', '');
        $type = Yii::$app->request->get('type', '');
//        $orderBy = Yii::$app->request->get('order_by');
        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('limit', 20);


        $page = !empty($page) ? $page : 1;

        if (Client::isMobile()) {
            header('location: /h5/job_list?code=' . $code . '&type=' . $type . '&page=' . $page);
        }


        $param = [
            'code'      => $code,
//            'order_by'  => $orderBy,
            'page'      => $page,
            'limit'     => $limit,
            'type'      => $type,
        ];

        if (!empty(Yii::$app->user->id)) {
            $param['user_id'] = Yii::$app->user->id;
        }

        $data = Yii::$app->hewaApi->getJobListByCode($param);

        if (!empty($data)) {
            $count = $data['data']['count'];
            $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => $count, 'route' => 'javascript:void(0);']);
            $pagination->setPage($page - 1);
        } else {
            $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => 0, 'route' => 'javascript:void(0);']);
        }


        $similarParam = [
            'job_id'    => 1,
            'user_id'   => 2,
        ];
        $similarJobList = Yii::$app->hewaApi->getSimilarJobList($similarParam);


        return $this->controller->render(
            'index_not_login', [
//                'type'                      => $type,
                'ct'                        => $count,
                'data'                      => !empty($data['data']['list']) ? $data['data']['list'] : [],
                'similar_job_list'          => !empty($similarJobList['data']['list']) ? $similarJobList['data']['list'] : [],
                'pagination'                => $pagination,
                'qr_code'                   => Common::createQrWithUrl(),
            ]
        );
    }


    public static function sortCity($a, $b)
    {
        if (ord($a->first_word) < ord($b->first_word)) {
            $ret = -1;
        } else if (ord($a->first_word) > ord($b->first_word)) {
            $ret = 1;
        } else {
            $ret = 0;
        }
//        var_dump($a->city_name);
//        var_dump($b->city_name);
//        var_dump($a->first_word);
//        var_dump($b->first_word);
//        var_dump($ret);
        return $ret;
    }
}