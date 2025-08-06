<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\flow;


use common\definitions\Common;
use common\models\Project;
use common\models\Payment;
use common\models\Report;
use common\models\Staff;
use common\models\Subscribed;
use common\models\Visit;
//use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class StaffApi extends ApiAction
{
    public $action;
    private $_get;
    private $_projectId;
    private $_reportId;

    public function run()
    {
        try {
            if (Yii::$app->request->method == 'POST') {
                $this->_get = Yii::$app->request->post();
            } else {
                $this->_get = Yii::$app->request->get();
            }

            $this->valToken();
            switch ($this->action) {
                case 'add':
                    $ret = $this->add();
                    break;
                case 'update':
                    $ret = $this->update();
                    break;
                case 'get_by_id':
                    $ret = $this->getById();
                    break;
                case 'get_by_name':
                    $ret = $this->getByName();
                    break;
                case 'get_list':
                    $ret = $this->getList();
                    break;
                case 'get_rules':
                    $ret = $this->getRules();
                    break;
                case 'get_role_rules':
                    $ret = $this->getRoleRules();
                    break;
                default:
                    $ret = [];
                    break;

            }
        } catch (\Exception $e) {
            $ret = $this->fail($e->getCode() . ': ' . $e->getMessage());
        }

        return $ret;
    }

    public function getList() {
        $page = !empty($this->_get['page']) ? $this->_get['page'] : 1;
        $pageSize = !empty($this->_get['page_size']) ? $this->_get['page_size'] : 10;
        $role = !empty($this->_get['role']) ? $this->_get['role'] : 0;
        $team = !empty($this->_get['team']) ? $this->_get['team'] : '';
        $isTeam = !empty($this->_get['is_team']) ? $this->_get['is_team'] : 0;

        $query = Staff::find();
        if ($isTeam == 1) {
            $query->select('team');
        }
        if (!empty($role)) {
            $query = $query->where([
                'role' => [$role, Staff::STAFF_ROLE_ADMIN],
            ]);
        }
        if (!empty($team)) {
            $query = $query->andWhere([
                'team' => $team,
            ]);
        }
        $query = $query->andWhere([
            '<>', 'staff_status', Staff::STAFF_STATUS_DISABLE,
        ]);
        if ($isTeam == 1) {
            $query = $query->groupBy('team');
            $count = $query->count();
            $list = $query->all();
        } else {
            $query = $query->orderBy([
                'id' => SORT_DESC
            ]);

            $count = $query->count();
            $list = $query->offset(($page - 1) * $pageSize)->limit($pageSize)->all();
        }

        $ret = [];
        if (!empty($list)) {
            foreach ($list as $l) {
                $row = $l->toArray();
                $row['role_name'] = !empty(Staff::$staffRole2Name[$l->role]) ? Staff::$staffRole2Name[$l->role] : '未知角色';
                $ret[] = $row;
            }
        }

        return $this->success([
            'list' => $ret,
            'total_count' => $count,
        ]);
    }

    public function getByName() {

        $staffName = !empty($this->_get['staff_name']) ? $this->_get['staff_name'] : '';

        if (empty($staffName)) {
            return $this->fail('需要指定用户', -1000);
        }

        $model = Staff::find()
            ->where([
                'like', 'staff_name', $staffName,
            ])
            ->all();

        if (empty($model)) {
            return $this->fail('用户不存在', -1000);
        }

        $ret = [];
        if (!empty($model)) {
            foreach ($model as $l) {
                $row = $l->toArray();
                $row['role_name'] = !empty(Staff::$staffRole2Name[$l->role]) ? Staff::$staffRole2Name[$l->role] : '未知角色';
                $row['rules_json'] = !empty($row['rules_json']) ? json_decode($row['rules'], true) : [];
                $ret[] = $row;
            }
        }

        return $this->success($ret);
    }

    public function getById() {

        $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;

        if (empty($staffId)) {
            return $this->fail('需要指定用户', -1000);
        }

        $model = Staff::find()
            ->where([
                'id' => $staffId,
            ])
            ->one();

        if (empty($model)) {
            return $this->fail('用户不存在', -1000);
        }

        $roleName = !empty(Staff::$staffRole2Name[$model->role]) ? Staff::$staffRole2Name[$model->role] : '未知角色';
        $ret = $model->toArray();
        $ret['role_name'] = $roleName;
        $ret['rules_json'] = !empty($model['rules']) ? json_decode($model['rules'], true) : [];

        return $this->success($ret);
    }

    public function update() {

        $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
        if (empty($staffId)) {
            return $this->fail('需要指定用户', -1000);
        }

        $adminStaffId = !empty($this->_get['admin_staff_id']) ? $this->_get['admin_staff_id'] : 0;

        $staffName = !empty($this->_get['staff_name']) ? $this->_get['staff_name'] : '';
        $role = !empty($this->_get['role']) ? $this->_get['role'] : 0;
        $mobile = !empty($this->_get['mobile']) ? $this->_get['mobile'] : '';
        $wx_id = !empty($this->_get['wx_id']) ? $this->_get['wx_id'] : '';
        $staffStatus = !empty($this->_get['staff_status']) ? $this->_get['staff_status'] : 0;
        $team = !empty($this->_get['team']) ? $this->_get['team'] : '';
        $rules = !empty($this->_get['rules']) ? $this->_get['rules'] : [];

        $model = Staff::find()
            ->where([
                'id' => $staffId,
            ])
            ->one();

        if (!empty($rules)) {
            $admin = Staff::find()
                ->where([
                    'id' => $adminStaffId
                ])
                ->one();

            if (empty($admin)) {
                return $this->fail('请您制定操作人', -1000);
            }


            $nowRuleJson = json_decode($admin->rules, true);
            if (!empty($nowRuleJson)) {
                $needRule = Staff::STAFF_RULE_SET_RULE;
                if (in_array($needRule, $nowRuleJson)) {
                    if ($rules != 'clear') {
                        $rules = \common\helpers\Common::splitMobile($rules);
                        $rules = json_encode($rules, JSON_UNESCAPED_UNICODE);
                    } else {
                        $rules = json_encode([], JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    return $this->fail('您不能更改用户权限', -1000);
                }
            } else {
                return $this->fail('更改用户权限失败', -1000);
            }

        }

        if (empty($model)) {
            return $this->fail('用户不存在', -1000);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($staffName)) {
                $model->staff_name = $staffName;
            }
            if (!empty($role)) {
                $model->role = $role;
            }
            if (!empty($staffStatus)) {
                $model->staff_status = $staffStatus;
            }
            if (!empty($mobile)) {
                $model->mobile = $mobile;
            }
            if (!empty($team)) {
                $model->team = $team;
            }
            if (!empty($wx_id)) {
                $model->wx_id = $wx_id;
            }
            if (!empty($rules)) {
                $model->rules = $rules;
            }

            $model->save();

            $transaction->commit();

            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

    }

    public function add() {

        $model = new Staff();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $staffName = !empty($this->_get['staff_name']) ? $this->_get['staff_name'] : '';
            $role = !empty($this->_get['role']) ? $this->_get['role'] : Staff::STAFF_ROLE_SALES;
            $mobile = !empty($this->_get['mobile']) ? $this->_get['mobile'] : '';
//            $wx_id = !empty($this->_get['wx_id']) ? $this->_get['wx_id'] : '';
            $staffStatus = !empty($this->_get['staff_status']) ? $this->_get['staff_status'] : Staff::STAFF_STATUS_NORMAL;
            $team = !empty($this->_get['team']) ? $this->_get['team'] : '';
            $rules = !empty($this->_get['rules']) ? $this->_get['rules'] : [];

            if (empty($rules) && !empty(Staff::$staffRole2rule[$role])) {
                $rules = Staff::$staffRole2rule[$role];
            } else {
                $rules = \common\helpers\Common::splitMobile($rules);
            }

            $model->staff_name = $staffName;
            $model->role = $role;
            $model->mobile = $mobile;
            $model->team = $team;
            $model->rules = json_encode($rules, JSON_UNESCAPED_UNICODE);
//            $model->wx_openid = $wx_id;
            $model->staff_status = $staffStatus;

            $model->save();

            $transaction->commit();

            $staffId = $model->getPrimaryKey();
//            $staffId = Yii::$db->getLastInsertID();

            return $this->success([
                'staff_id' => $staffId,
                'staff' => $model,
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }

    public function getRules() {
        $rules = Staff::$staffRule2Name;

        return $this->success($rules);
    }

    public function getRoleRules() {
        $roleRules = Staff::$staffRole2rule;

        return $this->success($roleRules);
    }


}
