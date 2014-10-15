<?php
/* @var $this SearchController */
/* @var $data Tag */
?>


<div class="searchresults-item">
    <div class="searchresults-item-ttl"><a href="<?=$data->getUrl()?>" class="searchresults-item-a"><?=CHtml::encode($data->name)?></a></div>
    <div class="searchresults-item-details">
        <?=Yii::t('app', '{n} пост|{n} поста|{n} постов',$data->getCountPost())?>
        <?=Yii::t('app', '{n} комментарий|{n} комментария|{n} комментариев',$data->getCountComent())?>
        
    </div>
</div>