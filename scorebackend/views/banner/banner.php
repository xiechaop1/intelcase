<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/3
 * Time: 下午12:42
 */

use common\helpers\Attachment;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

use kartik\grid\GridView;

$this->title = 'Banner管理';

?>


<div class="box box-primary">
    <div class="box-header">
        <div class="box-header">
            <?= \yii\bootstrap\Html::a('添加', '/banner/edit', [
                'class' => 'btn btn-primary pull-right',
            ]) ?>
        </div>
    </div>
    <div class="box-body">
        <?php
        echo \backend\widgets\GridView::widget([
            'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
            'dataProvider' => $dataProvider,
//            'exportConfig' => [
//                    'xls' => ['filename' => 'test'],
//            ],
            'afterRow' => function ($model, $key, $index) use ($bannerModel) {
                Modal::begin([
                    'size' => Modal::SIZE_DEFAULT,
                    'header' => 'Banner管理',
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
//                echo $form->field($bannerModel, 'page')->textInput(['value' => $model->page])->label('页面');
                echo $form->field($bannerModel, 'image')->widget('\liyifei\uploadOSS\FileUploadOSS', [
                    'multiple' => false,
                    'isImage' => true,
                    'ossHost' => Yii::$app->params['oss.host'],
                    'signatureAction' => ['/site/oss-signature?dir=banner/'],
                    'clientOptions' => ['autoUpload' => true],
                    'options' => ['value' => $bannerModel->image]
                ])->label('图片');
                echo $form->field($bannerModel, 'subject')->textInput(['value' => $model->subject])->label('Banner标题');
                echo $form->field($bannerModel, 'target')->textInput(['value' => $model->target])->label('链接目标');
                echo $form->field($bannerModel, 'sort')->textInput(['value' => $model->sort])->label('排序');
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
//                ['attribute' => 'page', 'label' => '页面'],
                [
                    'attribute' => 'image',
                    'format' => ['image', ['width' => '150', 'height' => 75]],
                    'value' => function ($model) {
                        return \common\helpers\Attachment::completeUrl($model->image);
                    },
                    'filter' => false
                ],
                ['attribute' => 'subject', 'label' => 'Banner'],
                ['attribute' => 'target', 'label' => '目标链接'],
                [
                    'attribute' => 'online_time',
                    'label' => '上线时间',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s', $model->online_time);
                    }
                ],
                [
                    'attribute' => 'offline_time',
                    'label' => '下线时间',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s', $model->offline_time);
                    }
                ],
                ['attribute' => 'sort', 'label' => '排序'],
                [
                    'label' => '状态',
                    'value' => function ($model) {
                        return !empty(\common\models\Banner::$bannerStatus2Name[$model->banner_status])
                                ? \common\models\Banner::$bannerStatus2Name[$model->banner_status]
                            : '未知';
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) {
                            return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['/banner/edit', 'data-id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
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
            ]
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
//                echo $form->field($bannerModel, 'page')->label('页面');
//                echo $form->field($bannerModel, 'image')->label('图片');
echo $form->field($bannerModel, 'image')->widget('\liyifei\uploadOSS\FileUploadOSS', [
    'multiple' => false,
    'isImage' => true,
    'ossHost' => Yii::$app->params['oss.host'],
    'signatureAction' => ['/site/oss-signature?dir=banner'],
    'clientOptions' => ['autoUpload' => true],
//    'options' => ['value' => $bannerModel->image]
])->label('图片');
                echo $form->field($bannerModel, 'subject')->label('主题');
                echo $form->field($bannerModel, 'target')->label('链接目标');
                echo $form->field($bannerModel, 'sort')->label('排序');
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
