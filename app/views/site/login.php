<?php
$title=Yii::t('app', 'Авторизация');
$this->pageTitle = Yii::app()->name . ' - '.$title;
$this->breadcrumbs = array(
    $title,
);
?>

<h1> <?=$title?> </h1>

<div calss="form">
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

    <?=Yii::t('form', '<p calss="note">Поля со знаком <span class="requred">*</span> обязательны для заполнения.</p>')?>
    
    <div calss = "row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>
    
    <div calss = "row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>
    
    <div calss = "row rememberMe">
        <?php echo $form->checkBox($model, 'rememberMe'); ?>
        <?php echo $form->label($model, 'rememberMe'); ?>
        <?php echo $form->error($model, 'rememberMe'); ?>
    </div>
    
    <div calss = "row buttons">
        <?php echo CHtml::submitButton(Yii::t('app','Войти')); ?>
    </div>
<?php $this->endWidget(); ?>
</div>
