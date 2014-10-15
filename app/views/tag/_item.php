<?php
/* @var $data Post */
/* @var $commentList array */
?>
<div class="comment-item" id="post-item-<?= $data->id ?>" data-post-id="<?= $data->id ?>">
    <div class="comment-item-in"<?= $data->photo && !isset($commentList[$data->id]) ? ' style="padding-bottom: 15px"' : '' ?>>
        <div class="comment-top">
            <a class="comment-top-pic" href="<?= Yii::app()->createUrl('/user/view', array('id' => $data->tp_user_id)) ?>"><img src="<?= User::getAvatarPath($data->tp_user_id, User::AVATAR_SIZE_50) ?>" width="30" alt="" /></a>
            <a class="comment-top-nick" href="<?= Yii::app()->createUrl('/user/view', array('id' => $data->tp_user_id)) ?>"><?= $data->user->nick ?></a>
            <?php if (isset($data->allocation->name) && $data->allocation->name): ?>
                <span class="comment-top-where"> в <a href="<?= Yii::app()->createUrl('/allocation/view', array('id' => $data->allocation->id)) ?>"><?= $data->allocation->name ?></a></span>
            <?php endif; ?>
            <span class="comment-top-clock"><?= DateHelper::getDateFormat2Post($data->date) ?></span>
        </div>
        <div class="comment-txt comment-txt-bottom">
            <?= $data->getReplacedText() ?>
        </div>
        <?php if ($data->photo): ?>
            <div class="comment-img-layout">
                <ul class="comment-img-list image-grid">
                    <?php $i = 0;
                    $groupCount = 0;
                    $photoCount = count($data->photo);
                    foreach ($data->photo as $photo) {
                        if (!$groupCount) {
                            if ($photoCount > Photo::IMAGE_TILE_GROUP_MAX_CONT) {
                                $photoCount -= Photo::IMAGE_TILE_GROUP_MAX_CONT;
                                $groupCount = Photo::IMAGE_TILE_GROUP_MAX_CONT;
                            } else {
                                $groupCount = $photoCount;
                                $photoCount = 0;
                            }
                        } ?>
                        <li class="<?= Photo::$tile[$groupCount][$i]['class'] ?>">
                            <a href="#" data-img-id="<?= $photo->id ?>"><img src="<?= $photo->getFileUrl(Photo::$tile[$groupCount][$i]['size']) ?>" /></a>
                        </li>
                        <?php $i++;
                        if ($i >= $groupCount) {
                            $i = 0;
                            $groupCount = 0;
                        }
                    } ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if ($data->comment_count): ?>
            <div class="comment-dialog">
                <table class="comment-tbl">
                    <?php $i = 0;
                    if (isset($commentList[$data->id])) {
                        foreach ($commentList[$data->id] as $comment): ?>
                            <tr id="comment-tr-<?= $comment->id ?>">
                                <td class="comment-td"><img src="/i/comment-bottom-num.png" alt="" /></td>
                                <td class="comment-td">
                                    <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $comment->user->id)) ?>"><?= $comment->user->nick ?></a>
                                    <?= $comment->getReplacedText() ?>
                                    <?php if ($comment->photo): ?>
                                        <div class="comment-img-layout">
                                            <ul class="comment-img-list">
                                                <?php foreach ($comment->photo as $photo): ?>
                                                    <li class="comment-img-list-item">
                                                        <a href="#" data-img-id="<?= $photo->id ?>"><img src="<?= $photo->getFileUrl(Photo::IMAGE_SIZE_129) ?>" alt=""></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $i++;
                        endforeach;
                    }?>
                    <?php if (!isset($commentList[$data->id]) || $data->comment_count > count($commentList[$data->id])): ?>
                        <tr>
                            <td class="comment-td"><img src="/i/comment-bottom-num-blue.png" alt="" /></td>
                            <td class="comment-td">
                                <a id="comment-prev-<?= $data->id ?>" data-post-id="<?= $data->id ?>" href="#">Показать все комментарии</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>