<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form TbActiveForm */
?>

<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
        'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL
)); 
/* @var \$form TbActiveForm */
?>\n"; ?>

	<p class="note">Поля <span class="required">*</span> обязательные.</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
 echo "<?php echo ".$this->generateActiveControlGroup($this->modelClass,$column)."; ?>\n"; ?>

<?php
}
?>
	<div class="form-actions">
		<?php echo "<?php echo TbHtml::submitButton(\$model->isNewRecord ? 'Создать' : 'Сохранить',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->
<?php 
if ($this->isdate)
{
    ?>
<?="<?php"?>


$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery'); 
$cs->registerScriptFile($cs->getCoreScriptUrl().'/jui/js/jquery-ui-i18n.min.js',CClientScript::POS_HEAD);
$cs->registerCoreScript('jquery.ui'); 
$cs->registerCssFile(
$cs->getCoreScriptUrl().
'/jui/css/base/jquery-ui.css'
);
$js = "$.datepicker.setDefaults($.datepicker.regional['ru']); jQuery('.datevibor').datepicker({dateFormat:'yy-mm-dd'});";
$cs->registerScript('dateviborfiltr',$js);


<?="?>"?>
<?php
}
?>