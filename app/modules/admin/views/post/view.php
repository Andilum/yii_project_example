<?php
/* @var $this PostController */
/* @var $model Post */

$this->pageTitle="Просмотр посты #".$model->id;$this->breadcrumbs=array(
	'посты'=>array('index'),
	$this->pageTitle,
);

$this->menu=array(
	array('label'=>'Создать пост', 'url'=>array('create')),
	array('label'=>'Изменить пост', 'url'=>array('update', 'id'=>$model->id)),

        array('label'=>'Удалить пост', 'url'=>$this->createUrl('delete',array('id'=>$model->id)),'htmlOptions'=>array('onclick'=>'return confirm(\'вы уверены?\')')),
	array('label'=>'Список постов', 'url'=>array('index')),
);
?>

<h1><?php echo $this->pageTitle; ?></h1>


<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'name',
		'tp_user_id',
		'date',
		'text',
		'allocation_id',
		'trash',
		'lang',
	),
)); ?>