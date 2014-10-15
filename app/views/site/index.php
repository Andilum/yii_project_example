<?php
/* @var $this SiteController */
/* @var $dataProvider CActiveDataProvider */
/* @var $commentList array */
/* @var $likeList array */

$this->widget('PostList', array(
    'dataProvider' => $dataProvider,
    'commentList' => $commentList,
    'likeList' => $likeList,
    'ajaxLink' => Yii::app()->createUrl('/post/list')
));
