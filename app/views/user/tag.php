<?php
/* @var $this UserController */
/* @var $user User */
/* @var $dataProvider CSqlDataProvider */
/* @var $sort array */

/* @var EClientScript $clientScript */
$clientScript = Yii::app()->getClientScript();
$baseUrl = Yii::app()->getBaseUrl();
$clientScript->registerScriptFile($baseUrl . '/js/User/tag.js');
?>
<div class="hashtags">
    <div class="hashtags-ttl">
    <?=Yii::t('search', 'Хэштеги')?>  <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $user->id)) ?>"><?= $user->nick ?></a>
    </div>
    <div class="hashtags-search">
        <input class="hashtags-search-input" name="tagName" placeholder="<?=Yii::t('search', 'Поиск по хэштегу')?>" style="width: 445px;">
        <?= CHtml::ajaxButton(Yii::t('search', 'Найти'), Yii::app()->createUrl('/user/tag', array('id' => $user->id)), array(
            'data' => array('page' => 'js:tagPage', 'sort' => "js:sort", 'tagName' => 'js:$(\'input[name="tagName"]\').val()', 'all' => '1'),
            'dataType' => 'json',
            'context' => 'this',
            'success' => '$.proxy(tagSearchSuccess, this)',
        ), array('id' => 'tag-search', 'class' => 'hashtags-search-button', 'style' => 'cursor: pointer')) ?>
    </div>
    <div class="hashtags-table">
        <div class="hashtags-table-row hashtags-table-row_ttl">
            <div class="hashtags-table-cell">
            </div>
            <?php foreach ($dataProvider->sort->attributes as $sortParam => $attr): ?>
                <div class="hashtags-table-cell">
                    <?php $url = Yii::app()->createUrl('/user/tag', array('id' => $user->id));
                    $direction = '';
                    $sortClass = '';
                    if ($sort[0] == $sortParam) {
                        if (isset($sort[1]) && $sort[1] == 'desc') {
                            $sortClass = ' hashtags-table-sorting_active_desc';
                        } else {
                            $direction = '.desc';
                            $sortClass = ' hashtags-table-sorting_active';
                        }
                    } ?>
                    <a class="hashtags-table-sorting<?= $sortClass ?>" href="#" data-url="<?= $url ?>" data-sort="<?= $sortParam ?>" data-direction="<?= $direction ?>"><?= $attr['label'] ?></a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php $this->renderPartial('_tagList', array('dataProvider' => $dataProvider)) ?>
        <div class="hashtags-table-row hashtags-table-row_popular">
            <?=Yii::t('search', 'Смотреть все <a href="{url}">популярные хэштеги</a>',array('{url}'=>'#'))?>
        </div>
    </div>
    <?php if ($dataProvider->totalItemCount > Tag::POST_LIMIT_ON_FIRST_LOAD): ?>
        <hr size="20">
        <div class="hashtags-more">
            <img src="/i/comment-bottom-more.png" alt="">
            <?=Yii::t('search', 'Показать')?>   <?= CHtml::ajaxLink(Yii::t('search','следующие 20 хэштегов'), Yii::app()->createUrl('/user/tag', array('id' => $user->id)), array(
                'data' => array('page' => 'js:tagPage + 1', 'sort' => "js:sort", 'tagName' => 'js:$(\'input[name="tagName"]\').val()'),
                'dataType' => 'json',
                'context' => 'this',
                'success' => '$.proxy(nextTagSuccess, this)',
            ), array('id' => 'next-tag')) ?>
        </div>
    <?php endif; ?>
    <hr size="20">
</div>