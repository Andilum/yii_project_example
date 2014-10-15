<?php
/* @var $allocations array */
?>
<div class="rightbar-white">
    <table class="rightbar-list-tbl">
        <?php $i = 1; ?>
        <?php foreach ($allocations as $allocation): ?>
            <tr>
                <td class="rightbar-list-td"><div class="rightbar-list-num"><?= $i ?></div></td>
                <td class="rightbar-list-td">
                    <a class="rightbar-list-a" href="<?= Yii::app()->createUrl('/allocation/view', array('id' => $allocation['id'])) ?>">
                        <?php $name = $allocation['name'] . ' ' . $allocation['alloccat_name'];
                        $name = strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name; ?>
                        <?= $name ?>
                    </a>
                    <?= $allocation['country_name'] ?>, <?= $allocation['resort_name'] ?>
                </td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>
    </table>
    <div class="rightbar-white-more"><a class="rightbar-white-more-a" href="<?= Yii::app()->createUrl('/rating/index', array('type' => 'allocation')) ?>">Весь рейтинг отелей &rarr;</a></div>
</div>