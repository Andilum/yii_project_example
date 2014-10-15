<?php
/* @var $users array */
?>
<div class="rightbar-white rightbar-white_users">
    <table class="rightbar-list-tbl">
        <?php $i = 1; ?>
        <?php foreach ($users as $user): ?>
            <tr>
                <td class="rightbar-list-td"><div class="rightbar-list-num"><?= $i ?></div></td>
                <td class="rightbar-list-td-pic">
                    <a href="<?= Yii::app()->createUrl('/user/view', array('id' => $user['id'])) ?>">
                        <img width="30" src="<?= User::getAvatarPath($user['id'], User::AVATAR_SIZE_50) ?>" alt="" />
                    </a>
                </td>
                <td class="rightbar-list-td">
                    <a class="rightbar-list-a" href="<?= Yii::app()->createUrl('/user/view', array('id' => $user['id'])) ?>"><?= $user['nick'] ?></a>
                    <?= $user['country_name'] ?>, <?= $user['city_name'] ?>
                </td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>
    </table>
    <div class="rightbar-white-more"><a class="rightbar-white-more-a" href="<?= Yii::app()->createUrl('/rating/index') ?>">Весь рейтинг туристов &rarr;</a></div>
</div>