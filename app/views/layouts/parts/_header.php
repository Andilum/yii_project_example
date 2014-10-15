<header>
    <div class="header">
        <div class="header-in">
            <div class="wrapper">
                <a class="header-logo" href="/"><img src="/i/logo.png" alt="" /></a>
                <form action="<?= Yii::app()->createUrl('search/index') ?>" method="get" >
                    <div class="header-search">
                        <span class="header-search-txt">Найти</span>
                        <div class="header-search-pro">
                            <?php
                            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                'name' => 't',
                                'id' => 'Search_text',
                                'source' => Yii::app()->createUrl('search/autocomplete'),
                                'options' => array(
                                    'minLength' => '2',
                                    'showAnim' => 'fold',
                                    // обработчик события, выбор пункта из списка
                                    'select' => 'js: function(event, ui) {
                                            this.value = ui.item.text;
                                            if (ui.item.url)
                                            window.location.href=ui.item.url;
                                        }',
                                ),
                                'htmlOptions' => array(
                                    'class' => "header-search-pro-input",
                                    'placeholder' => "отель или пользователя"
                                ),
                            ));
                            ?>
                        </div>
                        <input class="header-search-submit" type="submit" value=""  />
                    </div>
                </form>
                <div class="header-right">
                    <?php
                    if (Yii::app()->user->isGuest) {
                        $this->widget('AuthLink', array(
                            'showLink' => false,
                            'selector' => '#project_auth_link',
                        ));
                    }
                    ?>
                    <?php if (!Yii::app()->user->isGuest): ?>
                        <div class="header-nick">
                            <a href="#"><img src="<?= User::getAvatarPath(Yii::app()->user->id, User::AVATAR_SIZE_25) ?>" width="20" alt="" /></a>
                            <a class="header-nick-a" href="<?= Yii::app()->createUrl('user/view', array('id' => Yii::app()->user->id)) ?>"><?= Yii::app()->user->nick ?></a>
                        </div>
                        <a class="header-mine" href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => Yii::app()->user->id, 't' => 'alloc')) ?>"><?= Yii::t('app', 'Мои отели') ?></a>
                        <i class="header-delimiter"></i>
                        <a class="header-mine" href="<?= Yii::app()->createUrl('/user/subscriptions', array('id' => Yii::app()->user->id)) ?>"><?= Yii::t('app', 'Мои подписчики') ?></a>
                        <i class="header-delimiter"></i>
                        <div class="header-ics">
                            <a class="header-ic" href="#"><img src="/i/ic1.png" alt="" /></a>
                            <a class="header-ic" href="<?=Yii::app()->createUrl('messageUser/chats')?>"><img src="/i/ic2.png" alt="" />
                                <?php if (($count = MessageUser::getCountNoRead(Yii::app()->user->id))) { ?>
                                    <span><?= $count ?></span>
                                <?php } ?>
                            </a>
                            <a class="header-ic" href="#"><img src="/i/ic3.png" alt="" /></a>
                        </div>
                        <i class="header-delimiter"></i>
                        <div class="header-ics"><a class="header-ic" href="#"><img src="/i/ic4.png" alt="" /></a></div>
                    <?php else: ?>
                        <div class="header-nick">
                            <a id="project_auth_link" class="header-nick-a" href="/auth/login"><?= Yii::t('app', 'Войти') ?></a>
                        </div>
                    <?php endif; ?>
                    <i class="header-delimiter"></i>
                    <?php
                    //для 2х языков
                    $otherLang = Yii::app()->getLanguage() == 'ru' ? 'en' : 'ru';
                    ?>
                    <a class="header-lang" href="<?= Yii::app()->createUrl(Yii::app()->controller->route, array_merge($_GET, array('lang' => $otherLang))) ?>"><?= $otherLang ?></a>
                </div>
            </div>
        </div>
    </div>
</header>