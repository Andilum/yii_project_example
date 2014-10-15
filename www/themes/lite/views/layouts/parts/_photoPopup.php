<div class="pop-up-holder post-pp">
    <div class="post-pp-l">
        <ul class="post-pp-gallery"></ul>
        <div class="post-pp-gallery-nav">
            <a href="#" class="post-pp-gallery-nav-a post-pp-gallery-back"></a>
            <a href="#" class="post-pp-gallery-nav-a post-pp-gallery-next"></a>
        </div>
    </div>
    <div class="post-pp-r" style="position: relative">
        <img class="preloader" src="/i/preloader.gif" />
        <div class="post-pp-r-head">
            <a href="#" class="post-pp-userpic-wrap"></a>
            <div class="post-pp-user-details">
                <a href="#"></a> <span class="post-pp-time"></span>
                <div class="post-pp-loc"></div>
            </div>
            <a href="#" class="post-pp-close"></a>
        </div>
        <div class="pp-custom-scroll">
            <div class="comment-item" data-post-id="">
                <div class="comment-item-in">
                    <div class="comment-txt"></div>
                    <div class="comment-bottom"></div>
                    <div class="comment-dialog"></div>
                </div>
            </div>
        </div>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'comment-form-popup',
            'enableClientValidation' => false,
            'action' => Yii::app()->createUrl('/comment/create'),
            'method' => 'post',
            'htmlOptions' => array('enctype' => 'multipart/form-data')
        )); ?>
            <div class="comment-form">
                <div class="comment-form-area">
                    <table class="comment-form-tbl">
                        <tr>
                            <td class="comment-form-td">
                                <?= $form->textArea(Comment::model(), 'text', array(
                                    'id' => 'Comment_text_popup',
                                    'class' => 'comment-form-textarea',
                                    'style' => 'display: none',
                                )); ?>
                                <div class="b-feedback-txtarea comment-form-textarea" contenteditable="true" style="min-height: 22px"></div>
                            </td>
                            <td class="comment-form-td"><a href="#" class="comment-smile-select"><img src="/i/smile.png" alt="" /></a></td>
                        </tr>
                    </table>
                </div>
                <div class="b-feedback-smile-select" style="top: 240px;left: 263px;">
                    <?php foreach (SmileHelper::$smiles as $smile): ?>
                        <a href="#" class="b-feedback-smile-i" onclick="return js_feedback_smile_select(this)">
                            <img src="/i/blank.gif" alt="" class="b-feedback-emoji-smiles" style="background-position: 0 <?= $smile['position'] ?>px;"  data-code="<?= $smile['code'] ?>" />
                        </a>
                    <?php endforeach; ?>
                </div>
                <a class="comment-form-btn" id="comment-submit-popup" href="#"><?=Yii::t('app','Отправить')?></a>
            </div>
            <?= $form->hiddenField(Comment::model(), 'post_id', array('value' => '', 'id' => 'Comment_post_id_popup')); ?>
            <?= CHtml::hiddenField('lastCommentId', '', array('id' => 'lastCommentId-popup')) ?>
        <?php $this->endWidget(); ?>
    </div>
</div>