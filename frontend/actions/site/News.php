<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 4:17 PM
 */

namespace frontend\actions\site;


use common\models\Messages;
use common\models\Member;
use common\models\View;
use common\services\ViewsService;
use yii\base\Action;
use yii;

class News extends Action
{
    public function run()
    {
        $get = Yii::$app->request->get();

        $userType = Yii::$app->user->identity->company_id == 0 ? Message::MESSAGE_MEMBER_CLASS_PERSONAL : Message::MESSAGE_MEMBER_CLASS_COMPANY;
        $model = Messages::find()
//            ->andFilterWhere(['receiver_id' => Yii::$app->user->id]);
            ->where(['receiver_id' => Yii::$app->user->id,
                'message_type' => [
                    Messages::MESSAGE_TYPE_DOCUMENT,
                    Messages::MESSAGE_TYPE_JOB
                ]
            ])
            ->orFilterWhere([
                'receiver_id' => [
                    0,
                    $userType
                ],
                'message_type' => Messages::MESSAGE_TYPE_SYSTEM
            ]);
        if (!empty($get['type'])) {

            $model->andFilterWhere(['message_type' => $get['type']]);
        }
        if (isset($get['is_read'])) {
            $model->andWhere(['is_read' => $get['is_read']]);
        }

        if ( !empty($get['type']) && $get['type'] == Messages::MESSAGE_TYPE_JOB ) {
            $showType = 'work';
        } elseif ( !empty($get['type']) && $get['type'] == Messages::MESSAGE_TYPE_SYSTEM ) {
            $showType = 'system';
        } elseif ( empty($get['type']) ) {
            $showType = 'all';
        } else {
            $showType = 'unread';
        }

        $userType = Yii::$app->user->identity->company_id == 0 ? Message::MESSAGE_MEMBER_CLASS_PERSONAL : Message::MESSAGE_MEMBER_CLASS_COMPANY;
        $retCount = [];
        $retTypeCount = Messages::find()
            ->select(['message_type, count(1) as count'])
            ->groupBy(['message_type'])
//            ->where(['receiver_id' => Yii::$app->user->id])
            ->asArray()
            ->all();

        $retUnread = Messages::find()
            ->select(['count(case is_read when 0 then 1 end) as unread_total', 'count(1) as total'])
//            ->where(['receiver_id' => Yii::$app->user->id])
            ->where(['receiver_id' => Yii::$app->user->id,
                'message_type' => [
                    Messages::MESSAGE_TYPE_DOCUMENT,
                    Messages::MESSAGE_TYPE_JOB
                ]
            ])
            ->orFilterWhere([
                'receiver_id' => [
                    0,
                    $userType
                ],
                'message_type' => Messages::MESSAGE_TYPE_SYSTEM
            ])
//            ->andFilterWhere(['a' => 1])
            ->asArray()
            ->all();

        if (!empty($retTypeCount)) {
            foreach ($retTypeCount as $r) {
                $retCount[$r['message_type']] = $r['count'];
            }
        }

        if (!empty($retUnread)) {
            $r = $retUnread[0];
            foreach ($r as $key => $ct) {
                $retCount[$key] = $ct;
            }
        }

        $model->andFilterWhere(['a' => 1]);

        $pagination = new yii\data\Pagination(['pageSizeParam' => false, 'totalCount' => $model->count() * 1000, 'route' => 'javascript:void(0);']);

        $ret = $model->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        // 记录view
        Yii::$app->pageview->addNew(
            View::OBJECT_TYPE_MESSAGE,
            Yii::$app->user->id,           // 进入页面就算，不关心哪条记录
            Yii::$app->user->id,
            ViewsService::VIEW_UPDATE_METHOD_USER
        );


        return $this->controller->render('news', [
            'messsages' => $ret,
            'count'     => $retCount,
            'get'       => $get,
            'show_type' => $showType,
            'pagination'   => $pagination,
        ]);
    }
}