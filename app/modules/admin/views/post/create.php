<?php
/* @var $this PostController */
/* @var $model Post */

$this->pageTitle="Создание посты";$this->breadcrumbs=array(
	'посты'=>array('index'),
	$this->pageTitle,
);




$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создание посты</h1>


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>