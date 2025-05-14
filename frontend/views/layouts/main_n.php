<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

//AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="音乐超市">
    <meta name="description" content="音乐超市">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>
<div class="w-100">

    <?= $content ?>




</div>

<?php $this->endBody() ?>
<?php if( !YII_DEBUG): ?>
    <div style="display: none;">
        <script style="display: none;" type="text/javascript" src="https://s23.cnzz.com/z_stat.php?id=1277640552&web_id=1277640552"></script>
    </div>
<?php endif ?>
</body>
</html>
<?php $this->endPage() ?>
