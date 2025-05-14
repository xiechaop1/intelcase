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
    'label' => '歌曲视频管理',
];

$this->title = '歌曲视频列表';
echo \dmstr\widgets\Alert::widget();
?>


    <div class="box box-primary">
        <div class="box-header">
            <?= \yii\bootstrap\Html::a('视频上传', '/video/edit', [
                'class' => 'btn btn-primary pull-right',
            ]) ?>
        </div>
        <div class="box-body">
            <?php
            echo \backend\widgets\GridView::widget([
                'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'afterRow' => function ($model, $key, $index) use ($videoModel) {
                    Modal::begin([
                        'size' => Modal::SIZE_DEFAULT,
                        'header' => '查看日志',
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
                    echo Html::hiddenInput('data-id', $model->id);
                    ActiveForm::end();
                    Modal::end();
                },
                'columns' => [
                    [
                        'attribute' => 'id',
//                        'filter'    => Html::activeInput('text', $searchModel, 'id'),
                    ],
                    [
                        'label' => '音乐标题',
                        'format' => 'raw',
                        'filter'    => Html::activeInput('text', $searchModel, 'music_title',['placeholder'=>'音乐标题']),
                        'value' => function ($model) {
                            return $model->music_title;
                        }
                    ],
                    [
                        'label' => '视频文件URL',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            if (strpos($model->video_url, ',') !== false) {
                                $ret = '';
                                $videoUrls = explode(',', $model->video_url);
                                foreach ($videoUrls as $videoUrl) {
                                    $ret .= Html::a($videoUrl, $videoUrl, ['target' => '_blank']) . '<br>';
                                }
                                return $ret;
                            }
                            return Html::a($model->video_url, $model->video_url, ['target' => '_blank']);
                        }
                    ],
//                    [
//                        'label' => '视频文件类型',
//                        'attribute' => 'file_type',
//                    ],

//                    [
//                        'label' => '关联ID',
//                        'format' => 'raw',
//                        'filter'    => Html::activeInput('text', $searchModel, 'link_id',['placeholder'=>'关联ID']),
//                        'value' => function ($model) {
//                            return $model->link_id;
//                        }
//                    ],
                    [
                        'attribute' => 'is_delete',
                        'label' => '数据状态',
                        'value' => function($model) {
                            return
                                isset (\common\models\MusicVideo::$videoIsDelete[$model->is_delete]) ?
                                    \common\models\MusicVideo::$videoIsDelete[$model->is_delete] :
                                    '未知'
                                ;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'is_delete',
                            \common\models\MusicVideo::$videoIsDelete, ["class" => "form-control ", 'value' => !empty($params['Video']['is_delete']) ? $params['Video']['is_delete'] : ''])
                    ],
                    [
                        'label' => '创建时间',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'date_range',
                            \common\definitions\Common::$dateRange, ["class" => "form-control ", 'value' => !empty($params['Video']['date_range']) ? $params['Video']['date_range'] : '']),
                        'value' => function ($model) {
                            return Date('Y-m-d H:i:s', $model->created_at);
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{lines} {edit} {detail} {offline} {reset} {delete}',
                        'buttons' => [
                            'edit' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['video/edit', 'id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
                            },
                            'offline' => function ($url, $model, $key) {
                                return \yii\helpers\Html::button('下架', [
                                    'class' => 'btn btn-xs btn-danger delete_single_btn',
                                    'request-url' => '',
                                    'request-type' => 'POST',
                                    'data-action' => 'offline',
                                    'data-id' => $model->id
                                ]);
                            },
                            'reset' => function ($url, $model, $key) {
                                return \yii\helpers\Html::button('上架', [
                                    'class' => 'btn btn-xs btn-success ajax_single_btn',
                                    'request-url' => '',
                                    'request-type' => 'POST',
                                    'data-action' => 'reset',
                                    'data-id' => $model->id
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


