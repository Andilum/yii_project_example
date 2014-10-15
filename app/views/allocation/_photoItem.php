<?php
/* @var $data array */
/* @var $type string */
$src = $data->getFileUrl(Photo::IMAGE_SIZE_129);
if ($type == 'feed') {
    $src = $data->getFileUrl();
}
?>
<li class="userphoto-list-item" id="photo-item-<?= $data->id ?>"<?= $type == 'feed' ? ' style="float: none"' : '' ?>>
    <a href="#" data-img-id="<?= $data->id ?>"><img src="<?= $src ?>" alt="" style="max-width: 525px"></a>
</li>