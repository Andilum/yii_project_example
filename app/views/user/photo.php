<?php
/* @var $this UserController */
/* @var $user User */
/* @var $dataProvider CActiveDataProvider */
/* @var $type string */

/* @var EClientScript $clientScript */
$clientScript = Yii::app()->getClientScript();
$baseUrl = Yii::app()->getBaseUrl();
$clientScript->registerScriptFile($baseUrl . '/js/User/photo.js');
$clientScript->registerScriptFile(Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.components.assets.PostList') . '/postList.js'));
?>
<div class="userphoto">
    <div class="userphoto-ttl">
        Фотографии <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $user->id)) ?>"><?= $user->nick ?></a>
        <span class="userphoto-counter">– <?= $dataProvider->totalItemCount ?></span>
    </div>
    <div class="userphoto-search">
        <input class="userphoto-search-input" name="tagName" placeholder="Поиск фотографий по хэштегу" style="width: 365px">
        <?= CHtml::ajaxButton(Yii::t('search', 'Найти фотографии'), Yii::app()->createUrl('/user/photo', array('id' => $user->id)), array(
            'data' => array('page' => 'js:page', 'tagName' => 'js:$(\'input[name="tagName"]\').val()', 'all' => '1'),
            'dataType' => 'json',
            'context' => 'this',
            'success' => '$.proxy(photoSearchSuccess, this)',
        ), array('id' => 'photo-search', 'class' => 'userphoto-search-button', 'style' => 'cursor: pointer')) ?>
    </div>
    <div class="userphoto-layout">
        <?php $this->renderPartial('_photoList', array('dataProvider' => $dataProvider, 'type' => $type)) ?>
    </div>
    <?php if ($dataProvider->totalItemCount > Photo::DEFAULT_PHOTO_LIMIT_ON_PAGE): ?>
        <hr size="20">
        <div class="userphoto-more">
            <img src="/i/comment-bottom-more.png" alt="">
            <?= Yii::t('search', 'Показать') ?> <?= CHtml::ajaxLink(Yii::t('search','следующие 20 фотографий'), Yii::app()->createUrl('/user/photo', array('id' => $user->id)), array(
                'data' => array('page' => 'js:page + 1', 'type' => $type),
                'dataType' => 'json',
                'context' => 'this',
                'success' => '$.proxy(nextPhotoSuccess, this)',
            ), array('id' => 'next-photo')) ?>
        </div>
    <?php endif; ?>
    <hr size="20">
</div>