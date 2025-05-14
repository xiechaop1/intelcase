<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/1
 * Time: 3:17 PM
 */

namespace frontend\models;


use common\models\Member;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use yii;

class MemberIdentity extends Member implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return '';
        throw new NotSupportedException();
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
        throw new NotSupportedException();
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
//        var_dump( Yii::$app->security->generatePasswordHash($password));
//        var_dump( Yii::$app->security->generatePasswordHash($this->password));exit;
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}