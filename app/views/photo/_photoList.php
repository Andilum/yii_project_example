<?php
/* @var $this PhotoController */
/* @var $dataProvider CActiveDataProvider */
/* @var $type string */

$this->widget('zii.widgets.CListView', array(
    'id' => 'photo-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_photoItem',
    'htmlOptions' => array('class' => ''),
    'ajaxUpdate' => false,
    'template' => '{items}',
    'loadingCssClass' => '',
    'itemsTagName' => 'ul',
    'itemsCssClass' => 'userphoto-list items',
    'viewData' => array('type' => $type),
));