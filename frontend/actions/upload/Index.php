<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/5
 * Time: 3:49 PM
 */

namespace frontend\actions\upload;


use common\services\Upload;
use common\extensions\Uploader;
use liyifei\base\actions\ApiAction;
use yii;

class Index extends ApiAction
{
    public $dirPrefix;

    public function run()
    {
        return $this->_upload($this->dirPrefix);
    }

    public function _upload($dirPrefix){

        $config['accessKeyId'] = Yii::$app->params['oss.accesskeyid'];
        $config['accessKeySecret'] = Yii::$app->params['oss.accesskeysecret'];
        $config['endpoint'] = Yii::$app->params['oss.endpoint'];
        $config['bucket'] = Yii::$app->params['oss.bucket'];

        $up = new Uploader('file', $config);

        var_dump($up->getFileInfo());
exit;
        var_dump($_REQUEST);

//        file_put_contents('/tmp/uploadreq.log', var_export($_REQUEST, true), FILE_APPEND);
//
//        if (Yii::$app->request->isPost) {
//            var_dump($_REQUEST);
//            $needDate = true;
//            $fileArray = Yii::$app->upload->upload($dirPrefix);
//            if (empty($fileArray['file_model']) || $fileArray['file_model']->hasError == 0 ) {
//                $fileTruePath = $fileArray['file_path'];
////                $uploadShowDir = 'http://file.hewa.cn/';
////                $uploadShowDir = Yii::$app->upload->uploadShowDir . $dirPrefix . '/';
//                $subFilePath = $dirPrefix . '/';
//                if ($needDate) {
////                    $uploadShowDir .= date('Ymd') . '/';
//                    $subFilePath .= date('Ymd') . '/';
//                }
//                $uploadShowDir = Yii::$app->upload->uploadShowDir . $subFilePath;
//                $fileName = Yii::getAlias($uploadShowDir . $fileArray['file_name']);
//
//                $fileArray['file_path'] = $fileName;
//
//                $data = [
//                    'file_array' => $fileArray,
//                    'file_name' => $fileName,
//                    'sub_file_name' => $subFilePath . $fileArray['file_name'],
//                    'file_true_path' => $fileTruePath,
//                ];
//
//                return $this->success($data);
//            } else {
//                return $this->fail(Yii::t('web', 'upload failed'));
//            }
//        } else {
//            return $this->controller->render('upload', [
//            ]);
//        }

    }
}