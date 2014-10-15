<td class="content-td-left">
    <div class="left-scroll">
        <?php if (!Yii::app()->user->isGuest): ?>
        <div class="profile">
            <table class="profile-tbl">
                <tr>
                    <td class="profile-td"><a href="#"><img src="<?= User::getAvatarPath(Yii::app()->user->id, User::AVATAR_SIZE_50) ?>" width="60" alt="" /></a></td>
                    <td class="profile-td">
                        <a class="profile-a" href="#"><?=Yii::t('app', 'Мой профиль')?></a>
                        <a class="profile-a" href="#"><?=Yii::t('app', 'Настройки')?></a>
                    </td>
                </tr>
            </table>
        </div>
        <hr size="18" />
        <?php endif; ?>
        <!--<div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#">В отеле</a></div>
        <ul class="leftbar-ul">
                <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-feed" href="#">Лента событий отеля</a></li>
                <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-chat" href="#">Чаты</a></li>
                <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-theme" href="#">Актуальные темы</a></li>
                <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-pics" href="#">Фотографии</a></li>
                <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-marks" href="#">Оценки сервисов</a></li>
                <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-guests" href="#">Гости отеля</a></li>
                <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-place" href="#">Места отеля</a></li>
        </ul>-->
        <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?=Yii::t('app', 'В мире')?></a></div>
        <?php $items = array(
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
        )); ?>
        <hr class="leftmenu-hr" />
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
        <?php $this->widget('UserLanguageMenu'); ?>
        <hr class="leftmenu-hr" />
        <div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?=Yii::t('app', 'На вашем смартфоне')?></a></div>
        <ul class="leftbar-ul">
            <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-apple" href="#">AppStore</a></li>
            <li class="leftmenu-li"><a class="leftmenu-li-a leftmenu-li-andr" href="#">Android Market</a></li>
        </ul>
    </div>
</td>