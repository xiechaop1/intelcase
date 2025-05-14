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
    'label' => '用户管理',
];

$this->title = '用户列表';
?>
    <style>
        table a.data {font-color: blue; font-weight: bold;}
        table a.data:hover {text-decoration: underline;}
    </style>


<?php
echo \dmstr\widgets\Alert::widget();
?>

    <div class="box box-primary">
        <div class="box-header">
            <?= \yii\bootstrap\Html::button('添加白名单', [
                'class' => 'btn btn-primary pull-right',
                'data-toggle' => "modal",
                'data-target' => '#add-form'
            ]) ?>
        </div>
        <div class="box-body">
            <?php
            echo \backend\widgets\GridView::widget([
                'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'afterRow' => function ($model, $key, $index) use ($userModel) {
                    Modal::begin([
                        'size' => Modal::SIZE_DEFAULT,
                        'header' => '编辑用户',
                        'options' => [
                            'id' => 'case-form-' . $model->id
                        ]]);
                    $form = ActiveForm::begin([
                        'layout' => 'horizontal',
                        'enableClientValidation' => true,
                        'enableAjaxValidation' => true,
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'horizontalCssClasses' => [
                                'label' => 'col-sm-2',
                                'offset' => 'col-sm-offset-1',
                                'wrapper' => 'col-sm-6',
                            ],
                        ],
                    ]);
                    ?>
                    <?php
                    echo $form->field($userModel, 'remarks')->textInput(['value' => $model->remarks])->label('备注名');
                    echo $form->field($userModel, 'mobile')->textInput(['value' => $model->mobile])->label('手机号');
                    echo $form->field($userModel, 'max_lock_ct')->textInput(['value' => $model->max_lock_ct])->label('最多锁定数');
                    echo $form->field($userModel, 'user_type')->inline(true)->radioList(\common\models\User::$userTypeNameMap, ['value' => $model->user_type])->label('用户类型');
                    ?>
                    <div class="form-group">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </div>
                    <?php
                    echo Html::hiddenInput('data-id', $model->id);
                    ActiveForm::end();
                    Modal::end();
                },
                'columns' => [
                    [
                        'attribute' => 'id',
//                        'filter'    => Html::activeInput('text', $searchModel, 'id', ['size' => '5']),
                        'filter'    => false,
                    ],
                    [
                        'label' => '用户名',
                        'attribute' => 'remarks',
                        'filter'    => Html::activeInput('text', $searchModel, 'remarks',['placeholder'=>'用户名']),
                    ],
                    [
                        'label' => '手机号',
                        'attribute' => 'mobile',
                        'filter'    => Html::activeInput('text', $searchModel, 'mobile',['placeholder'=>'手机号']),
                    ],
//                    [
//                        'label' => '封面图片',
//                        'attribute' => 'verse_url',
//                        'format'    => 'raw',
//                        'value' => function ($model) {
//                            $img = \common\helpers\Attachment::completeUrl($model->verse_url);
//                            $ret = Html::img($img, ['width' => 150, 'height' => 75]);
//
//                            return $ret;
//                        },
//                        'filter' => false
//                    ],

                    [
                        'attribute' => 'user_type',
                        'label' => '用户类型',
                        'value' => function($model) {
                            return
                                isset (\common\models\User::$userTypeNameMap[$model->user_type]) ?
                                    \common\models\User::$userTypeNameMap[$model->user_type] :
                                    '未知'
                                ;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'user_type',
                            \common\models\User::$userTypeNameMap, ['prompt' => '全部', "class" => "form-control ", 'value' => !empty($params['User']['user_type']) ? $params['User']['user_type'] : ''])
                    ],
                    [
                        'attribute' => 'user_status',
                        'label' => '用户状态',
                        'value' => function($model) {
                            return
                                isset (\common\models\User::$userStatus[$model->user_status]) ?
                                    \common\models\User::$userStatus[$model->user_status] :
                                    '未知'
                                ;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'user_status',
                            \common\models\User::$userStatus, ["class" => "form-control ", 'value' => !empty($params['User']['user_status']) ? $params['User']['user_status'] : ''])
                    ],
                    [
                        'label' => '操作记录',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            $str =  Html::a('锁定：' . $model->getUserLockCount(), '/order/orders?Order[order_status]=' . \common\models\Order::ORDER_STATUS_LOCK . '&Order[user_id]=' . $model->id, ['class' => 'data focus']) . '<br>';
                            $str .= Html::a('喜欢：' . $model->getUserFavCount(), '/user/usermusiclist?list_type=' . \common\models\UserList::LIST_TYPE_FAV . '&id=' . $model->id, ['class' => 'data focus']) . '<br>';
                            $str .= Html::a('浏览：' . $model->getUserViewCount(), '/user/usermusiclist?list_type=' . \common\models\UserList::LIST_TYPE_VIEW . '&id=' . $model->id, ['class' => 'data focus']) . '<br>';
                            $str .= Html::a('购买：' . $model->getUserOrderCompletedCount(), '/order/orders?Order[order_status]=' . \common\models\Order::ORDER_STATUS_COMPLETED . '&Order[user_id]=' . $model->id, ['class' => 'data focus']) . '<br>';
                            $str .= Html::a('取消锁定：' . $model->getUserOrderCanceledCount(), '/order/orders?Order[order_status]=' . \common\models\Order::ORDER_STATUS_CANCELED . '&Order[user_id]=' . $model->id, ['class' => 'data focus']) . '<br>';
                            return $str;
                        }
                    ],
                    [
                        'label' => '创建时间',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            return Date('Y-m-d H:i:s', $model->created_at);
                        }
                    ],
                    [
                        'label' => '最后登录时间',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            return Date('Y-m-d H:i:s', $model->last_login_time);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{lines} {normal} {forbidden} {edit} {delete}',
                        'buttons' => [
                            'normal' => function ($url, $model, $key) {
                                if ($model->user_status == \common\models\User::USER_STATUS_FORBIDDEN) {
                                    return \yii\helpers\Html::a('账号恢复', 'javascript:void(0)', [
                                        'class' => 'btn btn-primary btn-xs ajax-status-btn',
                                        'request-confirm' => '确认恢复正常吗?',
                                        'request-url' => '',
                                        'request-type' => 'POST',
                                        'data-action' => 'normal',
                                        'data-id' => $model->id,
                                        'data-value' => '',
                                    ]);
                                }
                            },
                            'forbidden' => function ($url, $model, $key) {
                                if ($model->user_status == \common\models\User::USER_STATUS_NORMAL) {
                                    return \yii\helpers\Html::a('账号封禁', 'javascript:void(0)', [
                                        'class' => 'btn btn-primary btn-xs ajax-status-btn',
                                        'request-confirm' => '确认封禁吗?',
                                        'request-url' => '',
                                        'request-type' => 'POST',
                                        'data-action' => 'forbidden',
                                        'data-id' => $model->id,
                                        'data-value' => '',
                                    ]);
                                }
                            },
                            'edit' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a('编辑', 'javascript:void(0);', [
                                    'class' => 'btn btn-xs btn-primary',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#case-form-' . $model->id
                                ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return \yii\helpers\Html::button('删除', [
                                    'class' => 'btn btn-xs btn-danger delete_single_btn',
                                    'request-url' => '',
                                    'request-type' => 'POST',
                                    'data-action' => 'delete',
                                    'data-id' => $model->id
                                ]);
                            },
                        ],
                    ]
                ],

            ]);
            ?>
        </div>
    </div>

<?php
Modal::begin([
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'id' => 'add-form'
    ]
]);
$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'enableClientScript' => true,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-9',
        ],
    ],
]);
echo $form->field($userModel, 'remarks')->label('用户名');
echo $form->field($userModel, 'mobile')->label('手机号');
echo $form->field($userModel, 'max_lock_ct')->textInput(['value' => 3])->label('最多锁定数');
echo $form->field($userModel, 'user_type')->inline(true)->radioList(\common\models\User::$userTypeNameMap)->label('用户类型');
?>
    <div class="form-group">
        <label class="control-label col-sm-2"></label>
        <div class="col-sm-9">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php
ActiveForm::end();
Modal::end();


