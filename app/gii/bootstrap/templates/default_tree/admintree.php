<?='<?php'?>   
/* @var $this CategoryController */
/* @var $model Category */

$this->breadcrumbs=array(
	'список'=>array('index'),
	'Дерево',
);

$this->menu=array(
	array('label'=>'список', 'url'=>array('index')),
	array('label'=>'создать', 'url'=>array('create')),
);


<?='?>'?>
<h1>Дерево</h1>
<?='<?php'?>   
$this->widget('WTreeView', array('data' => $this->getcatdata()));
<?='?>'?>