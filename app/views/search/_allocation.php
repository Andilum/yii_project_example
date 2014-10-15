<?php
/* @var $this SearchController */
/* @var $data DictAllocation */
?>

<div class="searchresults-item">
    <div class="searchresults-item-ttl"><a class="searchresults-item-a" href="<?=$data->getUrl()?>"><?=  CHtml::encode($data->getName())?></a></div>
    <div class="searchresults-item-details"><?=$data->re?CHtml::encode($data->re->name):''?></div>
</div>