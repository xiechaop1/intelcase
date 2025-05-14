<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/09
 * Time: 下午21:09
 */

namespace backend\models;
use common\models\Job;
use common\models\Recommend;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii;


class Data extends Model
{

    public $dt;
    public $date_begin;
    public $date_end;

    public function search($params)
    {
        date_default_timezone_set('Asia/Shanghai');
//        $query = static::find();

        $dataType = $params['data_type'];

//        if ( empty($params['Data']['dt']) ) {
//            $dt = Date('Y-m-d', strtotime('-1day'));
//        } else {
//            $dt = $params['Data']['dt'];
//        }

        if ( empty($params['Data']['date_begin']) && empty($params['Data']['date_end'])) {
            $dt = Date('Y-m-d', strtotime('-1day'));
            $dateFilter = "date(from_unixtime(a.created_at)) = '" . $dt . "'";
        } else {
            $dateFilterTmp = [];
            if (!empty($params['Data']['date_begin'])) {
                $dateFilterTmp[] = "a.created_at >= " . strtotime($params['Data']['date_begin']);
            }
            if (!empty($params['Data']['date_end'])) {
                $dateFilterTmp[] = "a.created_at <= " . strtotime('+1day', strtotime($params['Data']['date_end']));
            }
            $dateFilter = implode(' and ', $dateFilterTmp);
        }

        $sql = '';
        switch ($dataType) {
            case '1':
                $sql = 'select a.id, date(from_unixtime(a.created_at)) as dt, a.job_name as job_name, b.true_name as true_name from
                    o_job a left join o_member b on a.user_id = b.id where ';
                $sql .= $dateFilter;
                break;
            case '2':
                $sql = 'select a.id, date(from_unixtime(a.created_at)) as dt, c.job_name as job_name, b.true_name as true_name from
                    o_orders a left join o_member b on a.user_id = b.id left join o_job c on a.post_id = c.id where a.user_id is not null and ';
                $sql .= $dateFilter;
                break;
            case '3':
                $sql = 'select a.id, date(from_unixtime(b.created_at)) as dt, c.true_name, d.uname, e.job_name from o_recommend a
                  left join o_recommend_history b on a.id = b.recommend_id left join o_member c on a.user_id = c.id left join o_documents d on a.document_id = d.id left join o_job e on a.post_id = e.id where b.recommend_filter = 10 and ';
                $sql .= $dateFilter;
                $sql .= ' group by id, dt, true_name, uname, job_name';
                break;
            case '4':
                $sql = 'select f.id, date(from_unixtime(a.created_at)) as dt, b.job_name, c.true_name as post_name, d.true_name as receiver_name, e.uname from o_recommend f
                  left join o_job b on f.post_id = b.id 
                  left join o_member c on b.user_id = c.id 
                  left join o_member d on f.user_id = d.id 
                  left join o_documents e on f.document_id = e.id 
                  left join o_recommend_history a on f.id = a.recommend_id
                  where a.recommend_filter = ' . Recommend::RECOMMEND_FILTER_ACCEPT_OFFER . ' and a.recommend_filter_detail = ' . Recommend::RECOMMEND_FILTER_ACCEPT_OFFER_DETAIL . ' and ';
                $sql .= $dateFilter;
//                $sql .= ')';
                break;
        }

//        $sql .= "date(from_unixtime(a.created_at)) = '" . $dt . "'";



        $dataProvider = new yii\data\SqlDataProvider([
//            'query' => $provider,
            'sql' => $sql,
            'sort' => false
        ]);

//        var_dump($dataProvider->sql);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
//
//    public function exec()
//    {
//        if ($this->validate()) {
//            foreach ($this->positions as $position) {
//                if (!\common\models\TagRelation::find()
//                    ->where(['tag_id' => $this->tagId, 'data_id' => $this->articleId, 'type' => TagRelation::TYPE_ARTICLE])
//                    ->exists()) {
//                    $tr = new \common\models\TagRelation([
//                        'tag_id' => $this->tagId,
//                        'data_id' => $this->articleId,
//                        'type' => TagRelation::TYPE_ARTICLE
//                    ]);
//
//                    if (!$tr->save()) {
//                        Yii::warning(json_encode($tr->errors));
//                    }
//                }
//            }
//        }
//    }
}