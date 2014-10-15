<?php
/* @var $this PostList */
/* @var $postId int */
/* @var $comment int */
/* @var $lastCommentId int */
?>
<div class="b-feedback"<?= !$comment ? ' style="display: none"' : '' ?>>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'comment-form-' . $postId,
        'enableClientValidation' => false,
        'action' => Yii::app()->createUrl('/comment/create'),
        'method' => 'post',
        'htmlOptions' => array('enctype' => 'multipart/form-data')
    )); ?>
        <div class="b-feedback-well">
            <div class="b-feedback-body">
                <div class="comment-form-area" style="width:auto;float:none;height: auto;min-height: 24px;">
                    <table class="comment-form-tbl">
                        <tr>
                            <td class="comment-form-td">
                                <?= $form->textArea(Comment::model(), 'text', array(
                                    'id' => 'Comment_text_' . $postId,
                                    'class' => 'comment-form-textarea',
                                    'style' => 'height: 18px;padding: 0;display: none',
                                )); ?>
                                <div class="b-feedback-txtarea comment-form-textarea" contenteditable="true" style="min-height: 22px;height: auto;"></div>
                            </td>
                            <td class="comment-form-td" style="vertical-align: top;"><a href="#" class="comment-smile-select"><img src="/i/smile.png" alt="" /></a></td>
                        </tr>
                    </table>
                </div>
                <?php $this->widget('PhotoUpload', array('postId' => $postId)); ?>
                <div class="b-feedback-smile-select">
                    <?php foreach (SmileHelper::$smiles as $smile): ?>
                        <a href="#" class="b-feedback-smile-i" onclick="return js_feedback_smile_select(this)">
                            <img src="/i/blank.gif" alt="" class="b-feedback-emoji-smiles" style="background-position: 0 <?= $smile['position'] ?>px;"  data-code="<?= $smile['code'] ?>" />
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="b-feedback-bot">
                <span class="pull-left color-medium-grey m8t"><?=Yii::t('app','Добавьте')?> </span>
                <a class="btn bg-grey color-medium-grey" id="comment-photo-<?= $postId ?>" href="#"><i class="icon-foto"></i><?=Yii::t('app','Фото')?></a>
                <a class="btn pull-right bg-blue" id="comment-submit-<?= $postId ?>" href="#"><?=Yii::t('app','Отправить')?></a>
            </div>
        </div>
        <?= $form->hiddenField(Comment::model(), 'post_id', array('value' => $postId, 'id' => 'Comment_post_id_' . $postId)); ?>
        <?= CHtml::hiddenField('lastCommentId', $lastCommentId, array('id' => 'lastCommentId-' . $postId)) ?>
    <?php $this->endWidget(); ?>
</div>