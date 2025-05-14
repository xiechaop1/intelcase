<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/1
 * Time: 下午4:06
 */

namespace backend\actions\member;


use common\models\Member;
use liyifei\base\actions\ApiAction;

class Autocomplete extends ApiAction
{
    public function run($q)
    {
        $query = Member::find();

        if ($q) {
            $query->where([
                'or',
                ['like', 'mobile', $q],
                ['like', 'email', $q]
            ]);
        }

        $query->limit(20);

        $members = $query->all();


        $data = [];
        if ($members) {
            $data = array_map(function (Member $member) {
                return [
                    'id' => $member->id,
                    'text' => "{$member->username} ({$member->mobile_section} {$member->mobile}, {$member->email})"
                ];
            }, $members);
        }

        return $this->success($data);
    }
}