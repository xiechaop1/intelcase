<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Report extends \common\models\gii\Report
{

    const REPORT_STATUS_PASS         = 1;
    const REPORT_STATUS_INVALID      = 2;

    public $reportStatus2Name = [
        self::REPORT_STATUS_PASS     => '有效',
        self::REPORT_STATUS_INVALID  => '无效',
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