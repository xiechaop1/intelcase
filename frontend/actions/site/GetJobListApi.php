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
use common\models\City;
use common\models\Industry;
use common\models\Job;
use common\models\PostBasic;
use common\models\Tag;
use common\models\View;
use liyifei\base\actions\ApiAction;
use common\models\SearchHistory;
use yii\base\Action;
use yii\db\Query;
use yii;

class GetJobListApi extends ApiAction
{
    public function run()
    {

        $type = Yii::$app->request->get('type');
        $orderBy = Yii::$app->request->get('order_by');
        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('limit', 20);

        $page = !empty($page) ? $page : 1;
        switch ($type) {
            case 'recommend':
                $param = [
                    'user_id'   => Yii::$app->user->id,
                    'order_by'  => $orderBy,
                    'page'      => $page,
                    'limit'     => $limit,
                ];
                $data = Yii::$app->hewaApi->getJobListByRecommend($param);
                break;
            case 'hot':
                $param = [
                    'user_id'   => Yii::$app->user->id,
                    'order_by'  => $orderBy,
                    'page'      => $page,
                    'limit'     => $limit,
                ];
                $data = Yii::$app->hewaApi->getJobListByHot($param);
                Cookie::setCookie('hasViewedHot', 1, 86400 * 365);
                break;
            case 'search':
            default:
                $param = [
                    'user_id'   => Yii::$app->user->id,
                    'search_type'   => Yii::$app->request->get('search_type'),
                    'search_content'   => Yii::$app->request->get('search_content'),
                    'city'   => Yii::$app->request->get('city'),
                    'industry'   => Yii::$app->request->get('industry'),
                    'post'   => Yii::$app->request->get('post'),
                    'day'   => Yii::$app->request->get('day'),
                    'salary'   => Yii::$app->request->get('salary'),
                    'order_by'  => $orderBy,
                    'page'      => $page,
                    'limit'     => $limit,
                ];
                $data = Yii::$app->hewaApi->getJobListBySearch($param);
                break;
        }

        $filter = Yii::$app->request->get();
        if (!empty($filter['search_content'])) {
            $keywords = $filter['search_content'];
//            $keywordsType = !empty($filter['search_type']) ? $filter['search_type'] : 'all';

            // save search history
            $history = SearchHistory::findOne([
                'title'     => $keywords,
                'type'      => SearchHistory::SEARCH_HISTORY_TYPE_KEYWORD,
                'user_id'   => Yii::$app->user->id,
            ]);

            if (!$history) {
                $history = new SearchHistory([
                    'title'     => $keywords,
                    'type'      => SearchHistory::SEARCH_HISTORY_TYPE_KEYWORD,
                    'user_id'   => Yii::$app->user->id,
                    'times'     => 0,
                ]);
            }

//            var_dump($history);
            $history->times += 1;
            $history->save();
        }

//        if (!empty($data)) {
            $count = !empty($data['data']['count']) ? $data['data']['count'] : 0;
            $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => $count, 'route' => 'javascript:void(0);']);
            $pagination->setPage($page - 1);

//      搜索结果:搜索词不为空  &page_id=pc_index_search
//      筛选结果&page_id=pc_index_filter
//      近期优选&page_id=pc_index_hot
//      为你推荐&page_id=pc_index_recommend
        $pageId = '';
        if (!empty($filter['search_content'])) {
            $pageId = 'pc_index_search';
        } elseif ($type == 'search') {
            $pageId = 'pc_index_filter';
        } elseif ($type == 'hot') {
            $pageId = 'pc_index_hot';
        } elseif ($type == 'recommend') {
            $pageId = 'pc_index_recommend';
        }

            return $this->controller->renderPartial(
                'index_api2', [
                    'data'                      => !empty($data['data']['list']) ? $data['data']['list'] : [],
                    'type'                      => $type,
                    'pagination'                => $pagination,
                    'page_id'                   => $pageId,
//                    'qr_code'                   => Common::createQrWithUrl(),
                ]
            );
//        }

//        $jobModel = Job::find();
//        $jobModel->andFilterWhere(['job_open_status' => Job::POST_OPEN_STATUS_OPEN]);
//
//
//        $filter = Yii::$app->request->get();
//
//        if (!empty($filter['search_content'])) {
//            $keywords = $filter['search_content'];
//            $keywordsType = !empty($filter['search_type']) ? $filter['search_type'] : 'all';
//            switch ($keywordsType) {
//                case 'all':
//
//                    $inQuery = (new Query())->select('id')
//                        ->from('o_company')
//                        ->where(['like', 'company_name', $keywords ]);
//                    $q = $inQuery->createCommand();
//
//                    $jobModel->andFilterWhere([
//                        'or',
//                        ['like', 'job_name', $keywords],
////                        ['job_name' => 1]
////                        ['customerCompany' => function ($model) use ($keywords) {
////                            return $model->where(['like', 'company_name', $keywords]);
////                        }]
//                        [
//                           'in', 'customer_company_id', (new Query())
//                                ->select('id')
//                                ->from('o_company')
//                                ->where(['like', 'company_name', $keywords ])
//                        ]
//                    ]);
////                    $jobModel->andFilterWhere(['a' => 1]);
//                    break;
//                case 'job':
//                    $jobModel->andFilterWhere([
//                        'like', 'job_name', $keywords,
//                    ]);
//                    break;
//                case 'company':
//                    $jobModel->andFilterWhere([
////                        'customerCompany' => function ($model) use ($keywords) {
////                            return $model->where(['like', 'company_name', $keywords]);
////                        }
//                        'in', 'customer_company_id', (new Query())
//                            ->select('id')
//                            ->from('o_company')
//                            ->where(['like', 'company_name', $keywords ])
//                    ]);
//                    break;
//            }
//
//            // save search history
//            $history = SearchHistory::findOne([
//                'title'     => $keywords,
//                'type'      => SearchHistory::SEARCH_HISTORY_TYPE_KEYWORD,
//                'user_id'   => Yii::$app->user->id,
//            ]);
//
//            if (!$history) {
//                $history = new SearchHistory([
//                    'title'     => $keywords,
//                    'type'      => SearchHistory::SEARCH_HISTORY_TYPE_KEYWORD,
//                    'user_id'   => Yii::$app->user->id,
//                    'times'     => 0,
//                ]);
//            }
//
////            var_dump($history);
//            $history->times += 1;
//            $history->save();
//        }
//
//        if (!empty($filter['city'])) {
//            $expectCity = $filter['city'];
//            if (!empty($expectCity[0]) && strpos($expectCity[0], ',') !== false) {
//                $expectCity = explode(',', $expectCity[0]);
//            }
//
//            $cityInfos = City::find()
//                ->where([
//                    'id'    => $expectCity
//                ])
//                ->andWhere([
//                    'deleted_at' => null,
//                ])
//                ->all();
//
//            $cityTree = [];
//            foreach ($cityInfos as $cityInfo) {
//                $cityTree = array_merge($cityTree, Yii::$app->city->getCityChildrenTree($cityInfo));
//            }
//
//            $expectCity = array_merge($expectCity, $cityTree);
//
//
//            $jobModel->andFilterWhere([
//                'work_city' => $expectCity
//            ]);
//        }
//
//        if (!empty($filter['industry'])) {
//            $expectIndustry = $filter['industry'];
//            $jobModel->andFilterWhere([
////                'industry' => $expectIndustry
//                'in', 'industry', Industry::find()
//                    ->select('id')
//                    ->where([
//                        'or',
//                        ['parent_id' => $expectIndustry],
//                        ['id'    => $expectIndustry],
//                    ])
//                    ->andWhere([
//                        'deleted_at' => null,
//                    ])
//            ]);
//        }
//
//        if (!empty($filter['post'])) {
//            $post = $filter['post'];
//
//            $postTree = [];
//            $postInfos = Tag::find()
//                ->where([
//                    'id'    => $post,
//                ])
//                ->all();
//            foreach ($postInfos as $postInfo) {
//                $postTree = array_merge($postTree, Yii::$app->common->getIdWithChildrenTree($postInfo));
//            }
//
//            $postList = array_merge($post, $postTree);
//
//            $jobModel->andFilterWhere([
////                'post' => $post
////                'in', 'post', PostBasic::find()
////                    ->select('post_id')
////                    ->where(['second_class' => $post])
//                'post' => $postList,
//            ]);
//        }
//
//        if (!empty($filter['day'])) {
//            $day = $filter['day'][0];
//            $jobModel->andFilterWhere([
//                '>', 'o_job.created_at', strtotime('-' . $day . 'days')
//            ]);
//        }
//
////        if (!empty($filter['publish_date_min'])) {
////            $jobModel->andFilterWhere([
////                '>', 'created_at', strtotime($filter['publish_date_min'])
////            ]);
////        }
////
////        if (!empty($filter['publish_date_max'])) {
////            $jobModel->andFilterWhere([
////                '<', 'created_at', strtotime($filter['publish_date_max'])
////            ]);
////        }
//
//        if (!empty($filter['salary'])) {
////            foreach ($filter['salary'] as $salaryArray) {
////                list($salaryMin, $salaryMax) = explode(',', $salaryArray);
//            list($salaryMin, $salaryMax) = explode('-',$filter['salary'][0]);
//            $salaryMin = intval($salaryMin);
//            $salaryMax = intval($salaryMax);
//                $jobModel
//                    ->andFilterWhere([
//                        'and',
//                        ['<=', 'salary_min', $salaryMax,],
//                        ['>=', 'salary_max', $salaryMin,]
//                    ]);
//
////            }
//        }
//
//        if (isset($filter['is_read'])) {
//            if ($filter['is_read'] == 1) {
//                // 未读
////                $jobModel->joinWith([
////                    'view' => function ($model) use ($filter) {
////                        $model->where(['o_view.created_at' => 0]);
////                    }
////                ]);
//                $jobModel->andFilterWhere([
//                    'not in', 'id', View::find()
//                        ->select('object_id')
//                        ->where(['user_id' => Yii::$app->user->id])
//                        ->andFilterWhere(['object_type' => View::OBJECT_TYPE_JOB])
//                        ->andFilterWhere(['>', 'created_at', 0])
//
//                ]);
//            } elseif ($filter['is_read'] == 2) {
//                // 已读
////                $jobModel->joinWith([
////                    'view' => function ($model) use ($filter) {
////                        $model->where(['>', 'o_view.created_at', 0]);
////                    }
////                ]);
//                $jobModel->andFilterWhere([
//                    'in', 'id', View::find()
//                        ->select('object_id')
//                        ->where(['user_id' => Yii::$app->user->id])
//                        ->andFilterWhere(['object_type' => View::OBJECT_TYPE_JOB])
//                        ->andFilterWhere(['>', 'created_at', 0])
//
//                ]);
//            }
//        }
//
//        $jobModel->andFilterWhere(['job_status' => Job::POST_STATUS_OPEN]);
////        $jobModel->andFilterWhere(['a' => 1]);
//
//        $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => $jobModel->count(), 'route' => 'javascript:void(0);']);
//        $pagination->pageSize = 10;
//
//
//        $orderBy = !empty($filter['order_by']) ? $filter['order_by'] : 'default';
//
//        switch ($orderBy) {
//            case 'divide_most':
//                $jobModel->orderBy(['estimated_share' => SORT_DESC]);
//                break;
//            case 'period_mini':
//                $jobModel->orderBy(['pay_time' => SORT_ASC]);
//                break;
//            case 'default':
//            default:
//                $jobModel->orderBy(['created_at' => SORT_DESC]);
//                break;
//        }
//
//        $jobModel->offset($pagination->offset);
//        $jobModel->limit($pagination->limit);
////var_dump($jobModel->createCommand()->getRawSql());exit;
//        $data = $jobModel->all();
//
//        return $this->controller->renderPartial(
//            'index_api', [
//                'data'                      => $data,
//                'type'                      => $type,
//                'pagination'                => $pagination,
//            ]
//        );
    }
}