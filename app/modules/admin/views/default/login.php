<?php
$this->pageTitle=Yii::app()->name . ' - Вход';

?>
<div class="form" style="margin: 50px auto;width: 500px;">
    

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'login-form',
'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>



	
		<?php echo $form->textFieldControlGroup($model,'username'); ?>

		<?php echo $form->passwordFieldControlGroup($model,'password'); ?>
	



		<?php echo $form->checkBoxControlGroup($model,'rememberMe'); ?>

<?php echo TbHtml::formActions(array(
TbHtml::submitButton('вход', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
)); ?>


<?php $this->endWidget(); ?>
</div>