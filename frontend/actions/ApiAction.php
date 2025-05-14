<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2018/2/11
 * Time: 上午10:36
 */

namespace frontend\actions;


use yii\base\Action;
use yii;

class ApiAction extends \liyifei\base\actions\ApiAction
{

    const TOKEN_SECRET = '123';

    public function init()
    {
        parent::init();

    }

    public function valToken() {

        $_get = Yii::$app->request->get();

        if (!empty($_get['is_test'])) {
            return true;
        }

        $userId = !empty($_get['user_id']) ? $_get['user_id'] : 0;
//        $uri = Yii::$app->request->getPathInfo();
        $timestamp = !empty($_get['timestamp']) ? $_get['timestamp'] : 0;
        $token = !empty($_get['token']) ? $_get['token'] : '';

        $valToken = md5( $userId . $timestamp . self::TOKEN_SECRET);

        if ($token != $valToken) {
            throw new yii\db\Exception('token验证无效');
        }

        return true;
    }


}