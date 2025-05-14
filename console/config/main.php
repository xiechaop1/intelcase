<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'Asia/Shanghai',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
//        'order' => [
//            'class' => 'yii\console\controllers\OrderController',
//            'namespace' => 'common\order',
//        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning'],
                ],
            ],
        ],
        'invoice' => [
            'class' => 'common\services\Invoice',
            'logoPath' => Yii::getAlias('@common/resources/images/logo_travel.png'),
            'chineseFontPath' => Yii::getAlias('@common/resources/fonts/DroidSansFallback.ttf')
        ],
    ],
    'params' => $params,
];
