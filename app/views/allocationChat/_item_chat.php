<?php
/* @var $this MessageController */
/* @var $data HotelChat */

$lastMsg=$data->getLastMessage();
?>
<div class="item-chat"  onclick="window.location.href = '<?= $this->createUrl('chat', array('chat' => $data->id)) ?>'" >
    <div class="c1">
    <div class="title"><?=CHtml::encode($data->title)?></div>
    <div class="desc"><?=CHtml::encode($data->description)?></div>
    </div>
    <div class="c2">
        <?php if ($lastMsg) { ?>
        <div class="msg"><?=  CHtml::encode($lastMsg->message)?></div>
        <div class="info"><a href="<?=$lastMsg->user_from->getUrl()?>"><?=CHtml::encode($lastMsg->user_from->name)?></a><time><?=DateHelper::getDateFormat2Post($lastMsg->date_create)?></time></div>
        <?php } else echo '<div class="emp">Сообщений нет</div>' ?>
    </div>
</div>