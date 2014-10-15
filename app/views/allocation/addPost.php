<?php
$title=Yii::t('app', 'Добавление сообщения со страницы пользователя');
$this->pageTitle = Yii::app()->name . ' - '.$title;
$this->breadcrumbs = array(
    $title,
);
?>

<h1><?=$title?></h1>

<div calss="form">
    <?php $this->renderPartial('_postForm', array('model' => $model)); ?>
</div>