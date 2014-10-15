<?php
/* @var $this SearchController */
/* @var $t string искомая строка */
/* @var $dataSearch array масив всех элементов поиска  */
/* @var $countAll integer  */

?>


<div class="searchresults">

<?php require 'parts/_head.php'; ?>
    <div class="searchresults-body">
        <div class="searchresults-tab">
          <?php require 'parts/_form.php'; ?>
            
            <?php
            if ($countAll) {
                foreach ($dataSearch as $type => $value) {
                    if (!$value['count'])
                        continue;
                    ?>

                    <div class="searchresults-tab-inner">
                        <div class="searchresults-tab-ttl"><?= Yii::t('app', $value['countText'], $value['count']) ?></div>
                        <?php
                        foreach ($value['dataProvider']->getData() as $model) {
                            $this->renderPartial($value['view'], array('data' => $model));
                        }
                        ?>
                        <div class="searchresults-all"><a href="<?= $this->createUrl('index', array('type' => $type, 't' => $t)) ?>" class="searchresults-all-a"><span><?= $value['all_text'] ?></span> →</a></div>
                    </div>

                    <?php
                }
            } else {
                ?>


                <div class="searchresults-null">
                    К сожалению, мы ничего не нашли<br>
                    по вашему запросу.<br>
                    Попробуйте изменить запрос<br>
                    и поискать снова.
                </div>
            <?php } ?>

        </div>

        <div class="searchresults-bottom">
            Возможно, вас заинтересует <a href="#">рейтинг отелей</a>
        </div>
    </div>

</div>