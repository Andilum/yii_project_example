<?php
/* @var $this PostList */
/* @var $dataProvider CActiveDataProvider */
/* @var $commentList array */
/* @var $likeList array */
/* @var $ajaxLink int */
/* @var $ajaxOptions array */

$this->widget('zii.widgets.CListView', array(
    'id' => 'post-list',
    'dataProvider' => $dataProvider,
    'itemView' => 'PostList/_post',
    'htmlOptions' => array('class' => ''),
    'ajaxUpdate' => false,
    'template' => '{items}{pager}',
    'loadingCssClass' => '',
    'viewData' => array('commentList' => $commentList, 'likeList' => $likeList),
    'pager' => array(
        'class' => 'AjaxPager',
        'ajaxLink' => $ajaxLink,
        'ajaxOptions' => $ajaxOptions,
        'label' => "следующие " . Post::DEFAULT_POST_LIMIT_ON_PAGE . " постов",
    ),
));
