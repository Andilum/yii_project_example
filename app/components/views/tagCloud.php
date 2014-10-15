<?php
/* @var $tags array */
?>
<?php if (count($tags)): ?>
    <div class="rightbar-white">
        <?php foreach ($tags as $tag): ?>
            <a href="<?= Yii::app()->createUrl('/tag/view', array('tag' => $tag['name'])) ?>"><?= $tag['name'] ?></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>