<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/24
 * Time: 10:27 AM
 */

namespace common\helpers;

use common\models\Messages;
use common\models\Admin;
use common\models\User;
use liyifei\chinese2pinyin\Chinese2pinyin;
use liyifei\DirectMail\Mail;
use Yii;
use yii\validators\EmailValidator;

class Music
{
    public static function transUserTypeToMusicType($userType) {
        switch ($userType) {
            case User::USER_TYPE_INNER:
                $musicType = \common\models\Music::MUSIC_TYPE_STATIC;
                break;
            case User::USER_TYPE_NORMAL:
            default:
                $musicType = \common\models\Music::MUSIC_TYPE_NORMAL;
                break;
        }
        return $musicType;
    }

    public static function formatLyricToJson($lyric) {
        $lyricPreg = '/\[(\d{2}:\d{2}.\d{2})\](.*)/';
        if (empty($lyric)) {
            return json_encode([], true);
        }
        $lyric = str_replace("\r", '', $lyric);

        $lyricTxt = $lyric;
        $lyricLines = explode("\n", $lyricTxt);
        if (!empty($lyricLines) ) {
            foreach ($lyricLines as $ly) {
                if (strpos($ly, '[') === false) {
                    if (strpos($ly, ',') !== false) {
                        $lyArray = explode(',', $ly);
                        if (count($lyArray) == 2) {
                            $lyricArray[] = [
                                'time' => $lyArray[0],
                                'text' => $lyArray[1],
                            ];
                        } else {
                            $lyricArray[] = [];
                        }
                    } else {
                        $lyricArray[] = $ly;
                    }
                    continue;
                }
                preg_match_all($lyricPreg, $ly, $lyMatch);
                if (!empty($lyMatch[1]) && !empty($lyMatch[2])) {
                    $lyricArray[] = [
                        'time' => $lyMatch[1][0],
                        'text' => $lyMatch[2][0],
                    ];
                } else {
                    $lyricArray[] = [];
                }
            }
        } else {
            $lyricArray = [];
        }
        return json_encode($lyricArray, true);
    }

    public static function formatLyricToTxt($lyric) {
        $lyricPreg = '/\[(\d{2}:\d{2}.\d{2})\](.*)/';
        if (empty($lyric)) {
            return '';
        }
        $lyric = str_replace("\r", '', $lyric);

        $lyricTxt = $lyric;
        $lyricLines = explode("\n", $lyricTxt);
        $lyricArray = [];
        if (!empty($lyricLines) ) {
            foreach ($lyricLines as $ly) {
                if (strpos($ly, '[') === false) {
                    if (strpos($ly, ',') !== false) {
                        $lyArray = explode(',', $ly);
                        if (count($lyArray) == 2) {
                            $lyricArray[] = '[' . $lyArray[0] . ']' . $lyArray[1];
                        } else {
                            $lyricArray[] = '';
                        }
                    } else {
                        $lyricArray[] = $ly;
                    }
                    continue;
                }
//                preg_match_all($lyricPreg, $ly, $lyMatch);
//                if (!empty($lyMatch[1]) && !empty($lyMatch[2])) {
//                    $lyricArray[] = [
//                        'time' => $lyMatch[1][0],
//                        'text' => $lyMatch[2][0],
//                    ];
//                } else {
//                    $lyricArray[] = [];
//                }
            }
        } else {
            $lyricArray = [];
        }
        return implode(PHP_EOL, $lyricArray);
    }

    public static function formatSource($music) {
        $music['cover_image'] = \common\helpers\Attachment::completeUrl($music['cover_image']);
        $music['cover_thumbnail'] = \common\helpers\Attachment::completeUrl($music['cover_thumbnail']);
        $music['verse_url'] = \common\helpers\Attachment::completeUrl($music['verse_url'], false);
        $music['chorus_url'] = \common\helpers\Attachment::completeUrl($music['chorus_url'], false);
        $music['lyric_url'] = \common\helpers\Attachment::completeUrl($music['lyric_url'], false);

        $music['background_image'] = \common\helpers\Attachment::completeUrl($music['background_image']);

        $music['chorus_start_time_int'] = !empty($music['chorus_start_time']) ? Time::formatTimeToInt($music['chorus_start_time']) : '';
        $music['chorus_end_time_int'] = !empty($music['chorus_end_time']) ? Time::formatTimeToInt($music['chorus_end_time']) : '';

        // format duration to int
        $music['duration_int'] = !empty($music['duration']) && strpos($music['duration'], ':') !== false ? Time::formatTimeToInt($music['duration']) : $music['duration'];
        $music['resource_download_file'] = \common\helpers\Attachment::completeUrl($music['resource_download_file'], false);
        return $music;
    }

    public static function formatImage($image) {
        $img = \common\helpers\Attachment::completeUrl($image);
        return $img;
    }

    public static function formatCategoryImage($category) {
        $category['category_image'] = \common\helpers\Attachment::completeUrl($category['category_image']);
        return $category;
    }

}