<?php
/* @var $user User */
?>
<div class="userinfo">
    <div class="userinfo-ttl">
        <?=Yii::t('user', 'Личные данные')?> <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $user->id)) ?>"><?= $user->nick ?></a>
    </div>
    <div class="userinfo-section">
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'Пол')?></div>
            <div class="userinfo-value"><a href="#">
                <?php if ($user->sex == 'male'): ?>
                     <?=Yii::t('user', 'Мужчина')?>
                <?php elseif ($user->sex == 'female'): ?>
                     <?=Yii::t('user', 'Женщина')?>
                <?php else: ?>
                    <?=Yii::t('user', 'Не указано')?>
                <?php endif; ?>
            </a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'День рождения')?></div>
            <div class="userinfo-value">
                <?php if ($user->birthday): ?>
                    <?= Yii::app()->dateFormatter->format("dd MMMM y", $user->birthday) ?> <span class="userinfo-age">— <?= floor((time()-strtotime($user->birthday))/(60*60*24*365.25)) ?> <?=Yii::t('user', 'год')?></span>
                <?php else: ?>
                    <?=Yii::t('user', 'Не указано')?>
                <?php endif; ?>
            </div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'Родной город')?></div>
            <div class="userinfo-value">
                <a href="#">
                    <?php if (isset($user->ct->name)): ?>
                        <?= $user->ct->name ?>
                    <?php else: ?>
                        <?=Yii::t('user', 'Не указано')?>
                    <?php endif; ?>
                </a>,
                <a href="#">
                    <?php if (isset($user->co->name)): ?>
                        <?= $user->co->name ?>
                    <?php else: ?>
                        <?=Yii::t('user', 'Не указано')?>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'Семейное положение')?></div>
            <div class="userinfo-value"><a href="#"><?=Yii::t('user', 'В активном поиске')?></a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label">Языки</div>
            <div class="userinfo-value">
                <a href="#">Русский</a>,
                <a href="#">Английский</a>,
                <a href="#">Французский</a>
            </div>
        </div>
    </div>
    <div class="userinfo-ttl">
        <?=Yii::t('user', 'Контактная информация')?>
    </div>
    <div class="userinfo-section">
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'Место жительства')?></div>
            <div class="userinfo-value"><a href="#"><?= $user->co->name ?></a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'Телефон')?></div>
            <div class="userinfo-value"><a href="#">
                <?php if ($user->phone): ?>
                    <?= $user->phone ?>
                <?php else: ?>
                    <?=Yii::t('user', 'Не указано')?>
                <?php endif; ?>
            </a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label">Skype</div>
            <div class="userinfo-value"><a href="#">utafoa</a></div>
        </div>
        <?php if ($user->show_email): ?>
            <div class="userinfo-row">
                <div class="userinfo-label">E-mail</div>
                <div class="userinfo-value"><a href="#">
                        <?php if ($user->email): ?>
                            <?= $user->email ?>
                        <?php else: ?>
                            <?=Yii::t('user', 'Не указано')?>
                        <?php endif; ?>
                    </a></div>
            </div>
        <?php endif; ?>
        <?php if ($user->show_icq): ?>
            <div class="userinfo-row">
                <div class="userinfo-label">ICQ</div>
                <div class="userinfo-value"><a href="#">
                    <?php if ($user->icq): ?>
                        <?= $user->icq ?>
                    <?php else: ?>
                        <?=Yii::t('user', 'Не указано')?>
                    <?php endif; ?>
                </a></div>
            </div>
        <?php endif; ?>
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'Веб-сайт')?></div>
            <div class="userinfo-value"><a href="#">
                <?php if ($user->www): ?>
                    <?= $user->www ?>
                <?php else: ?>
                    <?=Yii::t('user', 'Не указано')?>
                <?php endif; ?>
            </a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label">Facebook</div>
            <div class="userinfo-value"><a href="#">
                <?php if (isset($user->dop->facebook) && $user->dop->facebook): ?>
                    <?= $user->dop->facebook ?>
                <?php else: ?>
                    <?=Yii::t('user', 'Не указано')?>
                <?php endif; ?>
            </a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label">Вконтакте</div>
            <div class="userinfo-value"><a href="#">
                <?php if (isset($user->dop->vkontakte) && $user->dop->vkontakte): ?>
                    <?= $user->dop->vkontakte ?>
                <?php else: ?>
                    <?=Yii::t('user', 'Не указано')?>
                <?php endif; ?>
            </a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label">Twitter</div>
            <div class="userinfo-value"><a href="#">
                <?php if (isset($user->dop->twitter) && $user->dop->twitter): ?>
                    <?= $user->dop->twitter ?>
                <?php else: ?>
                    <?=Yii::t('user', 'Не указано')?>
                <?php endif; ?>
            </a></div>
        </div>
        <div class="userinfo-row">
            <div class="userinfo-label">RuTraveller</div>
            <div class="userinfo-value"><a href="#">rutraveller.ru/digitaldog</a></div>
        </div>
    </div>
    <?php if ($user->description): ?>
        <div class="userinfo-ttl">
            <?=Yii::t('user', 'О себе')?>
        </div>
        <div class="userinfo-section">
            <?= $user->description ?>
        </div>
    <?php endif; ?>
    <div class="userinfo-ttl">
        <?=Yii::t('user', 'Подписки и читатели')?>
    </div>
    <div class="userinfo-section subscriptions">
        <div class="userinfo-row">
            <?php $readers = UserSubscription::getReadersCount($user->id); ?>
            <div class="userinfo-label"><?=Yii::t('user', 'Читатели')?></div>
            <div class="userinfo-value"><a href="#"><?=Yii::t('user', '{n} турист|{n} туриста|{n} туристов',$readers)?></a></div>
        </div>
        <div class="userinfo-row">
            <?php $subscriptions = UserSubscription::getSubscriptionsCount($user->id); ?>
            <?php $allocations = AllocationSubscription::getSubscriptionsCount($user->id) ?>
            <div class="userinfo-label"><?=Yii::t('user', 'Читает')?></div>
            <div class="userinfo-value"><a href="#"><?=Yii::t('user', '{n} турист|{n} туриста|{n} туристов',$subscriptions).' '.Yii::t('user', 'и {n} отель|и {n} отеля|и {n} отелей',$allocations)?></a></div>
        </div>
        <?php if (Yii::app()->user->id != $user->id): ?>
            <?php $isSubscribed = UserSubscription::isAlreadySubscribed(Yii::app()->user->id, $user->id); ?>
            <?= CHtml::ajaxLink('<i class="userinfo-feed-icon"></i>' . Yii::t('app', 'Подписаться на ленту'), Yii::app()->createUrl('/subscription/userSubscribe', array('id' => $user->id)), array(
                'dataType' => 'json',
                'context' => 'this',
                'success' => '$.proxy(userSubscribeSuccess, this)',
            ), array('class' => 'userinfo-feed', 'style' => $isSubscribed ? 'display: none' : '')) ?>
            <?= CHtml::ajaxLink('<i class="userinfo-feed-icon"></i>' . Yii::t('app', 'Отписаться'), Yii::app()->createUrl('/subscription/userUnsubscribe', array('id' => $user->id)), array(
                'dataType' => 'json',
                'context' => 'this',
                'success' => '$.proxy(userUnsubscribeSuccess, this)',
            ), array('class' => 'userinfo-feed unsubscribe', 'style' => !$isSubscribed ? 'display: none' : '')) ?>
        <?php endif; ?>
    </div>
    <div class="userinfo-ttl">
         <?=Yii::t('user', 'Активность')?>
    </div>
    <div class="userinfo-section">
        <div class="userinfo-row">
            <div class="userinfo-label"><?=Yii::t('user', 'Онлайн')?></div>
            <div class="userinfo-value"><?=Yii::t('user', 'Был на сайте {date}',array('{date}'=>Yii::app()->getDateFormatter()->format('dd MMM yyyy h:m', time()-3600)))?></div>
        </div>
    </div>
    <div class="userinfo-rss">
      <?=Yii::t('user', 'Перейти к <a href="{url}">ленте событий</a>',array('{url}'=>'/user/'.$user->id))?>   <?= $user->nick ?>
    </div>
</div>
<hr size="20">