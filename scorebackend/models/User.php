<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/02
 * Time: 下午5:30
 */

namespace backend\models;


use common\definitions\Common;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class User extends \common\models\User
{

    public function rules()
    {
        return [
            [['user_status', 'user_type', 'status', 'last_login_time', 'max_lock_ct', 'created_at', 'updated_at'], 'integer'],
            [['wx_openid', 'remarks', 'wx_unionid', 'wx_token', 'avatar'], 'string'],
//            [['user_name'], 'unique', 'targetClass' => 'common\models\User', 'targetAttribute' => 'user_name', 'filter' => ['<>', 'id', $this->id], 'message' => '已经存在相同的用户名了'],
            [['mobile'], 'unique', 'targetClass' => 'common\models\User', 'targetAttribute' => 'mobile', 'filter' => function ($query) {
                $query->andWhere(['<>', 'id', $this->id]);
                $query->andFilterWhere(['is_delete' => Common::STATUS_NORMAL]);
            }, 'message' => '该手机号已经被其他用户使用了'],

        ];
    }

    public function search($params)
    {
        $query = \common\models\User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => false
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere([
            'like', 'mobile', $this->mobile
        ]);

//        $query->andFilterWhere([
//            'like', 'user_name', $this->user_name
//        ]);

        $query->andFilterWhere([
            'like', 'remarks', $this->remarks
        ]);

        // 判断ID
        if (!empty($params['User']['id'])) {
            $query->andFilterWhere([
                'id' => $params['User']['id']
            ]);
        }

        // 判断用户状态
        if (isset($params['User']['user_type']) && $params['User']['user_type'] > 0) {
            $query->andFilterWhere([
                'user_type'  => $params['User']['user_type']
            ]);
        }

        // 判断用户状态
        if (isset($params['User']['user_status']) && $params['User']['user_status'] >= 0) {
            $query->andFilterWhere([
                'user_status'  => $params['User']['user_status']
            ]);
        }

        if (empty($params['is_delete'])) {
            $query->andFilterWhere([
                'is_delete' => Common::STATUS_NORMAL
            ]);
        } else {
            $query->andFilterWhere([
                'is_delete' => $params['is_delete']
            ]);
        }

        return $dataProvider;
    }
}