<?php
/* @var $this TagController */
/* @var $dataProvider CSqlDataProvider */
/* @var $commentList array */
/* @var $tag string */

$baseAssetsPath = Yii::getPathOfAlias('application.components.assets.PostList');
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/postList.js'));

$this->widget('zii.widgets.CListView', array(
    'id' => 'item-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_item',
    'htmlOptions' => array('class' => ''),
    'ajaxUpdate' => false,
    'pager' => array(
        'class' => 'AjaxPager',
        'ajaxLink' => Yii::app()->createUrl('/tag/view', array('tag' => $tag)),
        'label' => "следующие " . Tag::DEFAULT_POST_LIMIT_ON_PAGE,
    ),
    'template' => '{items}{pager}',
    'loadingCssClass' => '',
    'viewData' => array('commentList' => $commentList),
));