<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/11
 * Time: 下午10:39
 */

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

$this->title = '问题列表';

echo \dmstr\widgets\Alert::widget();
?>

<div class="box box-primary">
    <div class="box-header">
        <?= \yii\bootstrap\Html::button('添加', [
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
            'afterRow' => function ($m, $key, $index) use ($categories) {
                Modal::begin([
                    'size' => Modal::SIZE_LARGE,
                    'options' => [
                        'id' => 'edit-form-' . $m->id
                    ]]);
                $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                ]);
                echo $form->field($m, 'title')->label('问题');
                echo $form->field($m, 'category_id')->dropDownList($categories)->label('分类');
                echo $form->field($m, 'answer')->widget('\kucha\ueditor\UEditor', [
                    'options' => [
                        'id' => 'edit-editor-' . $m->id
                    ],
                    'clientOptions' => [
                        'zIndex' => 10055,
                        'serverUrl' => '/helper/ueditor-upload'
                    ]
                ])->label('解答');
                ?>
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="col-sm-6">
                        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php
                echo Html::hiddenInput('id', $m->id);
                echo Html::activeHiddenInput($m, 'type', ['value' => \common\definitions\Category::TYPE_HELP_ISSUE]);
                echo Html::hiddenInput('action', 'edit');
                ActiveForm::end();
                Modal::end();
            },
            'columns' => [
                'id',
                [
                    'attribute' => 'title',
                    'label' => '名称'
                ],
                [
                    'attribute' => 'category_id',
                    'label' => '分类',
                    'value' => function ($model) {
                        return \yii\helpers\ArrayHelper::getValue($model->category, 'name', '');
                    },
                    'filter' => $categories
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) {
                            return \yii\helpers\Html::a('编辑', 'javascript:void(0)', [
                                'class' => 'btn btn-xs btn-primary',
                                'data-toggle' => 'modal',
                                'data-target' => '#edit-form-' . $model->id
                            ]);
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

<?php
Modal::begin([
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'id' => 'add-form'
    ]
]);
$form = ActiveForm::begin([
    'layout' => 'horizontal',
]);
echo $form->field($model, 'title')->label('问题');
echo $form->field($model, 'category_id')->dropDownList($categories)->label('分类');
echo $form->field($model, 'answer')->widget('\kucha\ueditor\UEditor', [
    'options' => ['id' => 'add-editor'],
    'clientOptions' => [
        'zIndex' => 10055,
        'serverUrl' => '/helper/ueditor-upload'
    ]
])->label('解答');
?>
<div class="form-group">
    <label class="control-label col-sm-3"></label>
    <div class="col-sm-6">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php
echo Html::activeHiddenInput($model, 'type', ['value' => \common\definitions\Category::TYPE_HELP_ISSUE]);
echo Html::hiddenInput('action', 'edit');
ActiveForm::end();
Modal::end();
?>

