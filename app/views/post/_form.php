<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'post-form',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    )
)); ?>
<?=Yii::t('form', '<p calss="note">Поля со знаком <span class="requred">*</span> обязательны для заполнения.</p>')?>
    
    <div calss = "row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    
    <div calss = "row">
        <?php echo $form->labelEx($model, 'text'); ?>
        <?php echo $form->textArea($model, 'text'); ?>
        <?php echo $form->error($model, 'text'); ?>
    </div>

    <div calss = "row" style="background: #ffffff">
        <?php $this->widget('PhotoUpload', array('displayGallery' => true)); ?>
    </div>
    
    <div calss = "row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('form','Добавить') : Yii::t('form','Сохранить')); ?>
    </div>
<?php $this->endWidget(); ?>