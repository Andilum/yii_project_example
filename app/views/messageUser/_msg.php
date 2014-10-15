<?php
/* @var $this MessageUserController */
/* @var $data Array */
$incoming = $data['user_to_id'] == Yii::app()->user->id;

$user = User::model()->findByPk($incoming ? $data['user_from_id'] : $data['user_to_id']);
?>
<table class="b-chatshotel-item <?php if ($incoming && !$data['read']) echo 'bg-f5f5f5'; ?>" onclick="window.location.href = '<?= Yii::app()->createUrl('messageUser/user', array('to' => $user->id)) ?>'">
    <tbody>
        <tr>
            <td class="b-chatshotel-item-column1">
                <img src="<?= User::getAvatarPath($user->id, User::AVATAR_SIZE_50) ?>" alt="">
                <a class="b-chatshotel-item-a" href="<?=$user->getUrl()?>"><?= CHtml::encode($user->name) ?></a>
                <span class="b-chatshotel-item-data"><?= DateHelper::getDateFormat2Post($data['date_create']) ?></span>
            </td>
            <td class="b-chatshotel-item-column2">
                <div class="b-chatshotel-item-column2-container <?php if (!$data['read']) echo 'bg-f5f5f5'; ?>">
<?php if (!$incoming) { ?>
                        <div class="b-chatshotel-item-column2-img">
                            <img src="<?= User::getAvatarPath(Yii::app()->user->id, User::AVATAR_SIZE_50) ?>" alt="">
                        </div>
<?php } ?>
                    <div class="b-chatshotel-item-column2-txt">
                    <?= CHtml::encode($data['message']) ?>											
                    </div>
                </div>	
            </td>
            <td class="b-chatshotel-item-column3">
                <div class="<?php if (!$data['read']) echo 'bg-f5f5f5'; ?>">
                <?php if (($count=MessageUser::getCountNoRead(Yii::app()->user->id,$user->id))) { ?>
                <span class="b-chatshotel-item-counter">+<?=Yii::app()->format->number($count)?></span>
                <?php } ?>
                </div>
            </td>
        </tr>
    </tbody>									
</table>