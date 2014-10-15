<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-form',
    'enableClientValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
    <?= $form->textArea($model, 'text', array('placeholder' => Yii::t('form', 'Комментировать') )); ?><br/>
    <input type="text" name="Photo[text]" placeholder="<?=Yii::t('form', 'Описание')?>" /><input type="file" name="Photo[file]" >
    <div calss = "row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('form', 'Добавить') : Yii::t('form', 'Сохранить') ); ?>
    </div>
<?php $this->endWidget(); ?>