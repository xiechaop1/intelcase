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
use common\models\Subscribed;
use common\models\Visit;
//use common\services\Log;
use frontend\actions\ApiAction;
use Yii;

class ProjectApi extends ApiAction
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
                case 'get_by_id':
                    $ret = $this->getById();
                    break;
                case 'update':
                    $ret = $this->update();
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

    public function getById()
    {

        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;

        if (empty($projectId)) {
            return $this->fail('需要指定项目', -1000);
        }

        $model = Project::find()
            ->where([
                'id' => $projectId,
            ])
            ->one();

        if (empty($model)) {
            return $this->fail('项目不存在', -1000);
        }

        return $this->success($model);
    }

    public function update() {
        $projectId = !empty($this->_get['project_id']) ? $this->_get['project_id'] : 0;
        if (empty($projectId)) {
            return $this->fail('需要指定项目', -1000);
        }
        $model = Project::find()
            ->where([
                'id' => $projectId,
            ])
            ->one();
        if (empty($model)) {
            return $this->fail('项目不存在', -1000);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $projectName = !empty($this->_get['project_name']) ? $this->_get['project_name'] : '';
            $opts = !empty($this->_get['opts']) ? $this->_get['opts'] : '';
            $projectClass = !empty($this->_get['project_class']) ? $this->_get['project_class'] : '';
            $targetProduct = !empty($this->_get['target_product']) ? $this->_get['target_product'] : '';
            $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;

            if (!empty($projectName)) {
                $model->project_name = $projectName;
            }
            if (!empty($opts)) {
                $model->opts = $opts;
            }
            if (!empty($projectClass)) {
                $model->project_class = $projectClass;
            }
            if (!empty($targetProduct)) {
                $model->target_product = $targetProduct;
            }
            if (!empty($staffId)) {
                $model->staff_id = $staffId;
            }


            if (!$model->save()) {
                return $this->fail('操作失败', -1000);
            }

            $transaction->commit();

            return $this->success('操作成功');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->fail('操作失败', -1000);
        }
    }

    public function add() {

        $model = new Project();

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $projectName = !empty($this->_get['project_name']) ? $this->_get['project_name'] : '';
            $opts = !empty($this->_get['opts']) ? $this->_get['opts'] : '';
            $projectClass = !empty($this->_get['project_class']) ? $this->_get['project_class'] : '';
            $targetProduct = !empty($this->_get['target_product']) ? $this->_get['target_product'] : '';
            $staffId = !empty($this->_get['staff_id']) ? $this->_get['staff_id'] : 0;

            $model->project_name = $projectName;
            $model->opts = $opts;
            $model->project_class = $projectClass;
            $model->target_product = $targetProduct;
            $model->staff_id = $staffId;



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