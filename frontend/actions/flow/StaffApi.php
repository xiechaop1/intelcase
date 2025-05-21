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
            $this->_get = Yii::$app->request->get();

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

        $query = Staff::find()
            ->orderBy([
                'id' => SORT_DESC
            ]);

        $count = $query->count();
        $list = $query->offset(($page - 1) * $pageSize)->limit($pageSize)->all();

        return $this->success([
            'list' => $list,
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
            ->one();

        if (empty($model)) {
            return $this->fail('用户不存在', -1000);
        }

        return $this->success($model);
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

        return $this->success($model);
    }

    public function update() {

        $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;
        if (empty($staffId)) {
            return $this->fail('需要指定用户', -1000);
        }

        $staffName = !empty($this->_get['staff_name']) ? $this->_get['staff_name'] : '';
        $role = !empty($this->_get['role']) ? $this->_get['role'] : 0;
        $staffStatus = !empty($this->_get['staff_status']) ? $this->_get['staff_status'] : 0;

        $model = Staff::find()
            ->where([
                'id' => $staffId,
            ])
            ->one();

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
            $staffStatus = !empty($this->_get['staff_status']) ? $this->_get['staff_status'] : Staff::STAFF_STATUS_NORMAL;

            $model->staff_name = $staffName;
            $model->role = $role;
            $model->staff_status = $staffStatus;

            $model->save();

            $transaction->commit();

            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();
//            Yii::$app->oplog->write(\common\models\Log::OP_CODE_VIEW, \common\models\Log::OP_STATUS_FAILED, $this->_userId, $this->_musicId, '用户浏览', json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
            return $this->fail('操作失败', -1000);
        }

    }


}