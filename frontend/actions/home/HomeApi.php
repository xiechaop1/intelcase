<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\home;


use common\models\Banner;
use frontend\actions\ApiAction;
use Yii;


class HomeApi extends ApiAction
{
    public $action;
    public function run()
    {

        switch ($this->action) {
            case 'banner':
                $ret = $this->banner();
                break;
            default:
                $ret = [];
                break;

        }

        return $ret;
    }

    public function banner() {

        $banner = Banner::find()
            ->where(['banner_status' => Banner::BANNER_STATUS_SHOW])
            ->andFilterWhere([
                '<=', 'online_time', time()
            ])
            ->andFilterWhere([
                '>', 'offline_time', time()
            ])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        foreach ($banner as &$item) {
            $item['image'] = \common\helpers\Attachment::completeUrl($item['image']);
        }

        return $this->success($banner);
    }

}