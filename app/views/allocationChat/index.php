<?php
/* @var $this AllocationChatController */
/* @var $items ItemsPageLoader */
?>
<div class="b-chat chat-hotel">
    <div class="b-chat-ttl">
        Чаты отеля <!--<span class="b-chat-number">– 145</span>-->
        <?php if ($this->isHotelier()) { ?>
        <a class="b-chat-all" href="<?=$this->createUrl('create')?>" onclick="popup.getpop(this.href);return false;" >Создать чат</a>
        <?php } ?>
    </div>
    <div class="b-chatshotel-body clearfix items-chats-hotel">
        <?php $items->render(); ?>
    </div>
</div>