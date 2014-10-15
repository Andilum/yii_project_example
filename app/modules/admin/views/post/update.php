<?php
/* @var $this PostController */
/* @var $model Post */

$this->pageTitle="Редактирование посты #".$model->id;$this->breadcrumbs=array(
        'посты'=>array('index'),
	$this->pageTitle,
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>