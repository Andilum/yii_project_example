<?php
/* @var $this SearchController */
/* @var $t string искомая строка */
/* @var $type array  выбранный тип элемент поиска */
/* @var $dataSearch array масив всех элементов поиска  */
/* @var $countAll integer  */
$entity = $dataSearch[$type];
?>

<div class="searchresults">
    <?php require 'parts/_head.php'; ?>
    <div class="searchresults-body">
        <div class="searchresults-tab">
            <?php require 'parts/_form.php'; ?>
            <?php
            if ($entity['count']) {
                ?>
                <div class="searchresults-tab-inner">
                    <div class="searchresults-tab-ttl"><?= Yii::t('app', $entity['countText'], $entity['count']) ?></div>
                    <?php
                    $entity['items']->render();
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="searchresults-null">
                    К сожалению, мы ничего не нашли<br>
                    по вашему запросу.<br>
                    Попробуйте изменить запрос<br>
                    и поискать снова.
                </div>
            <?php } ?>
        </div>
    </div>
</div>