<?php
/* @var $data SRUser */
/* @var $page int */
/* @var $index int */
?>
<div class="searchresults-rating-i">
    <div class="searchresults-rating-i-inner">
        <?php $rowNumber = $index + ($page - 1) * SR::DEFAULT_LIMIT + 1; ?>
        <div class="searchresults-rating-cell searchresults-rating-val"><?= $rowNumber ?></div>
        <div class="searchresults-rating-cell searchresults-rating-img">
            <img src="<?= User::getAvatarPath($data['id'], User::AVATAR_SIZE_50) ?>" alt="" style="width: 30px" />
        </div>
        <div class="searchresults-rating-cell searchresults-rating-name">
            <div>
                <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $data['id'])) ?>"><?= $data['nick'] ?></a>
                <?php if ($data['name'] && $data['surname']): ?>
                    <span>– <?= $data['name'] ?> <?= $data['surname'] ?></span>
                <?php endif; ?>
            </div>
            <?php if ($data['country_name'] || $data['city_name']): ?>
                <div class="searchresults-rating-i-loc"><?= $data['country_name'] ?>, <?= $data['city_name'] ?></div>
            <?php endif; ?>
            <span class="searchresults-rating-shader"></span>
        </div>
        <div class="searchresults-rating-cell searchresults-rating-feed"><a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $data['id'])) ?>"><?= $data['subscriber_count'] ?></a></div>
        <div class="searchresults-rating-cell searchresults-rating-post"><a href="<?= Yii::app()->createUrl('/user/view', array('id' => $data['id'])) ?>"><?= $data['post_count'] ?></a></div>
        <div class="searchresults-rating-cell searchresults-rating-photo"><a href="<?= Yii::app()->createUrl('/user/photo', array('id' => $data['id'])) ?>"><?= $data['photo_count'] ?></a></div>
        <div class="searchresults-rating-cell searchresults-rating-like"><span><?= $data['like_count'] ?></span></div>
    </div>
</div>