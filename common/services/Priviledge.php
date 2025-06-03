<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/2/20
 * Time: 2:12 PM
 */

namespace common\services;


use common\models\Staff;
use common\services\Curl;
use common\models\User;
use yii\base\Component;
use yii;

class Priviledge extends Component
{

    public function check($role, $tag) {

        $r = "";

        $tagMap = [
            'add_project' => [
                Staff::STAFF_ROLE_ADMIN,
            ],
        ];


        return $r;
    }

}