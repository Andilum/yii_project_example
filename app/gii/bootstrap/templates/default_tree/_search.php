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

<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
        'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL
)); ?>\n"; ?>

<?php 

foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
 echo "<?php echo ".$this->generateActiveControlGroup($this->modelClass,$column)."; ?>\n"; ?>


<?php endforeach; ?>
	<div class="form-actions">
		<?php echo "<?php echo TbHtml::submitButton('Поиск',  array('color' => TbHtml::BUTTON_COLOR_PRIMARY,));?>\n" ?>
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