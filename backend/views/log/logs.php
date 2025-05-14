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
    'label' => '日志管理',
];

$this->title = '日志';
echo \dmstr\widgets\Alert::widget();
?>


    <div class="box box-primary">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            echo \backend\widgets\GridView::widget([
                'filterPosition' => \backend\widgets\GridView::FILTER_POS_HEADER,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'afterRow' => function ($model, $key, $index) 
//                use ($logModel) 
                {
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
//                    echo $form->field($logModel, 'product_name')->textInput(['value' => $model->product_name])->label('产品名称');
//                    echo $form->field($logModel, 'title')->textInput(['value' => $model->title])->label('标题');
//                    echo $form->field($logModel, 'music_rate')->textInput(['value' => $model->music_rate])->label('歌曲频率');
//                    echo $form->field($logModel, 'duration')->label('歌曲时长')->textInput(['placeholder' => '歌曲时长', 'value' => $model->duration]);
                    ?>
                    <div class="form-group">
                        <label class="control-label col-sm-2"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </div>
                    <?php
//                    echo Html::hiddenInput('data-id', $model->id);
                    ActiveForm::end();
                    Modal::end();
                },
                'columns' => [
                    [
                        'attribute' => 'id',
//                        'filter'    => Html::activeInput('text', $searchModel, 'id'),
                    ],
                    [
                        'label' => '歌曲',
                        'attribute' => 'music_id',
                        'format'    => 'raw',
                        'value' => function ($model) {
                            if (empty($model->music)) return ' - ';
                            $img = \common\helpers\Attachment::completeUrl($model->music->cover_image);
                            $ret = Html::img($img, ['width' => 75, 'height' => 75]);

                            $ret .= ' ' . $model->music->title;

                            return $ret;
                        },
                        'filter' => false
                    ],
                    [
                        'label' => '用户',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            if (!empty($model->user)) {
                                return $model->user->remarks;
                            } elseif ($model->user_id == 0) {
                                return '系统用户';
                            } else {
                                return '未知用户';
                            }
                        }
                    ],
                    [
                        'label' => '操作',
                        'format' => 'raw',
                         'filter' => Html::activeDropDownList(
                            $searchModel,
                            'op_code',
                            \common\models\Log::$opCodeMap, ["class" => "form-control ", 'value' => !empty($params['Log']['op_code']) ? $params['Log']['op_code'] : '']),
                        'value' => function ($model) {

                            if (!empty($model->op_code)) {
                                $ret = \common\models\Log::$opCodeMap[$model->op_code];
                            } else {
                                $ret = ' - ';
                            }

                            return $ret;

                        }
                    ],

                    [
                        'attribute' => 'op_status',
                        'label' => '执行状态',
                        'value' => function($model) {
                            return
                                isset (\common\models\Log::$opStatusMap[$model->op_status]) ?
                                    \common\models\Log::$opStatusMap[$model->op_status] :
                                    '未知'
                                ;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'op_status',
                            \common\models\Log::$opStatusMap, ["class" => "form-control ", 'value' => !empty($params['Log']['op_status']) ? $params['Log']['op_status'] : ''])
                    ],
                    [
                        'label' => '描述',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            return isset ($model->op_desc) ?
                                $model->op_desc :
                                ' - '
                                ;
                        }
                    ],
                    [
                        'label' => '具体返回',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            return isset ($model->ret) ?
                                $model->ret :
                                ' - '
                                ;
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
                        'label' => '更新时间',
                        'format' => 'raw',
                        'filter'    => false,
                        'value' => function ($model) {
                            return Date('Y-m-d H:i:s', $model->updated_at);
                        }
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


