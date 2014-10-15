<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
echo '$this->pageTitle="Редактирование '.$this->name[0].' #".$model->'.$this->tableSchema->primaryKey.';';
echo "\$this->breadcrumbs=array(
	'список'=>array('index'),
	\$this->pageTitle,
);\n";
?>

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1><?php echo "<?php echo \$this->pageTitle; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>