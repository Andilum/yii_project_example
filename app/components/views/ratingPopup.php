<?php
/* @var $categories array */
/* @var $services array */
/* @var $ratings array */
/* @var $allocation DictAllocation */
?>
<div class="popup-score" id="rating-popup">
    <div class="popup-score-top">
        Оцените сервисы отеля <?= $allocation->name ?> <?= $allocation->alloccat->name ?>
        <a class="popup-score-close" href="#"></a>
    </div>
    <div class="popup-score-body">
        <table>
            <tbody>
            <tr>
                <td>
                    <ul class="popup-score-menu">
                        <li class="active">
                            <a href="#" data-category="0"><?=Yii::t('app','Все сервисы отеля')?></a>
                            <span class="popup-score-menu-active"></span>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="#" data-category="<?= $category->id ?>"><?=Yii::t('app',$category->name)?></a>
                                <span></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td class="popup-score-content-td">
                    <div class="popup-container-scroll">
                        <ul class="popup-score-content">
                            <?php foreach ($services as $service): ?>
                                <li class="popup-content-item" data-category="<?= $service->category->id ?>" data-service="<?= $service->id ?>">
                                    <div class="popup-score-object">
                                        <span class="popup-score-object-name"><?=Yii::t('app',$service->name)?></span>
                                        <span class="popup-score-object-type"><?=Yii::t('app',$service->category->name)?></span>
                                    </div>
                                    <div class="popup-score-point-block">
                                        <a class="popup-score-object-points" href="#" onclick="js_popup_select_open(this); return false;">
                                            <span class="popup-score-number"><?=Yii::t('app','Не знаю')?></span><i></i>
                                        </a>
                                        <span class="popup-score-object-points2"><?=Yii::t('app','Оцените сервис')?></span>
                                    </div>

                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="popup-score-bot">
        <a class="popup-score-reset" href="#"><?=Yii::t('app','Сбросить выбранные оценки')?></a>
        <a class="popup-score-send-btn" href="#"><?=Yii::t('app','сохранить оценки')?></a>
    </div>
    <div class="popup-score-overlay" onclick="js_overlay_close()"></div>
    <div class="popup-score-select">
        <div class="popup-score-select-top">
            <span class="popup-score-select-top-points"><?=Yii::t('app','Не знаю')?></span>
            <span class="popup-score-select-top-point2"><?=Yii::t('app','Оцените сервис')?></span>
        </div>
        <ul>
            <?php foreach ($ratings as $rating):
                switch ($rating->label) {
                    case '5':
                        $class = 'color-green-cyan';
                        break;
                    case '5-':
                        $class = 'color-brilliant-green';
                        break;
                    case '4':
                        $class = 'color-yellow-green';
                        break;
                    case '3':
                        $class = 'color-orange-yellow';
                        break;
                    case '2':
                        $class = 'color-strong-red';
                        break;
                    case '1':
                        $class = 'color-black';
                        break;
                    default:
                        $class = '';
                        break;
                } ?>
                <li data-rating-name="<?= $rating->label ?>" data-rating="<?= $rating->id ?>">
                    <span class="<?= $class ?>"><?= $rating->label ?></span>
                    <?= $rating->description ?>
                </li>
            <?php endforeach; ?>
            <li data-rating="0">
                <?=Yii::t('app','Не знаю')?>
            </li>
        </ul>
    </div>
</div>