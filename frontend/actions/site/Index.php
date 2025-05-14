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
use common\models\ConsultantCompany;
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

class Index extends Action
{
    public function run()
    {
        $this->controller->layout = '@frontend/views/layouts/main_r.php';
        $latestViewLimit = 5;
        $messageLimit = 5;

        $searchHistoryKeyword = SearchHistory::find()
            ->where([
                'user_id' => Yii::$app->user->id,
                'type' => SearchHistory::SEARCH_HISTORY_TYPE_KEYWORD,
//                ['>', 'created_at', strtotime('-30days')],
            ])
            ->andFilterWhere(['>', 'created_at', strtotime('-30days')])
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit(SearchHistory::SEARCH_HISTORY_KEYWORD_MAX)
            ->all();

        $searchHistoryUri = SearchHistory::find()
            ->where(['user_id' => Yii::$app->user->id, 'type' => SearchHistory::SEARCH_HISTORY_TYPE_URI])
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit(SearchHistory::SEARCH_HISTORY_URI_MAX)
            ->all();

        $latestView = View::find()
            ->where(['user_id' => Yii::$app->user->id, 'object_type' => View::OBJECT_TYPE_JOB])
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit($latestViewLimit)
//            ->with('job')
            ->all();

        $messageList = Messages::find()
            ->where(['receiver_id' => Yii::$app->user->id, 'is_read' => 0])
            ->limit($messageLimit)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        // filter
//        $cityList = City::find()
//            ->where(['level' => 1])
////        '北京', '上海', '广州', '深圳', '苏州', '杭州', '重庆'
////            ->andFilterWhere(['in', 'id', [
////                1,
////                792,
////                849,
////                921,
////                1936,
////                1959,
////                2236,
////                3260,
////            ]])
////            ->andFilterWhere(['id' => []])
////            ->andFilterWhere([
////                '>=', 'sort', 0
////            ])
////            ->orderBy(['sort' => SORT_DESC])
//            ->all();

//        $allCityList = City::find()
//            ->where(['level' => [1, 2]])
//            ->andWhere([
//                'deleted_at' => null,
//            ])
//            ->orderBy([
//                'sort'  => SORT_DESC,
//                'id'    => SORT_ASC
//            ])
//            ->all();
//
//        $cityList = [];
//        $defaultCity = [
//            2   => '北京市',
//            793 => '上海市',
//            1936    => '广州市',
//            1959    => '深圳市',
//            921     => '杭州市',
//            1682    => '武汉',
//            811     =>'南京市',
//            849     => '苏州市'];
////var_dump(\common\helpers\Common::getRealIP());
////        $cityAlias =  [
////            1       => ['北京市', 'B'],
////            792     => ['上海市', 'S'],
//////            1935    => ['广州市', 'G'],
//////            1959    => ['深圳市', 'S'],
//////            920     => ['杭州市', 'H'],
//////            811     => ['南京市', 'N'],
//////            849     => ['苏州市', 'S'],
////            19      => ['天津市', 'T'],
////            2236    => ['重庆市', 'C'],
////
////        ];
//        $allCityListByFirstword = [];
//        $allCityAreaList = [];
//        $allProvince = [];
//        $allCity = [];
//        $cityWordMap = [
//            [
////                'col'   => 'city_name',
////                'val'   => ['北京市','上海市','广州市','深圳市','杭州市', '苏州市', '南京市', '武汉市', '成都市', '重庆', '长沙市', '西安市', '郑州市', '沈阳市'],   // , '香港特别行政区', '澳门特别行政区', '台湾省'
//                'col'       => 'id',
//                'val'       => [1,792,1936,1959,921,1682,849,811,2278,2237,1799,2809,1506,465,],
//                'index'     => 1,
//            ],
//            [
//                'col'   => 'first_word',
//                'val' => [
//                    'MIN'   => 'A',
//                    'MAX'   => 'E',
//                ],
//                'index' => 2,
//            ],
//            [
//                'col'   => 'first_word',
//                'val' => [
//                    'MIN'   => 'F',
//                    'MAX'   => 'J',
//                ],
//                'index' => 3,
//            ],
//            [
//                'col'   => 'first_word',
//                'val' => [
//                    'MIN'   => 'K',
//                    'MAX'   => 'O',
//                ],
//                'index' => 4,
//            ],
//            [
//                'col'   => 'first_word',
//                'val' => [
//                    'MIN'   => 'P',
//                    'MAX'   => 'T',
//                ],
//                'index' => 5,
//            ],
//            [
//                'col'   => 'first_word',
//                'val' => [
//                    'MIN'   => 'U',
//                    'MAX'   => 'Z',
//                ],
//                'index' => 6,
//            ],
//            [
//                'col'   => 'country',
//                'val'   => '海外',
//                'index' => 7,
//            ],
//        ];
//
//        if (!empty($allCityList)) {
//            foreach ($allCityList as $city) {
//                if (isset($cityAlias[$city->id])) {
//                    $city->city_name    = $cityAlias[$city->id][0];
//                    $city->first_word   = $cityAlias[$city->id][1];
//                }
//
//                if ($city->level == 1) {
//                    $allProvince[] = $city;
//                }
//                if ($city->level == 2) {
//                    $allCity[$city->parent_id][] = $city;
//                }
//
//                $firstWordLevel = 0;
////                if ($city->level == 1) {
////                if (in_array($city->city_name, $defaultCity)) {
////                    $cityList[] = $city;
////                }
////                if ($city->level == 3) {
////                    $allCityAreaList[$city->parent->parent_id][] = $city;
////                }
//                foreach ($cityWordMap as $arr) {
//                    $col = $arr['col'];
//                    $firstWordLevel = 0;
//                    if (isset($city->$col)) {
//                        if (is_array($arr['val'])) {
//                            if (is_int(array_keys($arr['val'])[0])){
//                                if (in_array($city->$col, $arr['val'])) {
//                                    $firstWordLevel = $arr['index'];
//                                }
//                            } else {
//                                if (isset($arr['val']['MIN'])
//                                    && isset($arr['val']['MAX'])
//                                ) {
//                                    $minVal = is_string($arr['val']['MIN']) ? ord($arr['val']['MIN']) : $arr['val']['MIN'];
//                                    $maxVal = is_string($arr['val']['MAX']) ? ord($arr['val']['MAX']) : $arr['val']['MAX'];
//                                    $val = is_string($city->$col) ? ord($city->$col) : $city->$col;
//
//                                    if ($val >= $minVal
//                                        && $val <= $maxVal
//                                        && ($col == 'first_word' && $city->country == '中国' && $city->level == 1) // 首字母的国家特殊处理成中国
//                                    ) {
//                                        $firstWordLevel = $arr['index'];
//                                    }
//                                }
//                            }
//                        } else {
//                            if ($city->$col == $arr['val']) {
//                                $firstWordLevel = $arr['index'];
//                            }
//                        }
//                        $allCityListByFirstword[$firstWordLevel][] = $city;
//                    }
//                }
//
//                if (isset($defaultCity[$city->id])) {
//                    $defaultC = $city;
////                    $defaultC->city_name = $cityAlias[$city->id];
//    //                    $cityList[$defaultC->first_word] = $defaultC;
//                    $cityList[] = $defaultC;
//                }
//            }
//
//            usort($allCityListByFirstword[1], 'self::sortCity');
//            usort($cityList, 'self::sortCity');
//            usort($allProvince, 'self::sortCity');
//
//        }

        $cityData = Yii::$app->city->getCitiesForView();

        $allCityListByFirstword     = $cityData['all_city_list_by_first_word'];
        $allCity                    = $cityData['all_city'];
        $allCityAreaList            = $cityData['all_city_area_list'];
        $allProvince                = $cityData['all_province'];
        $cityList                   = $cityData['city_list'];
        $cityWordMap                = $cityData['city_word_map'];


//        $defaultIndustry = [
//            1, 14, 23, 28, 56
//        ];
//
//        $industryList = Industry::find()
//            ->where(['parent_id' => 0])
//            ->andFilterWhere(['id' => $defaultIndustry])
//            ->andFilterWhere([
//                '>=', 'sort', 0
//            ])
//            ->andWhere([
//                'deleted_at' => null,
//            ])
//            ->all();
//
//        $allIndustryList = Industry::find()
////            ->where(['parent_id' => 0])
////            ->andFilterWhere([
////                '>=', 'sort', 0
////            ])
//            ->andWhere([
//                'deleted_at' => null,
//            ])
//            ->all();
//
//        $allIndustryListByClass = [];
//        $allIndustryClass = [];
//        $visibleClass = [1, 14, 23, 28, 56, 48, 64, 38, 85];
//        if (!empty($allIndustryList)) {
//            foreach ($allIndustryList as $ind) {
//                if ($ind->parent_id == 0
//                    && in_array(
//                        $ind->id, $visibleClass
//                    )
//                ) {
//                    $allIndustryClass[] = $ind;
//                } else if ($ind->parent_id > 0) {
//                    if ( in_array($ind->parent_id, $visibleClass) ) {
//                        $allIndustryListByClass[$ind->parent_id][] = $ind;
//                    } else {
//                        // 剩余分类都放入"其他"
//                        $allIndustryListByClass[85][] = $ind;
//                    }
//                }
//            }
//        }

        $industryData           = Yii::$app->industry->getIndustryForView();
        $allIndustryListByClass = $industryData['all_industry_list_by_class'];
        $allIndustryClass       = $industryData['all_industry_class'];
        $industryList           = $industryData['industry_list'];

//        '财务', '法务', '人力资源', '销售', '市场', '设计', '运营', '产品'
//        $postList = PostBasic::find()
//            ->select('distinct(first_class)')
////            ->where(['in', 'id', [
////                2,26,67,86,118,457,652,682
////            ]])
////            ->where(['<=', 'id', 10])
//            ->all();
        $postListAll = Tag::find()
//            ->where([
//                'in', 'id', PostBasic::find()
//                    ->select('distinct(first_class)')
//            ])
            ->where([
                'tag_type' => [
                    Tag::TAG_TYPE_POST_BASIC,
                    Tag::TAG_TYPE_POST_BASIC_CLASS_FIRST,
                    Tag::TAG_TYPE_POST_BASIC_CLASS_SECOND
                ],
                'level' => [
                    Tag::TAG_LEVEL_1,
                    Tag::TAG_LEVEL_2
                ],
                'deleted_at' => null
            ])
            ->orderBy([
                'sort' => SORT_ASC,
                'parent_id' => SORT_ASC,
                'id' => SORT_ASC,
            ])
            ->all();

        $postList = [];
        $postFirstClassList = [];
        $postSecondClassList = [];
        $postListRange = [
            1, 153, 606, 737, 824, 931
        ];
        foreach ($postListAll as $post) {
            if ($post->level == Tag::TAG_LEVEL_1) {
                $postFirstClassList[$post->id] = $post->tag_name;
                if (in_array($post->id, $postListRange)) {
                    $postList[] = $post;
                }
            } else {
                $postSecondClassList[$post->parent_id][$post->id] = $post->tag_name;
            }
        }

//        $jobModel = Job::find();
//        $jobModel->andFilterWhere(['job_open_status' => Job::POST_OPEN_STATUS_OPEN]);
//
////        echo '[8: ' . microtime(true);
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
//                            'in', 'customer_company_id', (new Query())
//                            ->select('id')
//                            ->from('o_company')
//                            ->where(['like', 'company_name', $keywords ])
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
//                        'customerCompany' => function ($model) use ($keywords) {
//                            return $model->where(['like', 'company_name', $keywords]);
//                        }
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
//
//            $jobModel->andFilterWhere([
//                'work_city' => $expectCity
//            ]);
//        }
//
//        if (!empty($filter['industry'])) {
//            $expectIndustry = $filter['industry'];
//
//            $jobModel->andFilterWhere([
//                'industry' => $expectIndustry
//            ]);
//        }
//
//        if (!empty($filter['post'])) {
//            $post = $filter['post'];
//
//            $jobModel->andFilterWhere([
//                'post' => $post
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
//            $jobModel
//                ->andFilterWhere([
//                    'and',
//                    ['<', 'salary_min', $salaryMax,],
//                    ['>', 'salary_max', $salaryMin,]
//                ]);
//
////            }
//        }
//
//        if (isset($filter['is_read'])) {
//            if ($filter['is_read'] == 1) {
//                // 未读
//                $jobModel->joinWith([
//                    'view' => function ($model) use ($filter) {
//                        $model->where(['o_view.created_at' => 0]);
//                    }
//                ]);
//            } elseif ($filter['is_read'] == 2) {
//                // 已读
//                $jobModel->joinWith([
//                    'view' => function ($model) use ($filter) {
//                        $model->where(['>', 'o_view.created_at', 0]);
//                    }
//                ]);
//            }
//        }
////        $jobModel->andFilterWhere(['a' => 1]);
//
        $daysList = [
            7   => '7天',
            15  => '15天',
            30  => '30天',
            60  => '60天',
            90  => '90天',
        ];
//
//        $jobModel->andFilterWhere(['job_status' => Job::POST_STATUS_OPEN]);
//
//        $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => $jobModel->count(), 'route' => 'javascript:void(0);']);
//        $pagination->pageSize = 10;
//
////        var_dump($pagination);
//        $jobModel->orderBy(['created_at' => SORT_DESC]);
//        $jobModel->offset($pagination->offset);
//        $jobModel->limit($pagination->limit);
////        echo '9: ' . microtime(true);
//        $data = $jobModel->all();

        $type = Yii::$app->request->get('type', 'recommend');
        $orderBy = Yii::$app->request->get('order_by');
        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('limit', 20);
        $userId = !empty(Yii::$app->user->id) ? Yii::$app->user->id : 0;

        $page = !empty($page) ? $page : 1;
        switch ($type) {
            case 'hot':
                $param = [
                    'user_id'   => $userId,
                    'order_by'  => $orderBy,
                    'page'      => $page,
                    'limit'     => $limit,
                ];
                $data = Yii::$app->hewaApi->getJobListByHot($param);
                break;
            case 'search':
                $param = [
                    'user_id'   => $userId,
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
            case 'recommend':
            default:
                $param = [
                    'user_id'   => $userId,
                    'order_by'  => $orderBy,
                    'page'      => $page,
                    'limit'     => $limit,
                ];
                $data = Yii::$app->hewaApi->getJobListByRecommend($param);
                break;
        }

        if (!empty($data)) {
            $count = $data['data']['count'];
            $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => $count, 'route' => 'javascript:void(0);']);
            $pagination->setPage($page - 1);
        } else {
            $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => 0, 'route' => 'javascript:void(0);']);
        }

        $jobCount = Job::find()
            ->where(['job_open_status' => Job::POST_OPEN_STATUS_OPEN])
            ->count();
//        echo '10: ' . microtime(true);
        $consultantCount = Member::find()
            ->where([
                '<>', 'member_status', Member::MEMBER_STATUS_FAIL
            ])
            ->count();

        $rollMessages = [
            '亲爱的小伙伴，目前共有' . $jobCount . '个职位在招哦，加油吧！',
            '有' . $consultantCount . '个小伙伴正在等着接单呐！'
        ];

        $banners = Banner::find()
            ->where([
                'banner_status' => Banner::BANNER_STATUS_SHOW,
                'page'          => 'index',
            ])
            ->orderBy(['sort' => SORT_DESC])
            ->all();

        $params = [
            'activity_position' => HewaApi::BANNER_POSITION_HOME,
            'check_time'        => strtotime('now'),
            'banner_status'     => HewaApi::BANNER_STATUS_OPEN,
        ];
        $bannerData = Yii::$app->hewaApi->getBanner($params);

        $params = [
            'activity_position' => HewaApi::BANNER_POSITION_HOME_RIGHT,
            'check_time'        => strtotime('now'),
            'banner_status'     => HewaApi::BANNER_STATUS_OPEN,
        ];
        $bannerRightData = Yii::$app->hewaApi->getBanner($params);

        $banners = !empty($bannerData['data']) ? $bannerData['data'] : [];

        $bannersRight = !empty($bannerRightData['data']) ? $bannerRightData['data'] : [];

        // 审核通过以后进入首页次数
        $enterData = Cookie::getCookie('enter_ct');
        if (empty($enterData)) {
            $enterArray[$userId]['index'] = 0;
        } else {
            $enterArray = json_decode($enterData, true);
            if (!empty($enterArray[$userId]['index'])) {
                $enterArray[$userId]['index']++;
            } else {
                $enterArray[$userId]['index'] = 0;
            }
        }
        if (
            !empty(Yii::$app->user->identity->consultantCompany) &&
            Yii::$app->user->identity->consultantCompany->company_status == ConsultantCompany::CONSULTANT_COMPANY_STATUS_NORMAL
        ) {
            Cookie::setCookie('enter_ct', json_encode($enterArray), 86400 * 365);
        }
//        var_dump($banners);

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

        return $this->controller->render(
            'index', [
                'type'                      => $type,
                'data'                      => $data['data']['list'],
                'user_id'                   => $userId,
                'search_history'            => $searchHistoryUri,
                'search_history_keywords'   => $searchHistoryKeyword,
                'latest_view'               => $latestView,
                'city_word_map'             => $cityWordMap,
                'city_list'                 => $cityList,
                'all_province'              => $allProvince,
                'all_city'                  => $allCity,
//                'all_city_list'             => $allCityList,
                'all_city_list_by_first'    => $allCityListByFirstword,
                'all_city_area_list'        => $allCityAreaList,
                'all_industry_list_by_class' => $allIndustryListByClass,
                'all_industry_class'        => $allIndustryClass,
                'industry_list'             => $industryList,
                'post_list'                 => $postList,
                'post_first_class_list'     => $postFirstClassList,
                'post_second_class_list'    => $postSecondClassList,
                'days_list'                 => $daysList,
                'messages'                  => $messageList,
                'pagination'                => $pagination,
                'roll_msgs'                 => $rollMessages,
                'banners'                   => $banners,
                'banners_right'             => $bannersRight,
                'enter_ct'                  => $enterArray,
                'page_id'                   => $pageId,
//                'qr_code'                   => Common::createQrWithUrl(),
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