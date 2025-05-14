<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/5/19
 * Time: 8:29 PM
 */

namespace common\models;


class SearchHistory extends \common\models\gii\SearchHistory
{
    const SEARCH_HISTORY_KEYWORD_MAX    = 5;
    const SEARCH_HISTORY_URI_MAX        = 3;

    const SEARCH_HISTORY_TYPE_URI       = 1;
    const SEARCH_HISTORY_TYPE_KEYWORD   = 2;

    public static $searchHistoryType2Name = [
        self::SEARCH_HISTORY_TYPE_URI       => 'URI',
        self::SEARCH_HISTORY_TYPE_KEYWORD   => '关键词',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }
}