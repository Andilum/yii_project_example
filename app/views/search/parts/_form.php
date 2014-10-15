<form class="searchresults-search" action="<?= $this->createUrl('index') ?>">
    <button class="searchresults-button">Найти</button>
    <?php if (Yii::app()->request->getParam('type', '')) {
        echo CHtml::hiddenField('type', Yii::app()->request->getParam('type', ''));
    } ?>
    <div class="searchresults-input-wrap"><input name="t" value="<?= CHtml::encode($t) ?>" placeholder="Найти отель, пользователя или хэштег" class="searchresults-input"></div>
</form>