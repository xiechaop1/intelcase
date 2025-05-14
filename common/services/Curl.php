<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/27
 * Time: 下午4:33
 */

namespace common\services;

class Curl
{
    /**

     * 通过CURL发送HTTP请求

     * @param string $url  //请求URL

     * @param array $postFields //请求参数

     * @return mixed

     */

    public static function curlPost($url,$postFields, $header = array(), $isJson = false){

        if (!$isJson) {
            $postFields = http_build_query($postFields);
        } else {
            $postFields = json_encode($postFields);
            $header = [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($postFields)
            ];
        }

        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_POST, 1 );

        curl_setopt ( $ch, CURLOPT_HEADER, 0 );

        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );

        curl_setopt ( $ch, CURLOPT_URL, $url );

        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );

        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );

        $result = curl_exec ( $ch );

        curl_close ( $ch );

        return $result;

    }

    public static function curlGet($url){

        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_HEADER, 0 );

        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );

        curl_setopt ( $ch, CURLOPT_URL, $url );

        $result = curl_exec ( $ch );

        curl_close ( $ch );

        return $result;

    }
}
