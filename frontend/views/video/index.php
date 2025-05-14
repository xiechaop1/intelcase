<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/4
 * Time: 3:14 PM
 */

/**
 * @var \yii\web\View $this ;
 */

/**
 * @var \common\models\QA $qa
 */

\frontend\assets\Videoh5Asset::register($this);

$this->registerMetaTag([
    'name' => 'referrer',
    'content' => 'no-referrer',
]);
//$this->registerMetaTag([
//    'name' => 'viewport',
//    'content' => 'width=device-width; initial-scale=1.0',
//]);

?>

<div class="header">
  <div class="container">
    <img src="../../img/logo_yicun.png" class="img-logo">
  </div>
</div>
<!--       --><?php
//        if (!empty($musicVideo)) {
//
//            foreach ($musicVideo as $video) {
//
//                var_dump($video->toArray());
//                echo '<hr>';
//            }
//        }
//        ?>
<div class="video">
  <div class="container" style="margin-top: 0px">
    <div class="row">
      <div class="col-md-2">
        <div class="video-list-left">
          <!--视频封面 切换list-->
          <!--<div class="videolist active" vtype="mp4" vpath="v1.jpg" ipath="../../video/2_车控状态.mp4">
            <img src="../../img/example.png" class="img-auto">
            <img src="../../img/bg-play.png" class="icon-play">
          </div>
          <div class="videolist" vtype="MOV" vpath="v1.jpg" ipath="../../video/IMG_3722.MOV">
            <img src="../../img/example.png" class="img-auto">
            <img src="../../img/bg-play.png" class="icon-play">
          </div>
          <div class="videolist" vtype="avi" vpath="v1.jpg" ipath="../../video/Test.avi">
            <img src="../../img/example.png" class="img-auto">
            <img src="../../img/bg-play.png" class="icon-play">
          </div>-->

           <!--视频切换 切换list-->
          <?php
          $firstVideoUrl = '';
          if (!empty($musicVideo)) {

            $ct = 1;
            foreach ($musicVideo as $video) {
              if (strpos($video->video_url, ',') !== false) {
                $videoUrls = explode(',', $video->video_url);
                $videoInfos = json_decode($video->video_info, true);

                if (!empty($videoUrls)) {
                  if (empty($firstVideoUrl) && !empty($videoUrls[0])) {
                    $firstVideoUrl = \common\helpers\Attachment::completeUrl($videoUrls[0], false);
                  }
                  foreach ($videoUrls as $videoUrl) {
                    $videoInfo = !empty($videoInfos[$videoUrl]) ? $videoInfos[$videoUrl] : [];
                ?>
                    <div class="videolist2" vtype="<?= !empty($videoInfo['file_type']) ? $videoInfo['file_type'] : 'avi' ?>" vpath="<?= !empty($videoUrl) ? \common\helpers\Attachment::completeUrl($videoUrl, false) : '' ?>" ipath="<?= !empty($videoUrl) ? \common\helpers\Attachment::completeUrl($videoUrl, false) : '' ?>">
            <span>
              <?= !empty($video->video_title) ? $video->video_title : '视频' . $ct ?>
            </span>
                    </div>

                <?php
                    $ct++;
                  }
                }
              } else {
              if (empty($firstVideoUrl) && !empty($video->video_url)) {
                $firstVideoUrl = \common\helpers\Attachment::completeUrl($video->video_url, false);
              }

              ?>
              <div class="videolist2" vtype="<?= !empty($video->file_type) ? $video->file_type : 'avi' ?>" vpath="<?= !empty($video->video_url) ? \common\helpers\Attachment::completeUrl($video->video_url, false) : '' ?>" ipath="<?= !empty($video->video_url) ? \common\helpers\Attachment::completeUrl($video->video_url, false) : '' ?>">
            <span>
              <?= !empty($video->video_title) ? $video->video_title : '视频' . $ct ?>
            </span>
              </div>
                
              <?php
              $ct++;
              }
            }
          }
          ?>
          
<!--          <div class="videolist2" vtype="avi" vpath="视频封面地址" ipath="video/Test.avi">-->
<!--            <span>-->
<!--              视频名称视-->
<!--            </span>-->
<!--          </div>-->

        </div>

      </div>
      <div class="col-md-10">
        <div class="video-player videos">
          <video class="video_player_box" id="video"  src="<?= $firstVideoUrl ?>" preload="auto" controls="controls" autoplay="autoplay"></video>

        </div>
        <!--MP4，MOV，播放器-->
<!--        <div class="video-player hide">-->
<!--          <video id="video" class="videos video_player_box" poster="封面地址"  src="../../video/2_车控状态.mp4" preload="auto" controls="controls">-->
<!--          </video>-->
<!--        </div>-->
<!---->
        <!--webm，ogg 格式的视频播放器-->
<!--        <div class="video-player hide">-->
<!--          <video controls class="video_player_box">-->
<!--            <source src="../../video/Test.avi" type="video/webm">-->
<!--            <source src="../../video/Test.avi" type="video/ogg">-->
<!--          </video>-->
<!--        </div>-->
<!---->
        <!--AVI播放器-->
<!--        <div class="video-player hide">-->
<!--          <video controls class="video_player_box">-->
<!--            <source src="../../video/Test.avi" type="video/avi">-->
<!--          </video>-->
<!--        </div>-->


      </div>
    </div>

  </div>
</div>
