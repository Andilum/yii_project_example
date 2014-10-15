<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL
    ));
    ?>

    <?php echo $form->textFieldControlGroup($model, 'id'); ?>


    <?php echo $form->textFieldControlGroup($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>

    <?php
    $this->widget('Wautocomplete', array(
        'model' => $model,
        'attribute' => 'tp_user_id',
        'url'=>'/admin/api/autocompleteUser',
        'modelSelect' => 'application.models.User',
        'attrName' => 'nick',
        'prepend' => TbHtml::icon(TbHtml::ICON_USER)
    ));
    ?>


    <?php echo $form->textFieldControlGroup($model, 'date', array('class' => 'datevibor')); ?>


    <?php echo $form->textAreaControlGroup($model, 'text', array('rows' => 6, 'cols' => 50)); ?>


     <?php
    $this->widget('Wautocomplete', array(
        'model' => $model,
        'attribute' => 'allocation_id',
        'url'=>'/admin/api/autocompleteHotel',
        'modelSelect' => 'application.models.dict.DictAllocation',
        'attrName' => 'name',
        'prepend' => TbHtml::icon(TbHtml::ICON_BRIEFCASE)
    ));
    ?>
 


    <?php echo $form->checkBoxControlGroup($model, 'trash'); ?>


<?php echo $form->textFieldControlGroup($model, 'lang', array('size' => 2, 'maxlength' => 2)); ?>


    <div class="form-actions">
    <?php echo TbHtml::submitButton('Поиск', array('color' => TbHtml::BUTTON_COLOR_PRIMARY,)); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->


<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(
        $cs->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
);
$js = "jQuery('.datevibor').datepicker({dateFormat:'yy-mm-dd'});";
$cs->registerScript('dateviborfiltr', $js);
?>