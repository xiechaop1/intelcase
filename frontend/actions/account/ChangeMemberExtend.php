<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/7
 * Time: 下午10:56
 */

namespace frontend\actions\account;


use frontend\models\MemberIdentity;
use liyifei\base\actions\ApiAction;
use liyifei\base\helpers\Net;
use common\models\MemberSpecial;
use yii\base\UserException;
use yii;

class ChangeMemberExtend extends ApiAction
{
    public $action = 'special_tags';

    public function run()
    {
        if ((Yii::$app->request->isAjax && Yii::$app->request->isPost) || 1 == 1) {
            /**
             * @var MemberIdentity $identity
             */
            $identity = Yii::$app->user->identity;

            $post = Yii::$app->request->post();


            $r = false;
            try {
                if ($this->action == 'special_tags') {
                    if (!empty($post['special'])) {
                        $r = $this->_updateSpecial($post['special'], MemberSpecial::TYPE_USER_SPECIAL);
                    }
                } else if ($this->action == 'member_industry') {
                    if (!empty($post['special'])) {
                        $r = $this->_updateSpecial($post['special'], MemberSpecial::TYPE_USER_INDUSTRY);
                    } else {
                        return $this->fail('您没有选择行业');
                    }
                } else if ($this->action == 'member_city') {
                    if (!empty($post['special'])) {
                        $r = $this->_updateSpecial($post['special'], MemberSpecial::TYPE_USER_CITY);
                    } else {
                        return $this->fail('您没有选择城市');
                    }
                } else if ($this->action == 'member_post') {
                    if (!empty($post['special'])) {
                        $r = $this->_updateSpecial($post['special'], MemberSpecial::TYPE_USER_POST);
                    } else {
                        return $this->fail('您没有选择职业');
                    }
                }
                if ($r) {
                    Yii::$app->zhuge->setUserPoint('personality_modify_success');
                    return $this->success();
                } else {
                    return $this->fail('未知错误');
                }
            } catch (UserException $e) {
                return $this->fail($e->getMessage());
            }
        }

        return $this->success();

    }

    private function _updateSpecial($data, $type = MemberSpecial::TYPE_USER_SPECIAL) {
        $userId = Yii::$app->user->id;
        $transaction = Yii::$app->db->beginTransaction();
        MemberSpecial::deleteAll(['user_id' => $userId, 'special_type' => $type]);

        $r = true;
        foreach ($data as $msTag) {
            $memberSpecialModel = new MemberSpecial();
            $memberSpecialModel->user_id = $userId;
            $memberSpecialModel->tag_id = $msTag;
            $memberSpecialModel->special_type = $type;
            if (!$memberSpecialModel->save()) {
//                        var_dump($memberSpecialModel->getFirstErrors());
                $transaction->rollBack();

                Yii::warning('Create member ' . $type . ' fail');
                Yii::warning(json_encode($memberSpecialModel->errors));

                $r = false;
                throw new UserException(current($memberSpecialModel->getFirstErrors()));

            }
        }
        if ($r) {
            $transaction->commit();
        }

        return $r;

    }
}