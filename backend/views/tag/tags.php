<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/4
 * Time: 下午9:35
 */

$this->title = '标签列表';

foreach ($params['special_type']as $st) {
    if ($st == \common\definitions\Tag::TYPE_BOUTIQUE) {
        $this->params['breadcrumbs'][] = [
            'label' => '标签管理',
        ];

        $this->title = '主题管理';
    }
}
?>

<div class="box box-primary">
    <div class="box-header">
        <?php
        $needRecommend = false;
        foreach ($params['special_type'] as $st) {
            if ($st == \common\definitions\Tag::TYPE_COMMON
            || $st==\common\definitions\Tag::TYPE_GEO
            ) {
                $needRecommend = true;
            }
            $special_type_params[] = 'special_type[]=' . $st;
        }

        echo \yii\bootstrap\Html::a('添加', '/tag/edit?' . implode('&', $special_type_params), [
            'class' => 'btn btn-primary pull-right',
        ]) ?>
    </div>
    <div class="box-body">
        <?php

        if ($needRecommend) {
            $tagColumns = [
                'id',
                [
                    'attribute' => 'name',
                    'label' => '名称'
                ],
                [
                    'attribute' => 'created_at',
                    'label' => '创建时间',
                    'format' => ['date', 'php:Y-m-d H:i:s']
                ],
                [
                    'attribute' => 'special_type',
                    'label' => '分类',
                    'value' => function ($model) {
                        $typeName = \common\definitions\Tag::$tag2Name[$model->special_type];
                        return $typeName;
                    }
                ],
                [
                    'attribute' => 'is_recommend',
                    'label' => '推荐',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return \yii\bootstrap\Html::dropDownList('status-' . $model->id, $model->is_recommend, [
                            \common\definitions\Common::ENABLE => '是',
                            \common\definitions\Common::DISABLE => '否',
                        ], [
                            'class' => 'status-change',
                            'request-url' => '',
                            'data-sure' => '',
                            'data-action' => 'recommend',
                            'data-id' => $model->id,
                        ]);
                    },
                    'filter' => [\common\definitions\Common::ENABLE => '是', \common\definitions\Common::DISABLE => '否']
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) use ($special_type_params) {
                            return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['tag/edit', 'tag_id' => $model->id]) . '&' . implode('&', $special_type_params), ['class' => 'btn btn-xs btn-primary']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return \yii\helpers\Html::a('删除', '#', [
                                'class' => 'btn btn-xs btn-danger delete_single_btn',
                                'request-url' => '',
                                'request-type' => 'POST',
                                'data-action' => 'delete',
                                'data-id' => $model->id
                            ]);
                        },
                    ],
                ]
            ];
        } else {
            $tagColumns = [
//                'id',
                [
                    'attribute' => 'name',
                    'label' => '名称'
                ],
                [
                    'attribute' => 'created_at',
                    'label' => '创建时间',
                    'format' => ['date', 'php:Y-m-d H:i:s']
                ],
                [
                    'attribute' => 'special_type',
                    'label' => '分类',
                    'value' => function ($model) {
                        $typeName = \common\definitions\Tag::$tag2Name[$model->special_type];
                        return $typeName;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) use ($special_type_params) {
                            return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['tag/edit', 'tag_id' => $model->id]) . '&' . implode('&', $special_type_params), ['class' => 'btn btn-xs btn-primary']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return \yii\helpers\Html::a('删除', '#', [
                                'class' => 'btn btn-xs btn-danger delete_single_btn',
                                'request-url' => '',
                                'request-type' => 'POST',
                                'data-action' => 'delete',
                                'data-id' => $model->id
                            ]);
                        },
                    ],
                ]
            ];
        }
        echo \backend\widgets\GridView::widget([
            'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $tagColumns,

        ]);
        ?>
    </div>
</div>
