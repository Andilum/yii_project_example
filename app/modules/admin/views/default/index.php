<div class="rows_bl">
    <?php
    $modules = Yii::app()->helperAdmin->getMenuModule();

    function getmanu($it) {
        if (isset($it['items'])) {
            echo '<ul class="podm">';
            foreach ($it['items'] as $item) {
                echo '<li>';

                if (isset($item['url'])) {
                    ?>
                    <a class="" href="<?= CHtml::normalizeUrl($item['url']); ?>"><?= $item['label'] ?></a>
                    <?php
                } else
                    echo '<span>' . $item['label'] . '</span>';
                getmanu($item);


                echo '</li>';
            }
            echo '</ul>';
        }
    }
    ?>
    <?php foreach ($modules as $item) : 

    ?>

    <div class="box">		
        <div class="content"><strong>
<?php
if (isset($item['url'])) {
    ?>
                    <a class="strong" href="<?= CHtml::normalizeUrl($item['url']); ?>"><?= $item['label'] ?></a>
                    <?php
                } else
                    echo '<span class="strong">' . $item['label'] . '</span>';
                ?>
            </strong>
                <?php
                getmanu($item);
                ?>

        </div>
    </div>
    <?php endforeach; ?>
</div>