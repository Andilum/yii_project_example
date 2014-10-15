<?php
/* @var $this SiteController */
/* @var $dataProvider CActiveDataProvider */
/* @var $type string */
/* @var $page int */
/* @var $sort string */

/* @var EClientScript $clientScript */
$clientScript = Yii::app()->getClientScript();
$baseUrl = Yii::app()->getBaseUrl();
$clientScript->registerScriptFile($baseUrl . '/js/Rating/index.js');
?>
<div class="content-full-height">
    <div class="searchresults">
        <div class="searchresults-body">
            <div class="searchresults-tab">
                <div class="searchresults-search">
                    <?= CHtml::ajaxButton(Yii::t('search', 'Найти отель'), Yii::app()->createUrl('/rating/index', array('type' => $type)), array(
                        'data' => array('page' => 'js:page', 'sort' => "js:sort", 'name' => 'js:$(\'input[name="name"]\').val()', 'all' => '1'),
                        'dataType' => 'json',
                        'context' => 'this',
                        'success' => '$.proxy(itemSearchSuccess, this)',
                    ), array('id' => 'item-search', 'class' => 'searchresults-button', 'style' => 'cursor: pointer')) ?>
                    <div class="searchresults-input-wrap"><input class="searchresults-input" name="name" placeholder="Поиск отеля"></div>
                </div>
                <div class="searchresults-filter">
                    <ul>
                        <li>Сортировать</li>
                        <?php foreach ($dataProvider->sort->attributes as $sortParam => $attr): ?>
                            <?php
                            $url = Yii::app()->createUrl('/rating/index', array('type' => $type));
                            $direction = '.desc';
                            $sortClass = '';
                            if ($sort[0] == $sortParam) {
                                if (isset($sort[1]) && $sort[1] == 'desc') {
                                    $sortClass = ' searchresults-filter-a_down';
                                    $direction = '';
                                } else {
                                    $direction = '.desc';
                                    $sortClass = ' searchresults-filter-a_up';
                                }
                            }
                            ?>
                            <li><a href="#" class="searchresults-filter-a<?= $sortClass ?>" data-url="<?= $url ?>" data-sort="<?= $sortParam ?>" data-direction="<?= $direction ?>"><?= $attr['label'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="searchresults-tab-inner">
                    <?php $this->renderPartial('_ratingList', array('dataProvider' => $dataProvider, 'page' => $page, 'type' => $type)) ?>
                    <?php if ($dataProvider->totalItemCount > SR::DEFAULT_LIMIT): ?>
                        <div class="searchresults-rating-more">
                            <img src="/i/comment-bottom-more.png" alt=""><?=Yii::t('search', 'Показать')?>
                            <?= CHtml::ajaxLink(Yii::t('search', 'следующие 50 отелей'), Yii::app()->createUrl('/rating/index', array('type' => $type)), array(
                                'data' => array('page' => 'js:page + 1', 'sort' => "js:sort", 'name' => 'js:$(\'input[name="name"]\').val()'),
                                'dataType' => 'json',
                                'context' => 'this',
                                'success' => '$.proxy(nextItemSuccess, this)',
                            ), array('id' => 'next-items')) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>