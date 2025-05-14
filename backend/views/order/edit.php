<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/29
 * Time: 下午8:32
 */

use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = [
    'label' => '订单管理',
];
use yii\web\JsExpression;

$this->title = '订单管理';
echo \dmstr\widgets\Alert::widget();

?>


    <div class="box box-primary">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            $form = \yii\bootstrap\ActiveForm::begin([
                'layout' => 'horizontal',
                'enableClientValidation' => true,
            ]);
            echo $form->field($orderModel, 'id')->textInput(['value' => $orderModel->id, 'readonly' => true])->label('ID');
            echo $form->field($orderModel, 'music_title')->textInput(['value' => $orderModel->musicwithoutstatus->title, 'readonly' => true])->label('歌曲名称');
            echo $form->field($orderModel, 'music_singer')->textInput(['value' => $orderModel->musicwithoutstatus->singer, 'readonly' => true])->label('歌手');
            echo $form->field($orderModel, 'music_lyricist')->textInput(['value' => $orderModel->musicwithoutstatus->lyricist, 'readonly' => true])->label('词作者');
            echo $form->field($orderModel, 'music_composer')->textInput(['value' => $orderModel->musicwithoutstatus->composer, 'readonly' => true])->label('曲作者');
            echo $form->field($orderModel, 'user_name')->textInput(['value' => $orderModel->user->remarks, 'readonly' => true])->label('备注名');
            echo $form->field($orderModel, 'mobile')->textInput(['value' => $orderModel->user->mobile, 'readonly' => true])->label('手机号');
            echo $form->field($orderModel, 'attach')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                'multiple' => true,
                'isImage' => false,
                'ossHost' => Yii::$app->params['oss.host'],
                'signatureAction' => ['/site/oss-signature?dir=attach/contract/' . Date('Y/m/')],
                'clientOptions' => ['autoUpload' => true],
                'options' => ['value' => $orderModel->attach],
//                'directory' => 'cover/' . Date('Y/m/')
            ])->label('合同附件');
            echo $form->field($orderModel, 'order_status')->widget('\kartik\select2\Select2', [
                'data'  => $orderModel::$orderStatusForEdit,
                'options' => [
                    'id'    => 'order_status',
                    'multiple' => false,
                    'value' => $orderModel->order_status,
                ],
            ])->label('订单状态');
            echo $form->field($orderModel, 'order_permission')->widget('\kartik\select2\Select2', [
                'data'  => $orderModel::$orderPermission,
                'options' => [
                    'id'    => 'order_permission',
                    'multiple' => false,
                    'value' => $orderModel->order_permission,
                ],
            ])->label('订单权限');

            ?>
            <div class="form-group">
                <label class="control-label col-sm-3"></label>
                <div class="col-sm-5">
                    <?= \yii\bootstrap\Html::submitButton('提交', ['class' => 'center-block btn btn-primary btn-w-m']) ?>
                </div>
            </div>
            <?php
            \yii\bootstrap\ActiveForm::end();
            ?>

        </div>
    </div>