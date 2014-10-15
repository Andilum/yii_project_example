<?php
/* @var $this PostList */
/* @var $data Post */
/* @var $commentList array */
/* @var $likeList array */
?>
<div class="comment-item" id="post-item-<?= $data->id ?>" data-post-id="<?= $data->id ?>">
    <div class="comment-item-in">
        <div class="comment-top">
            <a class="comment-top-pic" href="<?= Yii::app()->createUrl('/user/view', array('id' => $data->tp_user_id)) ?>"><img src="<?= User::getAvatarPath($data->tp_user_id, User::AVATAR_SIZE_50) ?>" width="30" alt="" /></a>
            <a class="comment-top-nick" href="<?= Yii::app()->createUrl('/user/view', array('id' => $data->tp_user_id)) ?>"><?= isset($data->user->nick) ? $data->user->nick : '' ?></a>
            <?php if (isset($data->allocation->name) && $data->allocation->name): ?>
                <span class="comment-top-where"> в <a href="<?= Yii::app()->createUrl('/allocation/view', array('id' => $data->allocation->id)) ?>"><?= $data->allocation->name ?> <?= $data->allocation->alloccat->name ?></a></span>
            <?php endif; ?>
            <span class="comment-top-clock"><?= DateHelper::getDateFormat2Post($data->date) ?></span>
        </div>
        <div class="comment-txt<?= $data->photo ? ' comment-txt-bottom' : '' ?>">
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
        <?php if ($data->user_rating): ?>
            <div class="comment-opinion">
                <?php foreach ($data->user_rating as $userRating): ?>
                    <table class="comment-opinion-tbl" style="margin-top: 10px">
                        <tr>
                            <td class="comment-opinion-td"><img src="/images/pic4.jpg" alt="" /></td>
                            <td class="comment-opinion-td">Оценил <?= mb_strtolower($userRating->service->name, 'UTF-8') ?> отеля<br />
                                на <?= $userRating->rating->label ?> <?= Yii::t('app', 'балл|балла|баллов', round($userRating->rating->rate)) ?> из <?= $data->max_rating ?> возможных</td>
                        </tr>
                    </table>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="comment-bottom">
            <?php $buttonDisable = ' style="cursor:default;text-decoration:none"'; ?>
            <?php if ($data->like_count): ?>
                <div class="comment-bottom-like-num like-button">
                    <a class="comment-bottom-like" id="post-like-<?= $data->id ?>" data-owner-id="<?= $data->id ?>" href="#"<?= Yii::app()->user->isGuest ? $buttonDisable : '' ?>><?=Yii::t('app','лайк')?></a>
                    <a class="comment-bottom-heart" id="post-like-heart-<?= $data->id ?>" data-owner-id="<?= $data->id ?>" href="#"<?= Yii::app()->user->isGuest ? $buttonDisable : '' ?>><?= $data->like_count ?></a>
                </div>
            <?php else: ?>
                <a class="comment-bottom-like" id="post-like-<?= $data->id ?>" data-owner-id="<?= $data->id ?>" href="#"<?= Yii::app()->user->isGuest ? $buttonDisable : '' ?>><?=Yii::t('app','лайк')?></a>
            <?php endif; ?>
            <?php if ($data->comment_count): ?>
                <div class="comment-bottom-like-num comment-button">
                    <a class="comment-bottom-like"><?=Yii::t('app','комментарии')?></a>
                    <a class="comment-bottom-num"><?= $data->comment_count ?></a>
                </div>
            <?php else: ?>
                <a class="comment-bottom-like no-comments"<?= Yii::app()->user->isGuest ? $buttonDisable : '' ?>><?=Yii::t('app','комментировать')?></a>
            <?php endif; ?>
            <a href="#" class="comment-bottom-more"></a>
        </div>
        <?php $lastCommentId = 0; ?>
        <?php if ($data->comment_count || $data->like_count): ?>
            <div class="comment-dialog">
                <table class="comment-tbl">
                    <?php if ($data->like_count): ?>
                        <tr class="post-likes">
                            <td class="comment-td"><img src="/i/comment-bottom-heart.png" alt="" /></td>
                            <td class="comment-td">
                                <?php $i = 1; ?>
                                <?php foreach ($likeList[$data->id] as $like): ?>
                                    <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $like->user->id)) ?>"><?= $like->user->nick ?></a><?php if ($i < 3 && $i != count($likeList[$data->id])): ?>,<?php endif; ?>
                                    <?php if ($i >= 3) {
                                        break;
                                    }
                                    $i++; ?>
                                <?php endforeach; ?>
                                <?php if ($data->like_count > 3): ?>
                                    <span class="comment-td-gray">и еще</span> <a class="additional-likes" href="#"><?= ($data->like_count - 3) ?> <?= Yii::t('app', 'лайк|лайка|лайков', $data->like_count - 3) ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($data->comment_count > 3): ?>
                        <tr>
                            <td class="comment-td"><img src="/i/comment-bottom-num-blue.png" alt="" /></td>
                            <?php $commentNum = $data->comment_count - 3;

                             $commentPrevText = Yii::t('app', 'предыдущий|предыдущих|предыдущих',$commentNum);
                            $commentText = Yii::t('app', 'комментарий|комментария|комментариев',$commentNum);

                            $linkText = 'Просмотреть ' . $commentNum . ' ' . $commentPrevText . ' ' . $commentText ?>
                            <td class="comment-td">
                                <a id="comment-prev-<?= $data->id ?>" data-post-id="<?= $data->id ?>" href="#"><?= $linkText ?></a>
                            </td>
                        </tr>
                    <?php endif;
                    if ($data->comment_count) {
                        $i = 0;
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
                                                        <a href="#" data-img-id="<?= $photo->id ?>"><img src="<?= $photo->getFileUrl(Photo::IMAGE_SIZE_129) ?>" alt="" /></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $i++;
                            if (count($commentList[$data->id]) == $i) {
                                $lastCommentId = $comment->id;
                            }
                        endforeach;
                    } ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <?php if (!Yii::app()->user->isGuest): ?>
        <?php $this->render('PostList/_commentForm', array('postId' => $data->id, 'comment' => $data->comment_count, 'lastCommentId' => $lastCommentId)); ?>
    <?php endif; ?>
</div>