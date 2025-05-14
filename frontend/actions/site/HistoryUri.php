<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\site;


use common\models\SearchHistory;
use liyifei\base\actions\ApiAction;
use yii;

class HistoryUri extends ApiAction
{
    public $action;

    public function run()
    {
        switch ($this->action) {
            case 'save':
                $ret = $this->save();
                break;
            case 'delete':
                $ret = $this->delete();
                break;
        }


        return $this->success($ret);
    }

    public function save() {
        $get = Yii::$app->request->get();
        $userId = Yii::$app->user->id;

        $model = null;
        if (
            !empty($get['uri'])
            && !empty($get['title'])
        ) {
            $uri = $get['uri'];
            $title = $get['title'];
            $model = SearchHistory::findOne([
                'user_id'   => $userId,
                'type'      => SearchHistory::SEARCH_HISTORY_TYPE_URI,
                'title'     => $title,
                'uri'       => $uri
            ]);

            if (empty($model)) {
                $model = new SearchHistory();
                $model->user_id = $userId;
                $model->type    = SearchHistory::SEARCH_HISTORY_TYPE_URI;
                $model->uri     = $uri;
                $model->title   = $title;
            }

            $model->times += 1;
            $model->save();
        }
        return $model;
    }

    public function delete() {
        $get = Yii::$app->request->get();
        $userId = Yii::$app->user->id;

        $type = !empty($get['type']) ? $get['type'] : SearchHistory::SEARCH_HISTORY_TYPE_KEYWORD;

        if (!empty($get['id'])
            || (!empty($get['uri']) && !empty($get['title']))
        ) {
            if (!empty($get['id'])) {
                $model = SearchHistory::findOne([
                    'id'        => $get['id'],
                    'user_id'   => $userId,
                ]);
            } else {
                $model = SearchHistory::findOne([
                    'user_id'   => $userId,
                    'title'     => $get['title'],
                    'uri'       => $get['uri'],
                    'type'      => $type
                ]);
            }

            if (!empty($model)) {
                $model->delete();
            }

        }
    }

}