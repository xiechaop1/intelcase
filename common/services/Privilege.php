<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/2/20
 * Time: 2:12 PM
 */

namespace common\services;


use common\models\Staff;
use yii\base\Component;
use yii;

class Privilege extends Component
{

    public function check($role, $tag) {

        $r = "";

        $tagMap = [
            \common\definitions\Privilege::PROJECT_ADD => [
                Staff::STAFF_ROLE_ADMIN,
            ],
            \common\definitions\Privilege::REPORT_CONFIRM => [
                Staff::STAFF_ROLE_ADMIN,
                Staff::STAFF_ROLE_PM,
            ],
            \common\definitions\Privilege::VISIT_INFO_CONFIRM => [
                Staff::STAFF_ROLE_ADMIN,
                Staff::STAFF_ROLE_ADVISOR,
                Staff::STAFF_ROLE_CONSULTANT
            ],
            \common\definitions\Privilege::VISIT_CONFIRM => [
                Staff::STAFF_ROLE_ADMIN,
                Staff::STAFF_ROLE_PM
            ],
            \common\definitions\Privilege::SUB_CONFIRM_SIGN => [
                Staff::STAFF_ROLE_ADMIN,
                Staff::STAFF_ROLE_PM,
                Staff::STAFF_ROLE_ADVISOR,
            ],
            \common\definitions\Privilege::SUB_CONFIRM_DEAL => [
                Staff::STAFF_ROLE_ADMIN,
                Staff::STAFF_ROLE_PM,
                Staff::STAFF_ROLE_FINANCE,
            ],
            \common\definitions\Privilege::PAYMENT_CONFIRM => [
                Staff::STAFF_ROLE_ADMIN,
                Staff::STAFF_ROLE_PM,
                Staff::STAFF_ROLE_FINANCE
            ],
        ];

        $r = in_array($role, $tagMap[$tag]);


        return $r;
    }

    public function getTeamStaff($team, $role = 0) {
        if (!empty($team)) {
            $staff = Staff::find()
                ->where(['team' => $team]);
            if (!empty($role)) {
                $roles = [
                    $role,
                    Staff::STAFF_ROLE_ADMIN
                ];
                $staff = $staff->andFilterWhere(['role' => $roles]);
            }
            $staff = $staff->andFilterWhere(['<>', 'status', Staff::STAFF_STATUS_DISABLE])
                ->orderBy('rand()')
                ->one();

            return $staff;

        } else {
            return [];
        }
    }

    public function checkStaffTeam($staffId, $team) {
        $staff = Staff::find()
            ->where(['id' => $staffId])
            ->andFilterWhere(['team' => $team])
            ->andWhere(['<>', 'status', Staff::STAFF_STATUS_DISABLE])
            ->one();

        if (empty($staff)) {
            return false;
        }

        return true;
    }

    public function checkByUser($user, $tag) {
        $role = !empty($user->role) ? $user->role : '';

        $ret = $this->check($role, $tag);
        if ($ret === false) {
            throw new yii\base\Exception('您没有权限操作',403);
        }
        return $ret;
    }

}