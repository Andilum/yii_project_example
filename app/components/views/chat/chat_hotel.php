<?php
/* @var $this Chat */
/* @var $chat HotelChat */
/* @var $id string */
/* @var $lastMessage MessageUser[] */
?>
<div id="<?= $id ?>" class="b-chat b-chat-hotel content-full-height" >
    <div class="chat-head">

        <div class="b-chat-ttl">
            Чат: <span><?= CHtml::encode($chat->title) ?></span>
            <a class="b-chat-back" href="<?= Yii::app()->controller->createUrl('index') ?>">Вернуться к списку чатов</a>
        </div>
        <div class="b-chat-status b-chat-status_user">
            <a href="<?= Yii::app()->createUrl('allocationchat/index') ?>" class="b-chat-status-back">← <span>Вернуться к списку диалогов</span></a>
            <a href="#" class="b-chat-status-compose">Удалить диалог</a>
        </div>

    </div>
    <div class="chat-body items-container">
           
            <!-- messages -->

        </div>
    
    <div class="chat-bottom">
    <div class="b-feedback b-feedback_dialog">							
        <div class="b-feedback-well">
             <form class="b-feedback-body">
                <div class="comment-form-area" style="width:auto;float:none;height: auto;min-height: 24px;">
                    <table class="comment-form-tbl">
                        <tr>
                            <td class="comment-form-td">
                                 <div class="chat-text-input b-feedback-txtarea comment-form-textarea" contenteditable="true" style="min-height: 41px;height: auto;"></div>
                            </td>
                            <td class="comment-form-td" style="vertical-align: top;"><a href="#" class="comment-smile-select"><img src="/i/smile.png" alt="" /></a></td>
                        </tr>
                    </table>
                </div>
     
                <div class="b-feedback-smile-select">
                    <?php foreach (SmileHelper::$smiles as $smile): ?>
                        <a href="#" class="b-feedback-smile-i" onmousedown="return js_feedback_smile_select(this)">
                            <img src="/i/blank.gif" alt="" class="b-feedback-emoji-smiles" style="background-position: 0 <?= $smile['position'] ?>px;"  data-code="<?= $smile['code'] ?>" />
                        </a>
                    <?php endforeach; ?>
                </div>
             </form>
            
            
            <div class="b-feedback-bot">
                <?php
                $this->widget('WLoadAttach');
                ?>
                <div class="actions pull-right">
                    <a class="btn  bg-blue send-link" href="#">										
                        ОТПРАВИТЬ
                    </a>
                </div>
            </div>
        </div>
    </div>
        </div>
</div>