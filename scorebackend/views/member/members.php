<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/4/25
 * Time: 1:55 PM
 */

use yii\bootstrap\Modal;
use common\models\Member;

$this->title = '会员列表';


$type_list['0'] = '全部';
foreach (Member::$memberType2Name as $k => $v) {
    $type_list[$k] = $v;
}
?>

<div class="row">
    <div class="col-md-12">
        <?php
        $form = \yii\bootstrap\ActiveForm::begin([
            'action' => '/member/members',
            'method' => 'get',
            'layout' => 'inline',
            'fieldConfig' => [
                'options' => ['class' => 'form-group col-xs-2'],
                'template' => '{input}'
            ],
        ]);
        echo $form->field($searchModel, 'true_name')->textInput(['placeholder' => '姓名', 'value' => !empty($_GET['Member']['true_name']) ? $_GET['Member']['true_name'] : '']);
        echo $form->field($searchModel, 'mobile')->textInput(['placeholder' => '手机', 'value' => !empty($_GET['Member']['mobile']) ? $_GET['Member']['mobile'] : '']);
        echo $form->field($searchModel, 'search')->textInput(['placeholder' => '公司', 'value' => !empty($_GET['Member']['search']) ? $_GET['Member']['search'] : '']);
        echo $form->field($searchModel, 'type')->dropDownList($type_list);
        echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-primary']);
        \yii\bootstrap\ActiveForm::end();
        ?>
    </div>
</div>
<br/>
<div class="box box-primary">
    <div class="box-header">

    </div>
    <div class="box-body">
        <?php
        echo \backend\widgets\GridView::widget([
            'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
            'dataProvider' => $dataProvider,
            'afterRow' => function ($model, $key, $index) {
                Modal::begin([
                    'size' => Modal::SIZE_LARGE,
                    'options' => [
                        'id' => 'detail-' . $model->id
                    ]]);
                echo \yii\widgets\DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        [
                            'label' => '姓名',
                            'attribute' => 'true_name',
                        ],
                        [
                            'label' => '手机',
                            'value' => function ($model) {
                                return "{$model->mobile_section} {$model->mobile}";
                            }
                        ],
                        ['label' => '邮箱', 'attribute' => 'email'],
                        ['label' => '注册日期', 'attribute' => 'created_at', 'format' => ['date', 'php:Y-m-d H:i:s']],
                        [
                            'label' => '公司',
                            'value' => function ($model) {
                                if (!empty($model->consultantCompany->company_name)) {
                                    return "{$model->consultantCompany->company_name}";
                                }
                            }
                        ],
                    ]
                ]);
                Modal::end();
            },
            'filterModel' => null,
            'columns' => [
                'id',
                [
                    'label' => '姓名',
                    'attribute' => 'true_name',
                ],
                [
                    'label' => '手机',
                    'value' => function ($model) {
                        return "{$model->mobile_section} {$model->mobile}";
                    }
                ],
                ['label' => '邮箱', 'attribute' => 'email'],
                [
                    'label' => '公司',
                    'value' => function ($model) {
                        if (!empty($model->consultantCompany->company_name)) {
                            return "{$model->consultantCompany->company_name}";
                        }
                    }
                ],
                ['label' => '注册日期', 'attribute' => 'created_at', 'format' => ['date', 'php:Y-m-d H:i:s']],
                [
                    'label' => '身份类型',
                    'value' => function ($model) {
                        return Member::$memberType2Name[$model->type];
                    }
                ],
//                [
//                    'class' => 'yii\grid\ActionColumn',
//                    'header' => '操作',
//                    'template' => '{view}',
//                    'buttons' => [
//                        'view' => function ($url, $model, $key) {
//                            return \yii\helpers\Html::a('编辑', '/member/edit?member_id=' . $model->id, [
//                                'class' => 'btn btn-xs btn-primary',
//                                'target' => "_blank"
//                            ]);
//                        },
//                    ],
//                ]
            ],

        ]);
        ?>
    </div>
</div>
