<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Project extends \common\models\gii\Project
{

    const PROJECT_CLASS_DEFAULT       = 0;      // 默认

    const PROJECT_TAR_PRODUCE_SHOP    = 1;      // 商铺

    public function behaviors()
    {

        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function getPmStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'pm_staff_id']);
    }

    public function getConsultantStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'consultant_staff_id']);
    }

    public function getAdvisorStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'advisor_staff_id']);
    }

    public function getFinancialStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'financial_staff_id']);
    }




}