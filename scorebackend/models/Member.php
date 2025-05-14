<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:53 PM
 */

namespace backend\models;


use common\models\ConsultantCompany;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class Member extends \common\models\Member
{

    public $search;

    public function rules()
    {
        return [
            [['type', 'company_id', 'member_status', 'user_role', 'created_at', 'updated_at', 'audit_at',], 'integer'],
            [['user_name', 'english_name', 'true_name', 'audit_reason', 'mobile', 'mobile_section', ], 'string'],
            [['email', 'wx', 'last_visit', 'user_no', 'old_user_no', 'identity_no', 'authorize', 'remark'], 'string'],
            [['password'], 'string', 'max' => 128],
            [['avatar'], 'string', 'max' => 255],
//            [['true_name'], 'string', 'max' => 5],
        ];
    }

    public function search($params)
    {
        $query = \common\models\Member::find();
//        $query->with(['profile']);
        $memberStatus = !empty($params['member_status']) ? $params['member_status'] : \common\models\Member::MEMBER_STATUS_WAIT_AUDIT;

        $query->where([
            'type' => Member::MEMBER_TYPE_ADMIN,
            'member_status'    => $memberStatus,
        ]);

        if (!empty($params['sort'])) {
            if ($params['sort'] == 'time-up') {
                $defaultOrder = ['created_at' => SORT_DESC];
            } else {
                $defaultOrder = ['created_at' => SORT_ASC];
            }
        } else {
            $defaultOrder = ['created_at' => SORT_DESC];
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $defaultOrder
            ],
        ]);
        $this->load($params);
        if (!empty($params['apply-time']) && $params['apply-time'] != 'all') {

            $query->andFilterWhere(['>', 'created_at', strtotime('now') - ($params['apply-time'] * 86400)]);
        }

        if (!empty($params['search'])) {

            $companyQuery = ConsultantCompany::find()
                ->select('id')
                ->where([
                    'like', 'company_name', $params['search']
                ]);

            $query->andFilterWhere([
                    'or',
                    ['in', 'company_id', $companyQuery],
                    ['like', 'true_name', $params['search']]
                ]
            );
        }
//        $query->andFilterWhere(['a' => 1]);
//        $query->andFilterWhere(['mobile' => $this->mobile]);
//        if ($this->start && $this->end) {
//            $query->andFilterWhere(['between', 'created_at', strtotime($this->start), strtotime('+1day', strtotime($this->end))]);
//        }

        return $dataProvider;
    }

    public function searchList($params)
    {
        $query = \common\models\Member::find();
//        $query->with(['profile']);
        $memberStatus = !empty($params['member_status']) ? $params['member_status'] : \common\models\Member::MEMBER_STATUS_NORMAL;

        $query->where([
//            'type' => Member::MEMBER_TYPE_ADMIN,
            'member_status'    => $memberStatus,
        ]);

        if (!empty($params['sort'])) {
            if ($params['sort'] == 'time-up') {
                $defaultOrder = ['created_at' => SORT_DESC];
            } else {
                $defaultOrder = ['created_at' => SORT_ASC];
            }
        } else {
            $defaultOrder = ['created_at' => SORT_DESC];
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $defaultOrder
            ],
        ]);
        $this->load($params);
        if (!empty($params['apply-time']) && $params['apply-time'] != 'all') {

            $query->andFilterWhere(['>', 'created_at', strtotime('now') - ($params['apply-time'] * 86400)]);
        }

        if (!empty($params['Member']['search'])) {

            $companyQuery = ConsultantCompany::find()
                ->select('id')
                ->where([
                    'like', 'company_name', $params['Member']['search']
                ]);

            $query->andFilterWhere([
//                    'or',
                    'in', 'company_id', $companyQuery,
//                    ['like', 'true_name', $params['Member']['search']]
                ]
            );
        }

        if (!empty($params['Member']['true_name'])) {

            $query->andFilterWhere([
                    'like', 'true_name', $params['Member']['true_name'],
                ]
            );
        }
        if (!empty($params['Member']['type'])) {
            $query->andFilterWhere([
                    'type' => (int)$params['Member']['type'],
                ]
            );
        }

        if (!empty($params['Member']['mobile'])) {

            $query->andFilterWhere([
                    'like', 'mobile', $params['Member']['mobile'],
                ]
            );
        }

//        var_dump($query->createCommand()->getRawSql());

//        $query->andFilterWhere(['a' => 1]);
//        $query->andFilterWhere(['mobile' => $this->mobile]);
//        if ($this->start && $this->end) {
//            $query->andFilterWhere(['between', 'created_at', strtotime($this->start), strtotime('+1day', strtotime($this->end))]);
//        }

        return $dataProvider;
    }
}