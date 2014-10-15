<?php
//левое меню своё
$user = Yii::app()->controller->model;
/* @var $user User */
?>

<td class="content-td-left">
    <div class="side-menu">
        <div class="side-menu-holder">
            <div class="side-menu-mover">
                <div class="profile profile-info">
                    <table class="profile-tbl">
                        <tr>
                            <td class="profile-td">
                                <a href="<?=$user->getUrl()?>"><img src="<?= User::getAvatarPath(Yii::app()->user->id, User::AVATAR_SIZE_50) ?>" width="60" alt="" /></a>
                            </td>
                            <td class="profile-td">
                                <a class="profile-a" href="#"><?=Yii::t('app','Лента')?></a>
                                <a class="profile-a" href="#"><?=Yii::t('app','Настройки')?></a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?=Yii::t('app','Мой профиль')?></a></div>
                <?php $items = array(
                    array(
                        'label' => Yii::t('app','Информация'),
                        'url' => array('user/info', 'id' => $user->id),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-info'),
                    ),
                    array(
                        'label' => Yii::t('app','Лента событий'),
                        'url' => array('user/view', 'id' => $user->id),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-feed leftmenu-li-feed-sel'),
                    ),
                    array(
                        'label' => Yii::t('app','Подписки'),
                        'url' => array('user/subscriptions', 'id' => $user->id),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-guests'),
                    ),
//                    array(
//                        'label' => Yii::t('app','Актуальные темы'),
//                        'url' => array('user/tag', 'id' => $user->id),
//                        'itemOptions' => array('class' => 'leftmenu-li'),
//                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-theme'),
//                    ),
                    array(
                        'label' => Yii::t('app','Фотографии'),
                        'url' => array('user/photo', 'id' => $user->id),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-pics'),
                    ),
                    array(
                        'label' => Yii::t('app','Оценки сервисов'),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-marks'),
                    ),
//                    array(
//                        'label' => Yii::t('app','Чаты'),
//                        'url' => array('messageUser/chats'),
//                        'itemOptions' => array('class' => 'leftmenu-li'),
//                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-chat'),
//                    ),
                    array(
                        'label' => Yii::t('app','Журнал моих действий'),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-pen'),
                    ),
                );
                $this->widget('zii.widgets.CMenu', array(
                    'activeCssClass' => 'leftmenu-li-red',
                    'items' => $items,
                    'htmlOptions' => array(
                        'id' => 'left-user-menu',
                        'class' => 'leftbar-ul',
                    ),
                )); ?>
                <?php if (!Yii::app()->user->isGuest): ?>
                    <?php $allocations = AllocationSubscription::getList(Yii::app()->user->id); ?>
                    <?php if ($allocations): ?>
                        <hr class="leftmenu-hr" />
                        <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?=Yii::t('app','Мои отели')?></a></div>
                        <?php $items = array();
                        foreach ($allocations as $alloc) {
                            $label = $alloc->allocation->name . ' ' . $alloc->allocation->alloccat->name;
                            $label = strlen($label) > 20 ? substr($label, 0, 20) . '...' : $label;
                            $items[] = array(
                                'label' => $label,
                                'url' => array('/allocation/view', 'id' => $alloc->allocation_id),
                                'itemOptions' => array('class' => 'leftmenu-li'),
                                'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-feed leftmenu-li-feed-sel'),
                                'template' => '{menu}<a class="leftmenu-del" href="#"></a>',
                            );
                        }
                        $this->widget('zii.widgets.CMenu', array(
                            'activeCssClass' => 'leftmenu-li-green',
                            'items' => $items,
                            'htmlOptions' => array(
                                'id' => 'left-hotel-menu',
                                'class' => 'leftbar-ul',
                            ),
                        )); ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php /*$this->widget('UserLanguageMenu'); */?><!--
                <hr class="leftmenu-hr" />
                <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?/*=Yii::t('app','в мире')*/?></a></div>
                --><?php /*$items = array(
                    array(
                        'label' => Yii::t('app','Найдите отели'),
                        'url' => array('/search/index', 'type' => 'allocation'),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-find'),
                    ),
                    array(
                        'label' => Yii::t('app','Лента событий отелей'),
                        'url' => array('/site/index'),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-feed leftmenu-li-feed-sel'),
                    ),
                    array(
                        'label' => Yii::t('app','Актуальные темы'),
                        'url' => array('/tag/index'),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-theme'),
                    ),
                    array(
                        'label' => Yii::t('app','Новые фотографии'),
                        'url' => array('/photo/index'),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-pics'),
                    ),
                    array(
                        'label' => Yii::t('app','Пользователи'),
                        'url' => array('/rating/index'),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-guests'),
                    ),
                );
                $this->widget('zii.widgets.CMenu', array(
                    'activeCssClass' => 'leftmenu-li-red',
                    'items' => $items,
                    'htmlOptions' => array(
                        'id' => 'left-world-menu',
                        'class' => 'leftbar-ul',
                    ),
                ));*/ ?>
                <!--<hr class="leftmenu-hr" />
                <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?/*=Yii::t('app','На вашем смартфоне')*/?></a></div>
                <ul class="leftbar-ul">
                    <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-apple" href="#">AppStore</a></li>
                    <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-andr" href="#">Android Market</a></li>
                </ul>-->
            </div>
        </div>
    </div>
</td>