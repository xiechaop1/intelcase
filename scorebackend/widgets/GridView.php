<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/3/3
 * Time: 下午12:47
 */

namespace backend\widgets;


class GridView extends \yii\grid\GridView
{
    public $isExport = false;

    public $layout;

    public function init()
    {
        $this->isExport = !empty($_GET['is_export']) ? $_GET['is_export'] : false;
        if (!$this->isExport) {
            $this->layout = "{items}\n<p>{summary}{pager}</p>";
        } else {
            $this->layout = "{items}";
        }
        parent::init();
    }

    public function renderItems()
    {
        if (!$this->isExport) {
            return parent::renderItems();
        } else {
            return $this->export();
        }

    }

    public function export() {
        $tag = !empty(\Yii::$app->controller->action->id)
            ? \Yii::$app->controller->action->id . '_'
            : '';


        header('Expires: 0');
        header('Cache-control: private');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
//        header('Content-type: text/csv;');
        header("Content-Disposition:attachment;filename=" . "export_" . $tag . date("Ymd") . rand(1000,9999) . ".csv");
        echo "\xEF\xBB\xBF";
//        ob_end_clean();

        $this->filterPosition = false;
        $this->dataProvider->pagination = array('pageSize' => 10000);
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        $rows[] = $this->renderCsvTableHeader();
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            $rows[] = $this->renderCsvTableRow($model, $key, $index);
        }

        return implode("\n", $rows);

    }

    public function renderCsvTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {

//            $cells[] = iconv("UTF-8", "GB2312//IGNORE", strip_tags($column->renderDataCell($model, $key, $index)));
            $cells[] = strip_tags($column->renderDataCell($model, $key, $index));
        }

        return implode(",", $cells);
    }

    public function renderCsvTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
//            $cells[] = iconv("UTF-8", "GB2312", strip_tags($column->renderHeaderCell()));
            $cells[] = strip_tags($column->renderHeaderCell());
        }

        return implode(',', $cells);
    }

    public static function renderExportBtn()
    {
            $params = $_GET;
        if (empty($params['is_export'])) {
            $params['is_export'] = 'true';
            $query = http_build_query($params);
            return \yii\bootstrap\Html::a('导出', '?' . $query, [
                'class' => 'btn btn-danger pull-right',
            ]);
        } else {
            return '';
        }
    }
}