<?php
/* @var $this AllocationChatController */
/* @var $chat HotelChat */

$this->pageTitle = 'Чат '.$chat->title;

$this->widget('Chat',array('chat'=>$chat));
?>