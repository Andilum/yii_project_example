<?php
/* @var $this MessageUserController */
/* @var $userTo User */
/* @var $items ItemsPageLoader */
?>
<div class="b-chat">
    <div class="b-chat-ttl">
        Мои чаты <!--<span class="b-chat-number">– 145</span>-->
        <a class="b-chat-all" href="#">Все мои чаты</a>
    </div>
    <?php
    $allCount = MessageUser::getCountNoRead(Yii::app()->user->id);
    if ($allCount) {
        ?>
        <div class="b-chat-status">
            <a href="#">
                Личные сообщения
            </a>
            <span class="b-chat-status-msg">+<?= $allCount ?></span>
            <!-- <span class="b-chat-status-txt">Отельные чаты</span>
            <span class="b-chat-status-chats">+3 467</span>-->
        </div>
    <?php } ?>
    <div class="b-chatshotel-body clearfix">
        <?php $items->render(); ?>
    </div>
</div>