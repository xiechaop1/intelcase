<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/09/27
 * Time: 下午4:33
 */

namespace common\services;

use yii\base\Component;
use yii;
use OSS\OssClient;
use yii\httpclient\Client;


class Upload extends Component
{

    public function uploadToOss($content, $object = '')
    {
//        $client = new Client();
//        $request = $client->createRequest();
//        $request->setMethod('GET');
//        $request->addHeaders(['user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36']);
//        $request->setOptions([
//            'timeout' => 5,
//            'sslVerifyPeer' => false
//        ]);
        if (empty($object)) {
            $object = $this->createFileObject(1, 'image');
        }
        $oss = new OssClient(Yii::$app->params['oss.accesskeyid'], Yii::$app->params['oss.accesskeysecret'], Yii::$app->params['oss.endpoint'], false);

        try {
            $oss->putObject(Yii::$app->params['oss.bucket'], $object, $content);
//            $tag = str_replace($match[2], Yii::$app->params['oss.host'] . '/' . $object, $tag);
        } catch (\Exception $e) {

        }

        return $content;
    }

    public function uploadFileToOss($url, $file) {
        $ossClient = new OssClient();
        $object = $this->createFileObject(1, 'image');
        $ossClient->uploadFile(Yii::$app->params['oss.bucket'], $object, $file);
    }

    public function uploadFileContentToOss($url, $object = '') {
        $client = new Client();
        $request = $client->createRequest();
        $request->setMethod('GET');
        $request->addHeaders(['user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36']);
        $request->setOptions([
            'timeout' => 5,
            'sslVerifyPeer' => false
        ]);


        if (empty($object)) {
            $object = $this->createFileObject(1, 'image');
        }


        $request->setUrl($url);
        $response = $request->send();
        if ($response->isOk) {
            $body = $response->getContent();
            return $this->uploadToOss($body, $object);
        }
    }

    public function createFileObject($dateFlag = 0, $prefix = '', $fileExt = '.jpg') {
        $filePath = $prefix;
        if ($dateFlag == 1) {
            $filePath .= '/' . date('Ymd', time());
        }
        $fileName = (sprintf('%.3f', microtime(true)) * 1000) . rand(100000, 999999) . $fileExt;
        return $filePath . '/' . $fileName;
    }

}