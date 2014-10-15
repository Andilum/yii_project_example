<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
echo '$this->pageTitle="Просмотр '.$this->name[0].' #".$model->'.$this->tableSchema->primaryKey.';';
$label=$this->name[2]; //$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'{$this->name[0]}'=>array('index'),
	\$this->pageTitle,
);\n";
?>

$this->menu=array(
	array('label'=>'Создать <?php echo $this->name[1]; ?>', 'url'=>array('create')),
	array('label'=>'Изменить <?php echo $this->name[1]; ?>', 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),

        array('label'=>'Удалить <?php echo $this->name[1]; ?>', 'url'=>$this->createUrl('delete',array('id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),'htmlOptions'=>array('onclick'=>'return confirm(\'вы уверены?\')')),
	array('label'=>'Список <?php echo $this->name[3]; ?>', 'url'=>array('index')),
);
?>

<h1><?php echo "<?php echo \$this->pageTitle; ?>"; ?></h1>


<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
	echo "\t\t'".$column->name."',\n";
?>
	),
)); ?>