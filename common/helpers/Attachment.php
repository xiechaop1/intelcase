<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/3
 * Time: 下午11:01
 */

namespace common\helpers;

use yii;

class Attachment
{

    /**
     * @desc 附件访问路径
     * @param $path
     * @param bool $isimage
     * @param int $w
     * @param int $h
     * @param int $q
     * @return string
     */
    public static function completeUrl($path, $isimage = true, $w = 0, $h = 0, $q = 101)
    {
        if (!$path) {
            return '';
        }

        if (strpos($path, 'http') !== false) {
            return $path;
        }

        if ($isimage) {
            $params = [];
            if (defined('IN_FRONTEND') && strpos($path, 'format') === false) {
                $params[] = '/format';
                $pathExt = self::getPathExt($path);
                switch ($pathExt) {
                    case 'png':
                        $params[] = 'png';
                        break;
                    case 'gif':
                        $params[] = 'gif';
                        break;
                    case 'jpg':
                    default:
                        $params[] = 'jpg';
                        break;

                }
//                $params[] = 'jpg';
            }
            if ($w || $h) {
                $params[] = '/resize';
                if ($w && $h) {
                    $params[] = 'm_fill';
                }
                $params[] = "w_$w";
                $params[] = "h_$h";
                // $handle = "?x-oss-process=image/resize,w_$w,h_$h";
            }

            if ($q && $q <= 100) {
                $params[] = '/quality';
                $params[] = "q_$q";
            }

            if ($params) {
                $path .= '?x-oss-process=image' . implode(',', $params);
            }
        }

        $url = yii\helpers\Url::to(rtrim(static::getUrlPrefix(), '/') . '/' . ltrim($path, '/'), true);
        $url = str_replace(',/', '/', $url);
        return $url;
    }

    /**
     * @desc 附件域名+根路径
     * @return string
     */
    public static function getUrlPrefix()
    {
        return isset(Yii::$app->params['oss.cdn.host']) && Yii::$app->params['oss.cdn.host'] ? Yii::$app->params['oss.cdn.host'] : Yii::$app->params['oss.host'];
    }

    /**
     * 取文件扩展名
     */
    public static function getPathExt($path) {
        $pathInfo = pathinfo($path);
        return $pathInfo['extension'];
    }
}