<?php

namespace common\extensions\getid3;

use yii;

include_once(Yii::$app->basePath . '/../common/extensions/getid3/getid3/getid3.php');

class Service
{
    public function get($file)
    {
        $getID3 = new \getID3;
        $fileInfo = $getID3->analyze($file);
        $fileInfo['playtime_string'] = $fileInfo['playtime_string'] ?? '';
        $fileInfo['filesize'] = $fileInfo['filesize'] ?? '';
        $fileInfo['mime_type'] = $fileInfo['mime_type'] ?? '';
        $fileInfo['audio']['bitrate'] = $fileInfo['audio']['bitrate'] ?? '';
        $fileInfo['audio']['sample_rate'] = $fileInfo['audio']['sample_rate'] ?? '';
        $fileInfo['audio']['channels'] = $fileInfo['audio']['channels'] ?? '';
        $fileInfo['audio']['bits_per_sample'] = $fileInfo['audio']['bits_per_sample'] ?? '';
        $fileInfo['audio']['encoder_options'] = $fileInfo['audio']['encoder_options'] ?? '';
        $fileInfo['audio']['lossless'] = $fileInfo['audio']['lossless'] ?? '';
        $fileInfo['audio']['compression_ratio'] = $fileInfo['audio']['compression_ratio'] ?? '';
        $fileInfo['audio']['streams'] = $fileInfo['audio']['streams'] ?? '';
        $fileInfo['audio']['dataformat'] = $fileInfo['audio']['dataformat'] ?? '';
        $fileInfo['audio']['sample_rate'] = $fileInfo['audio']['sample_rate'] ?? '';
        $fileInfo['audio']['channelmode'] = $fileInfo['audio']['channelmode'] ?? '';
        $fileInfo['audio']['bitrate_mode'] = $fileInfo['audio']['bitrate_mode'] ?? '';
        $fileInfo['audio']['lossless'] = $fileInfo['audio']['lossless'] ?? '';
        $fileInfo['audio']['encoder'] = $fileInfo['audio']['encoder'] ?? '';
        $fileInfo['audio']['encoder_options'] = $fileInfo['audio']['encoder_options'] ?? '';
        $fileInfo['audio']['compression_ratio'] = $fileInfo['audio']['compression_ratio'] ?? '';
        $fileInfo['audio']['streams'] = $fileInfo['audio']['streams'] ?? '';
        $fileInfo['audio']['dataformat'] = $fileInfo['audio']['dataformat'] ?? '';
        $fileInfo['audio']['sample_rate'] = $fileInfo['audio']['sample_rate'] ?? '';
        $fileInfo['audio']['channelmode'] = $fileInfo['audio']['channelmode'] ?? '';
        return $fileInfo;
    }
}