<?php
/* @var $this AllocationController */
/* @var $dataProvider CActiveDataProvider */
/* @var $allocation DictAllocation */
/* @var $commentList array */
/* @var $likeList array */

$this->widget('PostList', array(
    'dataProvider' => $dataProvider,
    'commentList' => $commentList,
    'likeList' => $likeList,
    'ajaxLink' => Yii::app()->createUrl('/post/list', array('allocId' => $allocation ? $allocation->id : '')),
    'ajaxOptions' => array(
        'data' => array('ratedPosts' => true),
    ),
));