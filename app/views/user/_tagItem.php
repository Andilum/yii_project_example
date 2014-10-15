<?php
/* @var $data array */
?>
<div class="hashtags-table-row">
    <div class="hashtags-table-cell"><a class="hashtags-table-link" href="<?= Yii::app()->createUrl('/tag/view', array('tag' => $data['name'])) ?>"><?= $data['name'] ?></a></div>
    <div class="hashtags-table-cell"><a class="hashtags-table-link" href="#"><?= $data['total_count'] ?></a></div>
    <div class="hashtags-table-cell"><a class="hashtags-table-link" href="#"><?= $data['post_count'] ?></a></div>
    <div class="hashtags-table-cell"><a class="hashtags-table-link" href="#"><?= $data['comment_count'] ?></a></div>
</div>