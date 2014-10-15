<?php
/* @var $this SearchController */
/* @var $data User */
?>

<div class="searchresults-item">
    <div class="searchresults-item-img"><a href="<?=$data->getUrl()?>" class="comment-top-pic"><img src="<?= User::getAvatarPath($data->id, User::AVATAR_SIZE_25) ?>" alt=""/></a></div>
    <div class="searchresults-item-ttl"><a href="<?=$data->getUrl()?>" class="searchresults-item-a"><?=CHtml::encode($data->nick)?></a></div>
   <!--<div class="searchresults-item-details">Турция, Анталья</div>-->
</div>
