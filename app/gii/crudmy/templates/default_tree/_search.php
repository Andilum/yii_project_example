<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
)); ?>\n"; ?>

<?php 

foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
?>
	<div class="row">
		<?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
		<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
	</div>

<?php endforeach; ?>
	<div class="row buttons">
		<?php echo "<?php echo CHtml::submitButton('Поиск'); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->


<?php 
if ($this->isdate)
{
    ?>
<?="<?php"?>


$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery'); 
$cs->registerCoreScript('jquery.ui'); 
$cs->registerCssFile(
$cs->getCoreScriptUrl().
'/jui/css/base/jquery-ui.css'
);
$js = "jQuery('.datevibor').datepicker({dateFormat:'yy-mm-dd'});";
$cs->registerScript('dateviborfiltr',$js);


<?="?>"?>
<?php
}
?>