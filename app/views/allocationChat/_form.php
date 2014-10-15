<?php
/* @var $this AllocationChatController */
/* @var $model HotelChat */
?>
<div class="form-chat">
    <?php
    echo CHtml::beginForm();
    echo CHtml::errorSummary($model);
    ?>
    <div class="row title">
        <?php 
        echo CHtml::activeLabelEx($model, 'title');
        echo CHtml::activeTextField($model, 'title');
        ?>
    </div>
    
    <div class="row description">
        <?php 
        echo CHtml::activeLabelEx($model, 'description');
        echo CHtml::activeTextArea($model, 'description');
        ?>
    </div>
    
    <div class="actions">
        <input type="submit" value="Создать">
    </div>

    <?php
    echo CHtml::endForm();
    ?>
</div>