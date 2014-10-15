<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */
/* @var $commentList array */
/* @var $likeList array */
/* @var $userId int */

if ($userId == Yii::app()->user->id && !Yii::app()->user->isGuest): ?>
    <?php $user = User::model()->findByPk($userId); ?>
    <div class="b-feedback">
        <div class="b-feedback-ttl">
            <?=Yii::t('app', 'Напишите сообщение в ленту')?>
        </div>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'post-form',
            'action' => '/post/create',
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
            )
        )); ?>
        <div class="b-feedback-well">
            <div class="b-feedback-body">
                <?php echo $form->textArea(Post::model(), 'text', array('style' => 'display: none')); ?>
                <div class="b-feedback-txtarea-wrap" style="height: auto">
                    <div class="b-feedback-txtarea" contenteditable="true" style="min-height: 72px"></div>
                </div>
                <div class="b-feedback-smile" style="height: 92%;">
                    <a href="#" onclick="js_smile_select_open(); return false;">
                        <img class="b-feedback-smile-img" src="/i/smile.png" alt="">
                    </a>
                </div>
                <div class="b-feedback-smile-select">
                    <?php foreach (SmileHelper::$smiles as $smile): ?>
                        <a href="#" class="b-feedback-smile-i" onclick="return js_feedback_smile_select(this)"><img src="/i/blank.gif" alt="" class="b-feedback-emoji-smiles" style="background-position: 0 <?= $smile['position'] ?>px;"  data-code="<?= $smile['code'] ?>" /></a>
                    <?php endforeach; ?>
                </div>
                <div class="b-feedback-place" style="display: none">
                    <i></i>
                    <a  class="b-feedback-place-txt" href="#">
                        Москва, Россия <span>– <?=Yii::t('app','определено автоматически')?></span>
                    </a>
                    <input class="b-feedback-place-input" type="text" placeholder="место" onkeydown="js_select_place()">
                    <a class="b-feedback-place-close" href="#" onclick="js_feedback_open_input();
                            return false;"></a>
                    <div class="b-feedback-select-place">
                        <ul>
                            <li onclick="js_feedback_select_close(this)">
                                <span>Мос</span>ква, Россия
                            </li>
                            <li onclick="js_feedback_select_close(this)">
                                <span>Мос</span>ковская область, Россия
                            </li>
                            <li onclick="js_feedback_select_close(this)">
                                <span>Мос</span>оро, Бразилия
                            </li>
                            <li onclick="js_feedback_select_close(this)">
                                Тауэрский <span>мос</span>т, Лондон
                            </li>
                            <li onclick="js_feedback_select_close(this)">
                                <span>Мос</span>тачиано, Италия
                            </li>
                            <li>
                                <span>Все местоположения с совпадением </span>
                                <span class="b-feedback-select-place-all">- 345</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="b-feedback-score">
                    <i></i>
                    <span></span>
                    <a  class="b-feedback-score-more" href="#"></a>
                    <a class="b-feedback-score-close" href="#"></a>
                </div>
                <?php $this->widget('PhotoUpload'); ?>
            </div>
            <div class="b-feedback-bot">
                <span class="pull-left color-medium-grey m8t"><?=Yii::t('app', 'Добавьте')?> </span>
                <a class="btn bg-grey color-medium-grey" href="#" onclick="js_feedback_foto(this); return false;">
                    <i class="icon-foto"></i>
                    <?=Yii::t('app', 'ФОТО')?>
                </a>
                <a class="btn bg-grey color-medium-grey" id="post-form-add-rating" href="#" style="display: none">
                    <i class="icon-yes"></i>
                    <?=Yii::t('app', 'ОЦЕНКУ')?>
                </a>
                <a class="btn bg-grey color-medium-grey" href="#" onclick="js_feedback_place(this); return false;" style="display: none">
                    <i class="icon-chekin"></i>
                    <?=Yii::t('app', 'МЕСТО')?>
                </a>
                <a id="post-form-submit" class="btn pull-right bg-blue" href="#">
                    <?=Yii::t('app', 'ОТПРАВИТЬ')?>
                </a>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
<?php endif;

$this->widget('PostList', array(
    'dataProvider' => $dataProvider,
    'commentList' => $commentList,
    'likeList' => $likeList,
    'ajaxLink' => Yii::app()->createUrl('/post/list', array('userId' => $userId))
));