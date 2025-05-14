<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午4:50
 */

$this->params['breadcrumbs'][] = [
    'label' => '管理员列表',
];

$this->title = '管理员列表';

echo \dmstr\widgets\Alert::widget();

?>

<div class="box box-primary">
    <div class="box-header">
        <?= \yii\bootstrap\Html::a('添加', '/admin/edit', [
            'class' => 'btn btn-primary pull-right',
        ]) ?>
    </div>
    <div class="box-body">
        <?php
        echo \backend\widgets\GridView::widget([
            'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                [
                    'attribute' => 'name',
                    'label' => '名称',
                    'filter' => true,
                    // 'filter' => Html::activeInput('text', $searchModel, 'title',['placeholder'=>'名称']),
                ],
                [
                        'attribute' => 'role',
                    'label' => '角色',
                    'value' => function ($model) {
                        return \common\helpers\Common::showList(
                                \common\definitions\Admin::$adminRole2Name,
                                $model->role,
                                '未知'
                            );
                    },
                    'filter' => \common\definitions\Admin::$adminRole2Name ,
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) {
                            return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['/admin/edit', 'admin_id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return \yii\helpers\Html::a('删除', 'javascript:void(0);', [
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