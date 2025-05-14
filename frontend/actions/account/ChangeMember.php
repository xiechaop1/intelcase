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
use yii;

class ChangeMember extends ApiAction
{
    public function run()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            /**
             * @var MemberIdentity $identity
             */
            $identity = Yii::$app->user->identity;

            $post = Yii::$app->request->post();
            $identity->load($post, '');

            $r = $identity->exec();

            if ($r) {
                Yii::$app->zhuge->setUserPoint('personality_modify_success');

                return $this->success();
            } else {
                return $this->fail(current($identity->getFirstErrors()));
            }
        }

    }
}