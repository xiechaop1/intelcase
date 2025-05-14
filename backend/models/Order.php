<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/25
 * Time: 上午11:26
 */

namespace backend\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class Order extends \common\models\Order
{
    public $mobile;

    public $music_title;

    public $music_singer;
    public $music_lyricist;
    public $music_composer;

    public function rules()
    {
        return [
            [['user_id', 'music_id', 'pay_method', 'order_status', 'order_permission', 'status', 'expire_time', 'created_at', 'updated_at'], 'integer'],
            [['price', 'amount'], 'number'],
            [['attach'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = \common\models\Order::find();
        $query->with('user', 'music');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => false
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
        ]);

        if (isset($params['Order']['order_status']) && $params['Order']['order_status'] >= 0) {
            $query->andFilterWhere([
                'o_order.order_status'  => $params['Order']['order_status']
            ]);
        } else {
            $query->andFilterWhere([
                'o_order.order_status'  => [
                    \common\models\Order::ORDER_STATUS_PAIED,
                    \common\models\Order::ORDER_STATUS_COMPLETED
                ]
            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!empty($params['Order']['title'])) {
            $query->joinWith(['music' => function($model) use ($params) {
                return $model->andFilterWhere(['like', 'o_music.title', $params['Order']['title']]);
            }]);
        }

        if (!empty($params['Order']['singer'])) {
            $query->joinWith(['music' => function($model) use ($params) {
                return $model->andFilterWhere(['like', 'o_music.singer', $params['Order']['singer']]);
            }]);
        }

        if (!empty($params['Order']['lyricist'])) {
            $query->joinWith(['music' => function($model) use ($params) {
                return $model->andFilterWhere(['like', 'o_music.lyricist', $params['Order']['lyricist']]);
            }]);
        }

        if (!empty($params['Order']['composer'])) {
            $query->joinWith(['music' => function($model) use ($params) {
                return $model->andFilterWhere(['like', 'o_music.composer', $params['Order']['composer']]);
            }]);
        }

        if (!empty($params['Order']['mobile'])) {
            $query->joinWith(['user' => function($model) use ($params) {
                return $model->andFilterWhere(['like', 'o_user.mobile', $params['Order']['mobile']]);
            }]);
        }
//        $query->andFilterWhere([
//            'like', 'mobile', $this->mobile
//        ]);
        if (!empty($params['Order']['user_id'])) {
            $query->andFilterWhere([
                'o_order.user_id' => $params['Order']['user_id']
            ]);
        }

        if (empty($params['is_delete'])) {
            $query->andFilterWhere([
                'o_order.is_delete' => \common\definitions\Common::STATUS_NORMAL
            ]);
        } else {
            $query->andFilterWhere([
                'o_order.is_delete' => $params['is_delete']
            ]);
        }

        if (!empty($params['Order']['date_range'])) {
            $query->andFilterWhere([
                '>', 'created_at', time() - $params['Order']['date_range'] * 24 * 3600
            ]);
        }

        $query->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;


    }

}