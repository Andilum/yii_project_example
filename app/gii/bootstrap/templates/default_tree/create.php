<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
echo '$this->pageTitle="Создание '.$this->name[0].'";';
echo "\$this->breadcrumbs=array(
	'{$this->name[0]}'=>array('index'),
	\$this->pageTitle,
);\n";
?>




$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создание <?php echo $this->name[0];?></h1>


<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
