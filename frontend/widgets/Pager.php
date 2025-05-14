<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/3/4
 * Time: 4:25 PM
 */

namespace frontend\widgets;


use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

class Pager extends LinkPager
{
    public $prevPageCssClass = 'mx-2';

    public $nextPageCssClass = 'mx-2';

    public $pageCssClass = 'mx-2';

    public $options = [
        'class' => 'pagination justify-content-center'
    ];

    public $linkOptions = [
        'class' => 'page_link text-66'
    ];

    public $maxButtonCount = 8;

    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, $this->disableCurrentPageButton && $i == $currentPage, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        $options = $this->options;

        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        $btns = Html::tag($tag, implode("\n", $buttons), $options);

        $nav = Html::tag('nav', $btns);
        $block = Html::tag('div', $nav, ['class' => 'center m-t-10']);
//        $block = Html::tag('div', $nav, ['class' => 'd-flex justify-content-center']);

        return $block;
    }

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $page += 1;
        $options = $this->linkContainerOptions;
        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
        Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

        if ($active) {
            // Html::addCssClass($options, $this->activePageCssClass);
        }
//        if ($disabled) {
//            Html::addCssClass($options, $this->disabledPageCssClass);
//            $disabledItemOptions = $this->disabledListItemSubTagOptions;
//            $tag = ArrayHelper::remove($disabledItemOptions, 'tag', 'span');
//
//            return Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledItemOptions), $options);
//        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        if ($active) {
            $linkOptions['class'] .= ' active';
        }
        if ($label == $this->nextPageLabel || $label == $this->prevPageLabel) {
            $linkOptions['class'] = 'page_link text-F6  border-F6';
        }

        return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }
}