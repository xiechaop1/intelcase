<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/13
 * Time: 10:54 AM
 */

use common\models\Tag;
use common\definitions\Common;

$articles = Yii::$app->article->fetchInPosition(\common\definitions\Article::IN_SEARCH, 3);
$tags = Tag::findAll(['is_recommend' => Common::ENABLE]);

?>

<!-- 搜索模态框 -->
<!-- 最后加一个search.js -->
<div id="searchModal"  class="modal-content h-100  bg-00-40  d-none z-9999 fixed top-0">
    <div class="w-100 m-height-100 bg-00-40 d-flex justify-content-center pt-5 p-0 overflow-auto">
        <div class="w-1200 text-F6">
            <div class="d-flex align-items-center mt-5 flex-column">

                <div class="close align-self-end" data-dismiss="modal">
                    <span class="iconfont iconguanbi1 text-FF fs-30 bold"></span>
                </div>
                <div class="fs-34">搜 索</div>
                <div class="mt-5">
                    <div class="w-750 border-F6 search">
                        <form action="/search/result" name="search" class="d-flex align-items-center"
                                target="_blank">
                            <input class="text-F6 p-2 fs-20 col-11" type="text" name="kw" autocomplete="off">
                            <a class="pointer submit iconfont iconsearch fs-22 ml-3"></a>
                        </form>
                    </div>
                    <ul class="fs-14 align-self-start d-flex p-0 align-items-center relative pt-3">
                        <li>热门搜索：</li>
                        <?php
                        foreach ($tags as $tag) {
                            ?>
                            <a href="/search/tag/<?= $tag->id ?>"
                                class="px-2 mx-1 border-F6 rounded-20 text-F6" target="_blank"><?= $tag->name ?></a>
                            <?php
                        }
                        ?>
                        <ul class="z-index-1 opacity-0 think_search bg-FF absolute top-0 w-70 ml-1 list-group list-group-flush border">
                        </ul>
                    </ul>
                </div>  
            </div>
            <div class="col-12 fs-22 text-center mt-5 mb-4">
                <a href="" class="pl-3 py-2 mt-1 text-F6">推荐阅读</a>
            </div>
            <div class="d-flex justify-content-between pb-5">
                <?php
                foreach ($articles as $article) {
                    ?>
                    <a href="/lifeinuk/<?= $article->id ?>" class="bg-FF border-F0 w-390 card">
                        <div class="img-w-388">
                            <img class="img-w-388" src="<?= \common\helpers\Attachment::completeUrl($article->logo, true, 388, 220) ?>"
                                    alt="">
                        </div>
                        <div class="p-3 text-F6 text-center">
                            <div class="text-over" data-toggle="tooltip" data-placement="bottom" title="<?= $article->title ?>">
                              <span>
                                <?= $article->title ?>
                              </span>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>