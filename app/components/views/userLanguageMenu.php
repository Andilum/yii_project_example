<?php
/** @var $userLanguage string */
?>
<div class="leftbar-ttl"><a class="leftbar-ttl-a" href="#"><?=Yii::t('app', 'Мои языки')?></a></div>
<ul class="leftbar-ul" id="user-languages">
    <li class="leftmenu-li<?= $userLanguage == UserLanguages::ALL_LANGUAGE ? ' leftmenu-li-green' : '' ?>">
        <a class="leftmenu-li-a leftmenu-li-lang" href="#" data-user-lang="<?= UserLanguages::ALL_LANGUAGE ?>"><?=Yii::t('app', 'Посты на всех языках')?></a>
    </li>
    <li class="leftmenu-li<?= $userLanguage == UserLanguages::USER_LANGUAGE ? ' leftmenu-li-green' : '' ?>">
        <a class="leftmenu-li-a leftmenu-li-lang" href="#" data-user-lang="<?= UserLanguages::USER_LANGUAGE ?>"><?=Yii::t('app', 'Только по-русски')?></a>
    </li>
</ul>