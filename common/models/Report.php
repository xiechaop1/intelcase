<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Report extends \common\models\gii\Report
{

    const REPORT_STATUS_PASS         = 1;
    const REPORT_STATUS_INVALID      = 2;

    public static $reportStatus2Name = [
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

    public function getLastReports()
    {
        return $this->hasMany(Report::className(), ['guest_mobile' => 'guest_mobile']);
    }

    public function getStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'staff_id']);
    }

    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    public function getConsultantStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'consultant_staff_id']);
    }

    public function getAdvisorStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'advisor_staff_id']);
    }


}