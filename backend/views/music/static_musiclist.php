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
    'label' => '歌曲管理',
];

$this->title = '歌曲列表';
echo \dmstr\widgets\Alert::widget();
?>


    <div class="box box-primary">
        <div class="box-header">
            <?= \yii\bootstrap\Html::a('Demo上传', '/music/s_edit', [
                'class' => 'btn btn-primary pull-right',
            ]) ?>
        </div>
        <div class="box-body">
            <?php
            echo \backend\widgets\GridView::widget([
                'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'afterRow' => function ($model, $key, $index) use ($musicModel) {
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
                    foreach ($model->logs as $log) {
                        $logTime = Date('Y-m-d H:i', $log->created_at);
                        $userName = !empty($log->user) ? $log->user->user_name : '系统';
                        $opCode = \common\models\Log::$opCodeMap[$log->op_code];
                        $opRet = $log->ret;
                        $opDesc = $log->op_desc;
                        $opStatus = ($log->op_status == 0) ? '失败' : '成功';

                        echo '<p>';
                        echo '「' . $logTime . '」 ' . $userName . ' 【' . $opCode . '】 ' . $opStatus . ' ' . $opDesc;
                        echo "</p>";
                    }
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
                        'label' => '封面',
                        'attribute' => 'cover_thumbnail',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            $img = \common\helpers\Attachment::completeUrl($model->cover_thumbnail);
                            $ret = Html::img($img, ['width' => 75, 'height' => 75]);

                            return $ret;
                        },
                        'filter' => false
                    ],
                    [
                        'label' => '歌名',
                        'format' => 'raw',
                        'filter'    => Html::activeInput('text', $searchModel, 'title'),
                        'value' => function ($model) {
                            return Html::a($model->title, '/music/s_edit?id=' . $model->id);
                        }
                    ],
                    [
                        'label' => '歌手',
                        'format' => 'raw',
                        'filter'    => Html::activeInput('text', $searchModel, 'singer'),
                        'value' => function ($model) {
                            return $model->singer;
                        }
                    ],
                    [
                        'label' => '词作者',
                        'format' => 'raw',
                        'filter'    => Html::activeInput('text', $searchModel, 'lyricist'),
                        'value' => function ($model) {
                            return $model->lyricist;
                        }
                    ],
                    [
                        'label' => '曲作者',
                        'format' => 'raw',
                        'filter'    => Html::activeInput('text', $searchModel, 'composer'),
                        'value' => function ($model) {
                            return $model->composer;
                        }
                    ],
                    [
                        'label' => '分类',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'category_id',
                            $categories, ["class" => "form-control ", 'value' => !empty($params['Music']['category_id']) ? $params['Music']['category_id'] : '']),
                        'value' => function ($model) {

                            $ret = '';
                            if (!empty($model->categories)) {
                                foreach ($model->categories as $category) {
                                    $ret .= $category->category->category_name . '<br/>';
                                }
                            }

                            return $ret;

                        }
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
                        'attribute' => 'music_status',
                        'label' => '歌曲状态',
                        'value' => function($model) {
                            return
                                isset (\common\models\Music::$musicStatus[$model->music_status]) ?
                                    \common\models\Music::$musicStatus[$model->music_status] :
                                    '未知'
                                ;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'music_status',
                            $musicStatusList, ["class" => "form-control ", 'value' => isset($params['Music']['music_status']) ? $params['Music']['music_status'] : ''])
                    ],
                    [
                        'attribute' => 'is_delete',
                        'label' => '数据状态',
                        'value' => function($model) {
                            return
                                isset (\common\models\Music::$musicIsDelete[$model->is_delete]) ?
                                    \common\models\Music::$musicIsDelete[$model->is_delete] :
                                    '未知'
                                ;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'is_delete',
                            \common\models\Music::$musicIsDelete, ["class" => "form-control ", 'value' => !empty($params['Music']['is_delete']) ? $params['Music']['is_delete'] : ''])
                    ],
                    [
                        'label' => '锁定用户',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            return isset ($model->opUser->remarks) ?
                                $model->opUser->remarks :
                                ' - '
                                ;
                        },
                        'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)
                    ],
                    [
                        'label' => '创建时间',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'date_range',
                            \common\definitions\Common::$dateRange, ["class" => "form-control ", 'value' => !empty($params['Music']['date_range']) ? $params['Music']['date_range'] : '']),
                        'value' => function ($model) {
                            return Date('Y-m-d H:i:s', $model->created_at);
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{lines} {edit} {detail} {delete} {reset} {loglist} {sync}',
                        'buttons' => [
                            'edit' => function ($url, $model, $key) {
                                if (\common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)) {
                                    return \yii\helpers\Html::a('编辑', \yii\helpers\Url::to(['music/s_edit', 'id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
                                }
                            },
                            'detail' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a('详情', \yii\helpers\Url::to(['music/detail', 'id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return \yii\helpers\Html::button('下架', [
                                    'class' => 'btn btn-xs btn-danger delete_single_btn',
                                    'request-url' => '',
                                    'request-type' => 'POST',
                                    'data-action' => 'delete',
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
                            'lock' => function ($url, $model, $key) {
                                if (\common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)){
                                    return \yii\helpers\Html::button('锁定', [
                                        'class' => 'btn btn-xs btn-success ajax_single_btn',
                                        'request-url' => '',
                                        'request-type' => 'POST',
                                        'data-action' => 'lock',
                                        'data-id' => $model->id
                                    ]);
                                }
                            },
                            'unlock' => function ($url, $model, $key) {
                                if (\common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)) {
                                    return \yii\helpers\Html::button('解锁', [
                                        'class' => 'btn btn-xs btn-success ajax_single_btn',
                                        'request-url' => '',
                                        'request-type' => 'POST',
                                        'data-action' => 'unlock',
                                        'data-id' => $model->id
                                    ]);
                                }
                            },
                            'loglist' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a('详细日志', \yii\helpers\Url::to(['log/logs', 'Log[music_id]' => $model->id]), ['class' => 'btn btn-xs btn-primary', 'target' => '_blank']);
                            },
                            'sync' => function ($url, $model, $key) {
                                if (\common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)) {
                                    return \yii\helpers\Html::button('同步B库',[
                                        'class' => 'btn btn-xs btn-success delete_single_btn',
                                        'data-sure' => '确定同步B库吗？',
                                        'request-url' => '',
                                        'request-type' => 'POST',
                                        'data-action' => 'sync',
                                        'data-id' => $model->id
                                    ]);
                                }
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
echo $form->field($musicModel, 'title')->label('标题');
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


