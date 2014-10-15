<?php
/* @var $this SearchController */
/* @var $t string искомая строка */
?>

<div class="searchresults">
    <div class="searchresults-ttl">
        <?= Yii::t('app', 'Поиск') ?>
    </div>
    <div class="searchresults-body">
        <div class="searchresults-tab">
            <?php require 'parts/_form.php'; ?>
        </div>
    </div>
</div>