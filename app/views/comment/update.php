<?php
$title=Yii::t('app', 'Редактирование комментария');

$this->pageTitle = Yii::app()->name . ' - '.$title;
$this->breadcrumbs = array(
    $title,
);
?>

<h1><?=$title?></h1>

<div calss="form">
    <?php $this->renderPartial('_form', array('model' => $model)); ?>
</div>
