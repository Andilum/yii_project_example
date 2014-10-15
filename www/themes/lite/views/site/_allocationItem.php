<?php
/* @var $data SRAllocation */
/* @var $page int */
/* @var $index int */
?>
<div class="searchresults-rating-i">
    <div class="searchresults-rating-i-inner">
        <?php $rowNumber = $index + ($page - 1) * SR::DEFAULT_LIMIT + 1; ?>
        <div class="searchresults-rating-cell searchresults-rating-val"><?= $rowNumber ?></div>
        <div class="searchresults-rating-cell searchresults-rating-img">
            <img src="<?= $data['photo_url'] ?>" alt="" style="width: 30px" />
        </div>
        <div class="searchresults-rating-cell searchresults-rating-name">
            <div>
                <a href="<?= Yii::app()->createUrl('/allocation/view', array('id' => $data['id'])) ?>"><?= $data['name'] ?> <?= $data['alloccat_name'] ?></a>
            </div>
            <?php if ($data['country_name'] || $data['resort_name']): ?>
                <div class="searchresults-rating-i-loc"><?= $data['country_name'] ?>, <?= $data['resort_name'] ?></div>
            <?php endif; ?>
            <span class="searchresults-rating-shader"></span>
        </div>
        <div class="searchresults-rating-cell searchresults-rating-feed"><a href="#"><?= $data['subscriber_count'] ?></a></div>
        <div class="searchresults-rating-cell searchresults-rating-post"><a href="<?= Yii::app()->createUrl('/allocation/view', array('id' => $data['id'])) ?>"><?= $data['post_count'] ?></a></div>
        <div class="searchresults-rating-cell searchresults-rating-photo"><a href="<?= Yii::app()->createUrl('/allocation/photo', array('id' => $data['id'])) ?>"><?= $data['photo_count'] ?></a></div>
    </div>
</div>