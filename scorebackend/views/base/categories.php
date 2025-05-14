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
    'label' => '分类管理',
];

$this->title = '分类列表';
echo \dmstr\widgets\Alert::widget();
?>


    <div class="box box-primary">
        <div class="box-header">
            <?= \yii\bootstrap\Html::a('添加', '/base/category_edit', [
                'class' => 'btn btn-primary pull-right',
            ]) ?>
        </div>
        <div class="box-body">
            <?php
            echo \backend\widgets\GridView::widget([
                'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'afterRow' => function ($model, $key, $index) use ($categoryModel) {
                    Modal::begin([
                        'size' => Modal::SIZE_DEFAULT,
                        'header' => '编辑产品',
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
                    echo $form->field($categoryModel, 'category_image')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                        'multiple' => false,
                        'isImage' => true,
                        'ossHost' => Yii::$app->params['oss.host'],
                        'signatureAction' => ['/site/oss-signature?dir=category_image/' . Date('Y/m/')],
                        'clientOptions' => ['autoUpload' => true],
                        'options' => ['value' => $model->category_image],
                    ])->label('分类图片');
                    echo $form->field($categoryModel, 'category_name')->textInput(['value' => $model->category_name])->label('分类名称');
                    echo $form->field($categoryModel, 'sort_by')->textInput(['value' => $model->sort_by])->label('排序');
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
//                        'filter'    => Html::activeInput('text', $searchModel, 'id'),
                    ],
//                    [
//                        'label' => '分类图片',
//                        'attribute' => 'category_image',
//                        'format'    => 'raw',
//                        'value' => function ($model) {
//                            $img = \common\helpers\Attachment::completeUrl($model->category_image);
//                            $ret = Html::img($img, ['width' => 75, 'height' => 75]);
//
//                            return $ret;
//                        },
//                        'filter' => false
//                    ],
                    [
                        'attribute' => 'category_name',
                        'filter' => false,
//                        'filter'    => Html::activeInput('text', $searchModel, 'category_name'),
                    ],
                    [
                        'attribute' => 'sort_by',
                        'filter'    => false,
                    ],
                    [
                        'attribute' => 'tab_sort_by',
                        'filter'    => false,
                        'label'     => '音乐超市tab排序',
                    ],
                    [
                        'attribute' => 'is_delete',
                        'filter' => false,
                        'value' => function ($model) {
                            return $model->is_delete == 0 ? '正常' : '已删除';
                        },
                        'label' => '状态',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{lines} {edit} {delete} {reset}',
                        'buttons' => [
                            'edit' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['base/category_edit', 'id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
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
                            'reset' => function ($url, $model, $key) {
                                return \yii\helpers\Html::button('恢复', [
                                    'class' => 'btn btn-xs btn-success ajax_single_btn',
                                    'request-url' => '',
                                    'request-type' => 'POST',
                                    'data-action' => 'reset',
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



