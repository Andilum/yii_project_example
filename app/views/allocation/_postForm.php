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
    
    <div calss = "row">
        <?= CHtml::label(Yii::t('form','Введите название отеля:') ,'DictAllocation[name]') ?>
        <?php
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model'=>DictAllocation::model(),   // модель
                'attribute'=>'name',  // атрибут модели
                'source' =>Yii::app()->createUrl('/allocation/autocomplete'),
                'options'=>array(
                    'minLength'=>'2',
                    'showAnim'=>'fold',
                    // обработчик события, выбор пункта из списка
                    'select' =>'js: function(event, ui) {
                        this.value = ui.item.label;
                        $("#DictAllocation_id").val(ui.item.id);
                        return false;
                    }',
                ),
                'htmlOptions' => array(
                    'maxlength'=>10,
                ),
            ));
        ?>
        <?php echo $form->hiddenField(DictAllocation::model(),'id', array('style'=>'display: none;')); ?>
    </div>
    
<input type="text" name="Photo[text]" placeholder="<?=Yii::t('form', 'Описание')?>" /><input type="file" name="Photo[file]" >
    
    <div calss = "row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('form', 'Добавить') : Yii::t('form', 'Сохранить') ); ?>
    </div>
<?php $this->endWidget(); ?>