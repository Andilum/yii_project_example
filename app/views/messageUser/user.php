<?php
/* @var $this MessageUserController */
/* @var $userTo User */

$this->pageTitle = 'Чат с пользователем';

$this->widget('Chat',array('user_to'=>$userTo));
?>