<?php
/* @var $postId int */
/* @var $multiFileUpload array */
/* @var $displayGallery bool */
?>
<div class="b-feedback-gallery"<?= $displayGallery ? ' style="display:block"' : '' ?>>
    <a class="b-feedback-gallery-item-add" id="add-photo-<?= $postId ?>" href="#" title="Добавить фото"></a>
</div>
<?php 
new CMultiFileUpload();
$this->widget('CMultiFileUpload', $multiFileUpload); ?>