<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/5
 * Time: 3:49 PM
 */

namespace frontend\actions\video;


use backend\models\MusicVideo;
use yii\base\Action;
use yii;

class Index extends Action
{
    private $_get;
    public function run()
    {
        $this->_get = Yii::$app->request->get();

        if (!empty($this->_get['music_title'])) {
            $musicTitle = $this->_get['music_title'];
        } else {
            return $this->renderErr('请您给出音乐标题');
        }

        $musicVideo = MusicVideo::find()
            ->where([
                'music_title' => $musicTitle,
                'is_delete' => MusicVideo::VIDEO_IS_DELETE_NO,
            ])
            ->all();

        if (empty($musicVideo)) {
            return $this->renderErr('您指定的歌曲，暂时没有对应视频信息');
        }

        return $this->controller->render('index', [
            'musicVideo' => $musicVideo,
        ]);
    }

    public function renderErr($errTxt) {
        return $this->controller->render('msg', [
            'msg' => $errTxt,
        ]);
    }
}