<?php
/* @var $photos array */
?>
<?php if (count($photos)): ?>
    <div class="rightbar-pics">
        <?php foreach ($photos as $photo): ?>
            <a class="rightbar-pics-a" href="#" data-img-id="<?= $photo->id ?>"><img src="<?= $photo->getFileUrl(Photo::IMAGE_SIZE_67) ?>" alt="" /></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>