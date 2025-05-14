<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 3:13 PM
 */

namespace frontend\actions\download;


use common\helpers\Cookie;
use common\models\Member;
use common\models\MemberInvite;
use common\models\MemberInviteCode;
use common\models\Music;
use common\services\HewaApi;
use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii;

class Redirect extends Action
{

    public function run()
    {
        // 判断有url字段，就按照url跳转
        if (!empty(Yii::$app->request->get('url'))) {
            header('Location: ' . Yii::$app->request->get('url'));
            exit;
        }

        // 读取歌曲里的物料地址
        $music = Music::findOne(['id' => Yii::$app->request->get('music_id')]);
        if (!empty($music['resource_download_url'])) {
            if (strpos($music['resource_download_url'], 'http') === false) {
                $music['resource_download_url'] = 'http://' . $music['resource_download_url'];
            }
            header('Location: ' . $music['resource_download_url']);
        } else {
            echo '没有找到资源地址';
        }

    }
}