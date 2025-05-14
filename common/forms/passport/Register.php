<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/22
 * Time: 2:25 PM
 */

namespace common\forms\passport;


use yii\base\Model;


/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $password
 * @property string $avatar 头像
 * @property int $type 1总管理员 2管理员
 * @property int $created_at
 * @property int $updated_at
 */
class Register extends Model
{
    public function rules()
    {
        return [
            [['id', 'name'], 'required']
        ];
    }
}