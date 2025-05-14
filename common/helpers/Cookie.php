<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/27
 * Time: 下午4:33
 */

namespace common\helpers;

use yii;
use yii\web\Cookie as CookieBase;

class Cookie
{
    public static function getCookie($name, $defaultValue = null)
    {
        $cookies = Yii::$app->request->getCookies();
        if ($cookies) {
            return $cookies->getValue($name, $defaultValue);
        }

        return $defaultValue;
    }

    public static function setCookie($name, $value, $expire = 0, $httpOnly = false, $domain = '', $path = '/')
    {
        $cookies = Yii::$app->response->getCookies();
        $cookies->add(new CookieBase([
            'name' => $name,
            'value' => $value,
            'expire' => time() + $expire,
            'domain' => $domain,
            'path' => $path,
            'httpOnly' => $httpOnly,
        ]));
    }

    public static function unsetCookie($name)
    {
        $cookies = Yii::$app->response->getCookies();
        $cookies->remove($name, true);
    }
}
