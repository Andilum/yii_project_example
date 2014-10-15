<td class="content-td-left">
    <div class="side-menu">
        <div class="side-menu-holder">
            <div class="side-menu-mover">
                <?php if ($allocation): ?>
                    <div class="hotel-profile profile-info">
                        <div class="hotel-profile-ava-wrap">
                            <img src="<?=$allocation->getPhotoUrl()?>" alt="">
                        </div>
                        <div class="hotel-profile-info">
                            <div class="hotel-profile-info-top">
                                <div class="hotel-profile-info-ttl"><?= $allocation->name ?></div>
                                <div class="hotel-profile-info-loc">
                                    <span class="my-profile-hotel-stars star<?= (int) $allocation->alloccat->name ?>"></span>
                                    <?= $allocation->re->co->name ?>, <?= $allocation->re->name ?>			
                                </div>
                            </div>
                            <div class="hotel-profile-info-mid">
                                <a href="#" class="hotel-profile-info-rss"><?=Yii::t('app', 'Подписаться на ленту')?></a>
                            </div>
                            <div class="hotel-profile-info-bot">
                                <a href="#" class="hotel-profile-info-bot-a"><?=Yii::t('app', 'Отель на TopHotels')?></a>
                                <a href="#" class="hotel-profile-info-bot-a"><?=Yii::t('app', 'Отель на Booking.com')?></a>
                            </div>
                        </div>
                    </div>
                <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?=Yii::t('app', 'В этом отеле')?></a></div>
                <?php $items = array(
                    'events' => array(
                        'label' => Yii::t('app', 'События в отеле'),
                        'url' => array('/allocation/view', 'id' => $allocation->id),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-feed leftmenu-li-feed-sel'),
                    ),
//                    array(
//                        'label' => Yii::t('app', 'Чаты'),
//                        'url' => array('/allocationChat/index', 'id' => $allocation->id),
//                        'itemOptions' => array('class' => 'leftmenu-li'),
//                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-chat')
//                    ),
//                    array(
//                        'label' => Yii::t('app', 'Актуальные темы'),
//                        'url' => array('/allocation/tag', 'id' => $allocation->id),
//                        'itemOptions' => array('class' => 'leftmenu-li'),
//                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-theme')
//                    ),
                    array(
                        'label' => Yii::t('app', 'Фотографии'),
                        'url' => array('/allocation/photo', 'id' => $allocation->id),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-pics')
                    ),
                    array(
                        'label' => Yii::t('app', 'Оценки сервисов'),
                        'url' => array('/allocation/rating', 'id' => $allocation->id),
                        'itemOptions' => array('class' => 'leftmenu-li'),
                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-marks')
                    ),
//                    array(
//                        'label' => Yii::t('app', 'Гости отеля'),
//                        'itemOptions' => array('class' => 'leftmenu-li'),
//                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-guests')
//                    ),
//                    array(
//                        'label' => Yii::t('app', 'Места отеля'),
//                        'itemOptions' => array('class' => 'leftmenu-li'),
//                        'linkOptions' => array('class' => 'leftmenu-li-a leftmenu-li-place')
//                    ),
                );
                if (Yii::app()->controller->id == 'allocation' && Yii::app()->controller->action->id == 'view') {
                    $url = Yii::app()->createUrl('/post/list', array('allocId' => $allocation->id));
                    $items['events']['template'] = '{menu}<a class="leftmenu-tail" href="#"></a>
                        <div class="leftmenu-options">
                            <ul>
                                <li class="leftmenu-option-li"><a class="leftmenu-option-li-a post-sorting" href="#" data-url="' . $url . '" data-sort="like" data-direction=".desc">Популярные события</a></li>
                                <li class="leftmenu-option-li"><a class="leftmenu-option-li-a post-sorting leftmenu-option-active" href="#" data-url="' . $url . '" data-sort="date" data-direction=".desc">Последние</a></li>
                            </ul>
                        </div>';
                }
                $this->widget('zii.widgets.CMenu', array(
                    'activeCssClass' => 'leftmenu-li-red',
                    'items' => $items,
                    'htmlOptions' => array(
                        'id' => 'left-hotel-menu',
                        'class' => 'leftbar-ul',
                    ),
                )); ?>
                <hr class="leftmenu-hr" />
                <?php endif; ?>
                <?php if (!Yii::app()->user->isGuest): ?>
                    <?php $allocations = AllocationSubscription::getList(Yii::app()->user->id); ?>
                    <?php if ($allocations): ?>
                        <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?=Yii::t('app', 'Мои отели')?></a></div>
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
                <div class="leftbar-ttl"><a class="leftbar-ttl-a leftbar-ttl-a-spoiler" href="#"><?/*=Yii::t('app', 'В мире')*/?></a></div>
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
                        'style' => 'display: none;',
                    ),
                ));*/ ?>
            </div>
        </div>
    </div>
</td>