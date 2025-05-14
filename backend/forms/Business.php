<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午5:30
 */

namespace backend\forms;


use common\models\Admin;
use common\models\BusinessProfile;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class Business extends Model
{
    public $businessId;

    public $name;

    public $mobileSection;

    public $mobile;

    public $email;

    public $password;

    public $role;

    public $address;

    public $licence;

    public function rules()
    {
        return [
            [['name', 'mobileSection', 'mobile', 'email', 'password', 'role'], 'required'],
            [['businessId'], 'integer'],
            [['address'], 'string', 'max' => 128],
            [['licence'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => 'common\models\Admin', 'targetAttribute' => ['name' => 'name'], 'filter' => function ($query) {
                $query->andWhere(['<>', 'id', (int)$this->businessId]);
            }],
            [['email'], 'unique', 'targetClass' => 'common\models\Admin', 'targetAttribute' => ['email' => 'email'], 'filter' => function ($query) {
                $query->andWhere(['<>', 'id', (int)$this->businessId]);
            }],
            [['mobileSection', 'mobile'], 'unique', 'targetClass' => 'common\models\Admin', 'targetAttribute' => ['mobileSection' => 'mobile_section', 'mobile' => 'mobile'], 'filter' => function ($query) {
                $query->andWhere(['<>', 'id', (int)$this->businessId]);
            }]
        ];
    }

    public function exec()
    {
        if ($this->validate()) {
            if ($this->businessId) {
                $business = Admin::findOne($this->businessId);
                if (!$business) {
                    throw new NotFoundHttpException();
                }
            } else {
                $business = new Admin();
            }

            $business->name = $this->name;
            $business->mobile_section = $this->mobileSection;
            $business->mobile = $this->mobile;
            $business->email = $this->email;
            if ($this->businessId) {
                if ($this->password != $business->password) {
                    $business->password = \Yii::$app->security->generatePasswordHash($this->password);
                }
            } else {
                $business->password = \Yii::$app->security->generatePasswordHash($this->password);
            }
            $business->role = $this->role;

            if (!$business->save()) {
                \Yii::warning('Save admin fail');
                \Yii::warning($business->errors);

                return false;
            }

            $profile = $business->businessProfile;
            if (!$profile) {
                $profile = new BusinessProfile(['id' => $business->id]);
            }

            $profile->address = $this->address;
            $profile->licence = $this->licence;
            if (!$profile->save()) {
                \Yii::warning('Save business profile fail');
                \Yii::warning($profile->errors);
            }
            
            return true;

        } else {
            \Yii::warning('Exec business fail');
            \Yii::warning(json_encode($this->errors));

            return false;
        }
    }
}