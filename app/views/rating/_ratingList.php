<?php
/* @var $this RatingController */
/* @var $dataProvider CActiveDataProvider */
/* @var $type string */
/* @var $page int */

$isUser = $type != SR::TYPE_ALLOCATION;

$this->widget('zii.widgets.CListView', array(
    'id' => 'rating-list',
    'dataProvider' => $dataProvider,
    'itemView' => $isUser ? '_userItem' : '_allocationItem',
    'htmlOptions' => array('class' => 'searchresults-rating'),
    'ajaxUpdate' => false,
    'template' => '{items}',
    'loadingCssClass' => '',
    'viewData' => array('page' => $page),
));