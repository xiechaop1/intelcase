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

$this->title = '订单列表';
echo \dmstr\widgets\Alert::widget();
?>


    <div class="box box-primary">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            echo \backend\widgets\GridView::widget([
                'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
                'filterRowOptions' => ['class' => 'filters'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'afterRow' => function ($model, $key, $index) use ($orderModel) {
                    Modal::begin([
                        'size' => Modal::SIZE_DEFAULT,
                        'header' => '编辑订单',
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
                    echo $form->field($orderModel, 'order_status')->widget('\kartik\select2\Select2', [
                        'data'  => $orderModel::$orderStatus,
                        'options' => [
                            'id'    => 'order_status',
                            'multiple' => false,
                            'value' => $model->order_status,
                        ],
                    ])->label('订单状态');
                    echo $form->field($orderModel, 'attach')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                        'multiple' => false,
                        'isImage' => false,
                        'ossHost' => Yii::$app->params['oss.host'],
                        'signatureAction' => ['/site/oss-signature?dir=attach/contract/' . Date('Y/m/')],
                        'clientOptions' => ['autoUpload' => true],
                        'options' => ['value' => $model->attach],
                    ])->label('合同附件');
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
                        'filter'    => Html::activeInput('text', $searchModel, 'id', ['size' => 5]),
                    ],
                    [
                        'label' => '封面',
                        'attribute' => 'music_id',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            $img = !empty($model->musicwithoutstatus->cover_image) ? \common\helpers\Attachment::completeUrl($model->musicwithoutstatus->cover_image) : '';
                            $ret = Html::img($img, ['width' => 75, 'height' => 75]);
                            return $ret;
                        },
                        'filter'    => false,
                    ],
                    [
                        'label' => '歌名',
                        'attribute' => 'music_id',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            $musicName = !empty($model->musicwithoutstatus->title) ? $model->musicwithoutstatus->title : '';
                            $ret = $musicName;

                            return $ret;
                        },
                        'filter'    => Html::activeInput('text', $searchModel, 'title', ['value' => !empty($params['Order']['title']) ? $params['Order']['title'] : '']),
                    ],
                    [
                        'label' => '歌手',
                        'attribute' => 'music_singer',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->musicwithoutstatus->singer)) {
                                $singer = $model->musicwithoutstatus->singer;
                            } else {
                                $singer = '-';
                            }
                            return $singer;
                        },
                        'filter'    => Html::activeInput('text', $searchModel, 'singer', ['value' => !empty($params['Order']['singer']) ? $params['Order']['singer'] : '']),
                    ],
                    [
                        'label' => '词作者',
                        'attribute' => 'music_lyricist',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->musicwithoutstatus->lyricist)) {
                                $lyricist = $model->musicwithoutstatus->lyricist;
                            } else {
                                $lyricist = '-';
                            }
                            return $lyricist;
                        },
                        'filter'    => Html::activeInput('text', $searchModel, 'lyricist', ['value' => !empty($params['Order']['lyricist']) ? $params['Order']['lyricist'] : '']),
                    ],
                    [
                        'label' => '曲作者',
                        'attribute' => 'music_composer',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->musicwithoutstatus->composer)) {
                                $composer = $model->musicwithoutstatus->composer;
                            } else {
                                $composer = '-';
                            }
                            return $composer;
                        },
                        'filter'    => Html::activeInput('text', $searchModel, 'composer', ['value' => !empty($params['Order']['composer']) ? $params['Order']['composer'] : '']),
                    ],
                    [
                        'label' => '用户名',
                        'attribute' => 'user_id',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->user->remarks)) {
                                $remarks = $model->user->remarks;
                            } else {
                                $remarks = '-';
                            }
                            return $remarks;
                        },
                        'filter'    => false,
                    ],
                    [
                        'label' => '手机号',
                        'attribute' => 'user_id',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->user->mobile)) {
                                $mobile = $model->user->mobile;
                            } else {
                                $mobile = '-';
                            }
                            return $mobile;
                        },
                        'filter'    => Html::activeInput('text', $searchModel, 'mobile', ['value' => !empty($params['Order']['mobile']) ? $params['Order']['mobile'] : '']),
                    ],

                    [
                        'attribute' => 'order_status',
                        'label' => '订单状态',
                        'value' => function($model) {
                            return
                                isset (\common\models\Order::$orderStatus[$model->order_status]) ?
                                    \common\models\Order::$orderStatus[$model->order_status] :
                                    '未知'
                                ;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'order_status',
                            $orderStatusList, ["class" => "form-control ", 'value' => isset($params['Order']['order_status']) ? $params['Order']['order_status'] : ''])
                    ],
                    [
                        'label' => '合同',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            if (!empty($model->attach)) {
                                $url = \common\helpers\Attachment::completeUrl($model->attach);
                                return Html::a('浏览合同', $url);
                            }
                        }
                    ],
                    [
                        'label' => '过期时间',
                        'format' => 'raw',
//                        'filter'    => Html::activeInput('text', $searchModel, 'created_at'),
                        'value' => function ($model) {
                            if ($model->expire_time <= 0) {
                                return '-';
                            }
                            $ret = Date('Y-m-d H:i:s', $model->expire_time);
                            $ret .= "<br>剩余：" . \common\helpers\Time::minute2friendly(((int)$model->expire_time - (int)time())/60);
                            return $ret;
                        }
                    ],
//                    [
//                        'label' => '剩余时间',
//                        'format' => 'raw',
////                        'filter'    => Html::activeInput('text', $searchModel, 'created_at'),
//                        'value' => function ($model) {
//                            if ($model->expire_time <= 0) {
//                                return '-';
//                            }
//                            return \common\helpers\Time::minute2friendly(((int)$model->expire_time - (int)time())/60);
//                        }
//                    ],
                    [
                        'label' => '创建时间',
                        'format' => 'raw',
//                        'filter'    => Html::activeInput('text', $searchModel, 'created_at'),
                        'value' => function ($model) {
                            return Date('Y-m-d H:i:s', $model->created_at);
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'date_range',
                            \common\definitions\Common::$dateRange, ["class" => "form-control ", 'value' => !empty($params['Order']['date_range']) ? $params['Order']['date_range'] : '']),
                    ],
                    [
                        'label' => '更改时间',
                        'format' => 'raw',
//                        'filter'    => Html::activeInput('text', $searchModel, 'updated_at'),
                        'value' => function ($model) {
                            return Date('Y-m-d H:i:s', $model->updated_at);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{lines} {confirm} {edit} {delete}',
                        'buttons' => [
                            'confirm' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a('确认', 'javascript:void(0)', [
                                    'class' => 'btn btn-primary btn-xs ajax-status-btn',
                                    'request-confirm' => '确认订单吗?',
                                    'request-url' => '',
                                    'request-type' => 'POST',
                                    'data-action' => 'confirm',
                                    'data-id' => $model->id,
                                    'data-value' => '',
                                ]);
                            },
                            'edit' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['order/edit', 'id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
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
echo $form->field($orderModel, 'order_status')->widget('\kartik\select2\Select2', [
    'data'  => $orderStatus,
    'options' => [
        'id'    => 'singer_id',
        'multiple' => false
    ],
])->label('订单状态');
echo $form->field($orderModel, 'attach')->widget('\liyifei\uploadOSS\FileUploadOSS', [
    'multiple' => false,
    'isImage' => false,
    'ossHost' => Yii::$app->params['oss.host'],
    'signatureAction' => ['/site/oss-signature?dir=attach/contract/' . Date('Y/m/')],
    'clientOptions' => ['autoUpload' => true],
    'options' => ['value' => $orderModel->attach],
])->label('合同附件');
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


