<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/17
 * Time: 3:24 PM
 */

namespace common\helpers;


class Time
{
    public static function minute2friendly($min, $d = '天', $h = '时', $m = '分')
    {
        $timeAry = [];
        $day = floor($min / 1440);
        if ($day > 0) {
            $timeAry[] = $day . $d;
        }
        $hour = floor($min % 1440 / 60);
        if ($hour) {
            $timeAry[] = $hour . $h;
        }
        $minute = (int)$min % 60;
        $timeAry[] = $minute . $m;

        return implode('', $timeAry);
    }

    public static function formatTimeFriendly($timeInt) {
        $minute = (int)$timeInt % 60;
        $second = $timeInt - $minute;

        return $minute . ':' . $second;
    }

    public static function formatTimeToInt($timeStr) {
        if (strpos($timeStr, ':') === false) {
            return $timeStr;
        }
        $timeAry = explode(':', $timeStr);
        if (sizeof($timeAry) == 2) {
            $minute = (int)$timeAry[0];
            $second = (int)$timeAry[1];
            return $minute * 60 + $second;
        } else {
            return 0;
        }


    }
}