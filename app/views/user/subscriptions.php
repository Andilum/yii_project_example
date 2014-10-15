<?php
/* @var $user User */
/* @var $data array */
/* @var $type string */

/* @var EClientScript $clientScript */
$clientScript = Yii::app()->getClientScript();
$baseUrl = Yii::app()->getBaseUrl();
$clientScript->registerScriptFile($baseUrl . '/js/User/subscriptions.js');
?>
<div class="subscribe">
    <div class="subscribe-ttl">Читатели и подписки <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $user->id)) ?>"><?= $user->nick ?></a></div>
    <div class="subscribe-head">
        <ul>
            <li><a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id)) ?>" class="subscribe-head-a<?= !$type ? ' current' : '' ?>">Читатели</a> <?= UserSubscription::getReadersCount($user->id) ?></li>
            <li><a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id, 't' => 'sub')) ?>" class="subscribe-head-a<?= $type == 'sub' ? ' current' : '' ?>">Подписки</a> <?= UserSubscription::getSubscriptionsCount($user->id) ?></li>
            <li><a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id, 't' => 'alloc')) ?>" class="subscribe-head-a<?= $type == 'alloc' ? ' current' : '' ?>">Выбранные отели</a> <?= AllocationSubscription::getSubscriptionsCount($user->id) ?></li>
        </ul>
    </div>
    <div class="subscribe-body">
        <div class="subscribe-tab">
            <?php if ($type == 'sub'): ?>
                <?php if ($data): ?>
                    <?php foreach ($data as $subscription): ?>
                        <div class="subscribe-item">
                            <div class="subscribe-item-ttl">
                                <div class="subscribe-item-cell"><img src="<?= User::getAvatarPath($subscription->tp_user_id, User::AVATAR_SIZE_50) ?>" alt="" style="width: 30px"></div>
                                <?php $fullName = '';
                                if ($subscription->user_s->name) {
                                    $fullName .= $subscription->user->name;
                                }
                                if ($subscription->user_s->surname) {
                                    $fullName .= ' ' . $subscription->user->surname;
                                } ?>
                                <div class="subscribe-item-cell"><a href="<?= Yii::app()->createUrl('/user/view', array('id' => $subscription->tp_user_id)) ?>" class="subscribe-item-a"><?= $subscription->user->nick ?></a><?= trim($fullName) ? ' — ' . $fullName : '' ?></div>
                            </div>
                            <div class="subscribe-item-tools">
                                <?php $isSubscribed = UserSubscription::isAlreadySubscribed(Yii::app()->user->id, $subscription->tp_user_id); ?>
                                <div class="subscribe-status"<?= $isSubscribed ? ' style="display: none;"' : '' ?>>
                                    <?= CHtml::ajaxLink(Yii::t('app', 'Подписаться'), Yii::app()->createUrl('/subscription/userSubscribe', array('id' => $subscription->tp_user_id)), array(
                                        'dataType' => 'json',
                                        'context' => 'this',
                                        'success' => '$.proxy(userReaderSubscribeSuccess, this)',
                                    ), array('class' => 'subscribe-turn-on', 'id' => 'subscription-subscribe-' . $subscription->tp_user_id)) ?>
                                </div>
                                <div class="subscribe-status"<?= !$isSubscribed ? ' style="display: none;"' : '' ?>>
                                    <span class="subscribe-on">Подписан</span>
                                    <?= CHtml::ajaxLink(Yii::t('app', 'Отписаться'), Yii::app()->createUrl('/subscription/userUnsubscribe', array('id' => $subscription->tp_user_id)), array(
                                        'dataType' => 'json',
                                        'context' => 'this',
                                        'success' => '$.proxy(userReaderUnsubscribeSuccess, this)',
                                    ), array('class' => 'subscribe-turn-off', 'id' => 'subscription-unsubscribe-' . $subscription->tp_user_id)) ?>
                                </div>
                                <div class="subscribe-tool">
                                    <a href="#" class="subscribe-msg">Сообщение</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="subscribe-bottom">Возможно, вы хотели посмотреть, <a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id)) ?>">кто читает ленту</a>  <?= $user->nick ?></div>
            <?php elseif ($type == 'alloc'): ?>
                <?php if ($data): ?>
                    <?php foreach ($data as $item): ?>
                        <div class="subscribe-item">
                            <div class="subscribe-item-ttl">
                                <div class="subscribe-item-cell"><img src="<?= $item->photo->getUrl('s') ?>" alt="" style="width: 30px"></div>
                                <div class="subscribe-item-cell"><a href="<?= Yii::app()->createUrl('/allocation/view', array('id' => $item->allocation_id)) ?>" class="subscribe-item-a"><?= $item->allocation->name ?></a> <span class="subscribe-hotel-stars"><?= $item->allocation->alloccat->name ?></span></div>
                            </div>
                            <div class="subscribe-item-tools">
                                <?php $isSubscribed = AllocationSubscription::isAlreadySubscribed(Yii::app()->user->id, $item->allocation_id); ?>
                                <div class="subscribe-status"<?= $isSubscribed ? ' style="display: none;"' : '' ?>>
                                    <?= CHtml::ajaxLink(Yii::t('app', 'Подписаться'), Yii::app()->createUrl('/subscription/allocationSubscribe', array('id' => $item->allocation_id)), array(
                                        'dataType' => 'json',
                                        'context' => 'this',
                                        'success' => '$.proxy(userAllocationSubscribeSuccess, this)',
                                    ), array('class' => 'subscribe-turn-on', 'id' => 'allocation-subscribe-' . $item->allocation_id)) ?>
                                </div>
                                <div class="subscribe-status"<?= !$isSubscribed ? ' style="display: none;"' : '' ?>>
                                    <span class="subscribe-on">Подписан</span>
                                    <?= CHtml::ajaxLink(Yii::t('app', 'Отписаться'), Yii::app()->createUrl('/subscription/allocationUnsubscribe', array('id' => $item->allocation_id)), array(
                                        'dataType' => 'json',
                                        'context' => 'this',
                                        'success' => '$.proxy(userAllocationUnsubscribeSuccess, this)',
                                    ), array('class' => 'subscribe-turn-off', 'id' => 'allocation-unsubscribe-' . $item->allocation_id)) ?>
                                </div>
                                <div class="subscribe-tool">
                                    <a href="#" class="subscribe-msg">Чаты</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="subscribe-bottom">Возможно, вы хотели посмотреть, <a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id)) ?>">читателей и подписчиков</a>  <?= $user->nick ?></div>
            <?php else: ?>
                <?php if ($data): ?>
                    <?php foreach ($data as $reader): ?>
                        <div class="subscribe-item">
                            <div class="subscribe-item-ttl">
                                <div class="subscribe-item-cell"><img src="<?= User::getAvatarPath($reader->subscriber_id, User::AVATAR_SIZE_50) ?>" alt="" style="width: 30px"></div>
                                <?php $fullName = '';
                                if ($reader->user_s->name) {
                                    $fullName .= $reader->user_s->name;
                                }
                                if ($reader->user_s->surname) {
                                    $fullName .= ' ' . $reader->user_s->surname;
                                } ?>
                                <div class="subscribe-item-cell"><a href="<?= Yii::app()->createUrl('/user/view', array('id' => $reader->subscriber_id)) ?>" class="subscribe-item-a"><?= $reader->user_s->nick ?></a><?= trim($fullName) ? ' — ' . $fullName : '' ?></div>
                            </div>
                            <div class="subscribe-item-tools">
                                <?php $isSubscribed = UserSubscription::isAlreadySubscribed(Yii::app()->user->id, $reader->subscriber_id); ?>
                                <div class="subscribe-status"<?= $isSubscribed ? ' style="display: none;"' : '' ?>>
                                    <?= CHtml::ajaxLink(Yii::t('app', 'Подписаться'), Yii::app()->createUrl('/subscription/userSubscribe', array('id' => $reader->subscriber_id)), array(
                                        'dataType' => 'json',
                                        'context' => 'this',
                                        'success' => '$.proxy(userReaderSubscribeSuccess, this)',
                                    ), array('class' => 'subscribe-turn-on', 'id' => 'reader-subscribe-' . $reader->subscriber_id)) ?>
                                </div>
                                <div class="subscribe-status"<?= !$isSubscribed ? ' style="display: none;"' : '' ?>>
                                    <span class="subscribe-on">Подписан</span>
                                    <?= CHtml::ajaxLink(Yii::t('app', 'Отписаться'), Yii::app()->createUrl('/subscription/userUnsubscribe', array('id' => $reader->subscriber_id)), array(
                                        'dataType' => 'json',
                                        'context' => 'this',
                                        'success' => '$.proxy(userReaderUnsubscribeSuccess, this)',
                                    ), array('class' => 'subscribe-turn-off', 'id' => 'reader-unsubscribe-' . $reader->subscriber_id)) ?>
                                </div>
                                <div class="subscribe-tool">
                                    <a href="#" class="subscribe-msg">Сообщение</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="subscribe-bottom">Возможно, вы хотели посмотреть, <a href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => $user->id, 't' => 'sub')) ?>">кого читает</a>  <?= $user->nick ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
