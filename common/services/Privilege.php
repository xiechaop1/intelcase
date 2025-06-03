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
        ];

        $r = in_array($role, $tagMap[$tag]);


        return $r;
    }

}