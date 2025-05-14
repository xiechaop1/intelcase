<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 10:10 PM
 */

namespace common\models;


use yii\web\NotFoundHttpException;

class Admin extends \common\models\gii\Admin
{

//    public $name;
//    public $password;
    public $adminId;

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    public function exec() {
        if ($this->validate()) {
            if ($this->adminId) {
                $admin = Admin::findOne($this->adminId);
                if (!$admin) {
                    throw new NotFoundHttpException();
                }
            } else {
                $admin = new Admin();
            }

            if ($this->adminId) {
                if ($this->password != $admin->password) {
                    $this->password = \Yii::$app->security->generatePasswordHash($this->password);
                }
            } else {
                $this->password = \Yii::$app->security->generatePasswordHash($this->password);
            }

            $ret = $this->save();
        }
        return $ret;
    }

}