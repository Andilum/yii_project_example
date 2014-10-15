<?php
/* @var $this UserController */
/* @var $dataProvider CSqlDataProvider */

$this->widget('zii.widgets.CListView', array(
    'id' => 'tag-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_tagItem',
    'htmlOptions' => array('class' => ''),
    'ajaxUpdate' => false,
    'template' => '{items}',
    'loadingCssClass' => '',
));