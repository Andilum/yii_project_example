<?php
/* @var EClientScript $clientScript */
$clientScript = Yii::app()->getClientScript();
$baseUrl = Yii::app()->getBaseUrl();
$clientScript->registerScriptFile($baseUrl . '/js/Allocation/profile.js');

$allocation = isset($_GET['id']) ? DictAllocation::model()->findByPk($_GET['id']) : null;
if ($allocation):
?>
<div class="my-profile">
    <div class="wrapper">
        <img class="my-profile-foto" src="<?=$allocation->getPhotoUrl()?>" alt="">
        <div class="my-profile-right">
            <div class="my-profile-top">
                <ul class="my-profile-social">
                    <li>
                        <a class="my-profile-social-a my-profile-social-vk" href="#"></a>
                    </li>
                    <li>
                        <a class="my-profile-social-a my-profile-social-f" href="#"></a>
                    </li>
                </ul>
                <h1><?= $allocation->name ?> <?= $allocation->alloccat->name?></h1>
                <div class="my-profile-nicname">
                    <span class="<?= AllocationHelper::getClassNameForStars($allocation->alloccat->name) ?>"></span>
                    <?= $allocation->re->co->name ?>, <?= $allocation->re->name ?>
                </div>
                <?php
                $controllerId = Yii::app()->controller->getId();
                $actionId = Yii::app()->controller->getAction()->getId();
                ?>
                <ul class="my-profile-nav">
                    <li>
                        <a href="#">
                            <span class="my-profile-nav-icon my-profile-icon7"></span>
                            <span class="my-profile-nav-number">358
                                <span class="my-profile-nav-description">были в отеле</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="my-profile-nav-icon my-profile-icon8"></span>
                            <span class="my-profile-nav-number">8
                                <span class="my-profile-nav-description">в отеле</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="my-profile-nav-icon my-profile-icon2"></span>
                            <?php $subscriberCount = AllocationSubscription::getSubscribersCount($allocation->id);
                            $subscriberText = Yii::t('app','читает'); ?>
                            <span class="my-profile-nav-number"><?= $subscriberCount ?>
                                <span class="my-profile-nav-description"><?= $subscriberText ?></span>
                            </span>
                        </a>
                    </li>
                    <li<?= ($controllerId == 'allocation' && $actionId == 'view') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/allocation/view', array('id' => $allocation->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon3"></span>
                            <?php $postCount = Post::getTotalAllocationCount($allocation->id);
                            $postText = Yii::t('app','запись|записи|записей',$postCount); ?>
                            <span class="my-profile-nav-number"><?= $postCount ?>
                                <span class="my-profile-nav-description"><?= $postText ?></span>
                            </span>
                        </a>
                    </li>
                    <li<?= ($controllerId == 'allocation' && $actionId == 'photo') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/allocation/photo', array('id' => $allocation->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon4"></span>
                            <?php $photoCount = Photo::getTotalCountByAllocationId($allocation->id);
                            $photoText = Yii::t('app','фотография|фотографии|фотографий',$photoCount); ?>
                            <span class="my-profile-nav-number"><?= $photoCount ?>
                                <span class="my-profile-nav-description"><?= $photoText ?></span>
                            </span>
                        </a>
                    </li>
                    <li<?= ($controllerId == 'allocation' && $actionId == 'rating') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/allocation/rating', array('id' => $allocation->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon5"></span>
                            <?php $ratingCount = UserRating::getTotalCountByAllocationId($allocation->id);
                            $ratingText = Yii::t('app','оценка|оценки|оценок',$ratingCount); ?>
                            <span class="my-profile-nav-number"><?= $ratingCount ?>
                                <span class="my-profile-nav-description"><?= $ratingText ?></span>
                            </span>
                        </a>
                    </li>
                    <li<?= ($controllerId == 'allocation' && $actionId == 'tag') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/allocation/tag', array('id' => $allocation->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon6"></span>
                            <?php $tagCount = Tag::getTotalCountByAllocationId($allocation->id);
                            $tagText = Yii::t('app','хэштег|хэштега|хэштегов',$tagCount); ?>
                            <span class="my-profile-nav-number"><?= $tagCount ?>
                                <span class="my-profile-nav-description"><?= $tagText ?></span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="my-profile-bottom">
                <?php $isSubscribed = AllocationSubscription::isAlreadySubscribed(Yii::app()->user->id, $allocation->id); ?>
                <?= CHtml::ajaxLink('<span></span>' . Yii::t('app', 'Подписаться на ленту'), Yii::app()->createUrl('/subscription/allocationSubscribe', array('id' => $allocation->id)), array(
                    'dataType' => 'json',
                    'context' => 'this',
                    'success' => '$.proxy(allocationSubscribeSuccess, this)',
                ), array('id' => 'profile-subscribe-button', 'class' => 'my-profile-bottom-feed-btn', 'style' => $isSubscribed ? 'display: none' : '')) ?>
                <?= CHtml::ajaxLink('<span></span>' . Yii::t('app', 'Отписаться'), Yii::app()->createUrl('/subscription/allocationUnsubscribe', array('id' => $allocation->id)), array(
                    'dataType' => 'json',
                    'context' => 'this',
                    'success' => '$.proxy(allocationSubscribeSuccess, this)',
                ), array('id' => 'profile-unsubscribe-button', 'class' => 'my-profile-bottom-feed-btn unsubscribe', 'style' => !$isSubscribed ? 'display: none' : '')) ?>
                <a href="http://tophotels.ru/main/hotel/al<?= $allocation->id ?>" class="my-profile-bottom-a" target="_blank">Отель на TopHotels</a>
                <a href="#" class="my-profile-bottom-a">Отель на Booking.com</a>
                <div class="my-profile-bottom-notme">
                    <a href="<?= Yii::app()->createUrl('/search/index', array('type' => 'allocation')) ?>" class="my-profile-bottom-notme-a">Другой отель?</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
endif;
?>