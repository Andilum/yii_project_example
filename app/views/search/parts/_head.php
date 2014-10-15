<?php 
$typeSelect=  isset($type)?$type:null;
?>
<div class="searchresults-ttl">
    <?php
    if ($countAll) {
       echo Yii::t('search', 'Мы нашли {n} результат по запросу|Мы нашли {n} результа по запросу|Мы нашли {n} результатов по запросу', $countAll) . ' <span>«' . CHtml::encode($t) . '»</span>';
    } else {
        echo Yii::t('search', 'Ничего не найдено по запросу') . ' <span>«' . CHtml::encode($t) . '»</span>';
    }
    ?>
</div>
<div class="searchresults-head">
    <ul>
        <li><a href="<?= $this->createUrl('index', array('t' => $t)) ?>" class="searchresults-head-a<?php if ($typeSelect==null) echo ' current'?>"><?= Yii::t('app', 'Результаты поиска') ?></a> <?= $countAll ?></li>
        <?php
        if ($countAll) {
            foreach ($dataSearch as $type2 => $value) {
                ?>
                <li><a href="<?= $this->createUrl('index', array('type' => $type2, 't' => $t)) ?>" class="searchresults-head-a<?php if ($typeSelect==$type2) echo ' current'?>"><?= $value['title'] ?></a> <?= $value['count'] ?></li>
                    <?php
                }
            }
            ?>
    </ul>
</div>