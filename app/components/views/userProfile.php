<?php
/* @var $user User */
/* @var $my bool мой профиль */
?>
<div class="my-profile">
    <div class="wrapper">
        <img class="my-profile-foto" src="<?= User::getAvatarPath($user->id, User::AVATAR_SIZE_290) ?>" alt="">
        <div class="my-profile-right">
            <div class="my-profile-top">
                <?php if ($my) { ?>
                    <ul class="my-profile-social">
                        <li>
                            <a class="my-profile-social-a my-profile-social-vk" href="#"></a>
                        </li>
                        <li>
                            <a class="my-profile-social-a my-profile-social-f" href="#"></a>
                        </li>
                    </ul>
                <?php } ?>
                <h1><?= $user->name ?> <?= $user->surname ?></h1>
                <div class="my-profile-nicname"><?= $user->nick ?></div>
                <?php
                $controllerId = Yii::app()->controller->getId();
                $actionId = Yii::app()->controller->getAction()->getId();
                ?>
                <ul class="my-profile-nav">
                    <?php if (!Yii::app()->theme): ?>
                    <li<?= ($controllerId == 'user' && $actionId == 'subscriptions' && !isset($_GET['t'])) ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon1"></span>
                            <?php
                            $readerCount = UserSubscription::getReadersCount($user->id);
                            $readerText = Yii::t('app', 'читатель|читателя|читателей', $readerCount);
                            ?>
                            <span class="my-profile-nav-number"><?= $readerCount ?>
                                <span class="my-profile-nav-description"><?= $readerText ?></span>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li<?= ($controllerId == 'user' && $actionId == 'subscriptions' && isset($_GET['t']) && $_GET['t'] == 'sub') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id, 't' => 'sub')) ?>">
                            <span class="my-profile-nav-icon my-profile-icon2"></span>
                            <?php
                            $subscriptionCount = UserSubscription::getSubscriptionsCount($user->id);
                            $subscriptionText = Yii::t('app', 'читает');
                            ?>
                            <span class="my-profile-nav-number"><?= $subscriptionCount ?>
                                <span class="my-profile-nav-description"><?= $subscriptionText ?></span>
                            </span>
                        </a>
                    </li>
                    <li<?= ($controllerId == 'user' && $actionId == 'view') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $user->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon3"></span>
                            <?php
                            $postCount = Post::getTotalCount($user->id);
                            $postText = Yii::t('app', 'запись|записи|записей', $postCount);
                            ?>
                            <span class="my-profile-nav-number"><?= $postCount ?>
                                <span class="my-profile-nav-description"><?= $postText ?></span>
                            </span>
                        </a>
                    </li>
                    <li<?= ($controllerId == 'user' && $actionId == 'photo') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/user/photo', array('id' => $user->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon4"></span>
                            <?php
                            $photoCount = Photo::getTotalCount($user->id);
                            $photoText = Yii::t('app', 'фотография|фотографии|фотографий', $photoCount);
                            ?>
                            <span class="my-profile-nav-number"><?= $photoCount ?>
                                <span class="my-profile-nav-description"><?= $photoText ?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="my-profile-nav-icon my-profile-icon5"></span>
                            <?php
                            $likeCount = Like::getTotalCount($user->id);
                            $LikeText = Yii::t('app', 'оценка|оценки|оценок', $likeCount);
                            ?>
                            <span class="my-profile-nav-number"><?= $likeCount ?>
                                <span class="my-profile-nav-description"><?= $LikeText ?></span>
                            </span>
                        </a>
                    </li>
                    <?php if (!Yii::app()->theme): ?>
                    <li<?= ($controllerId == 'user' && $actionId == 'tag') ? ' class="active"' : '' ?>>
                        <a href="<?= Yii::app()->createUrl('/user/tag', array('id' => $user->id)) ?>">
                            <span class="my-profile-nav-icon my-profile-icon6"></span>
                            <?php
                            $tagCount = Tag::getTotalCount($user->id);
                            $tagText = Yii::t('app', 'хэштег|хэштега|хэштегов', $tagCount);
                            ?>
                            <span class="my-profile-nav-number"><?= $tagCount ?>
                                <span class="my-profile-nav-description"><?= $tagText ?></span>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if ($my) { ?>
                <div class="my-profile-bottom">
                    <a href="#" class="my-profile-bottom-a active"><?=Yii::t('app','{n} новое сообщение|{n} новых сообщений',  MessageUser::getCountNoRead(Yii::app()->user->id))?></a>
                    <a href="#" class="my-profile-bottom-a" style="display: none">Обновить информацию</a>
                    <a href="#" class="my-profile-bottom-a">Журнал моих действий</a>
                    <a href="<?=Yii::app()->createUrl('auth/logout')?>" class="my-profile-bottom-a-exit">Разлогиниться</a>
                </div>
            <?php } else { ?>
                <div class="my-profile-bottom">
                    <a href="<?= Yii::app()->createUrl('messageUser/user', array('to' => $user->id)) ?>" class="my-profile-bottom-pm-btn"><span></span><?= Yii::t('app', 'отправить сообщение') ?></a>
                    <?php $isSubscribed = UserSubscription::isAlreadySubscribed(Yii::app()->user->id, $user->id); ?>
                    <?= CHtml::ajaxLink('<span></span>' . Yii::t('app', 'Подписаться на ленту'), Yii::app()->createUrl('/subscription/userSubscribe', array('id' => $user->id)), array(
                        'dataType' => 'json',
                        'context' => 'this',
                        'success' => '$.proxy(userSubscribeSuccess, this)',
                    ), array('id' => 'profile-subscribe-button', 'class' => 'my-profile-bottom-feed-btn', 'style' => $isSubscribed ? 'display: none' : '')) ?>
                    <?= CHtml::ajaxLink('<span></span>' . Yii::t('app', 'Отписаться'), Yii::app()->createUrl('/subscription/userUnsubscribe', array('id' => $user->id)), array(
                        'dataType' => 'json',
                        'context' => 'this',
                        'success' => '$.proxy(userUnsubscribeSuccess, this)',
                    ), array('id' => 'profile-unsubscribe-button', 'class' => 'my-profile-bottom-feed-btn unsubscribe', 'style' => !$isSubscribed ? 'display: none' : '')) ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>