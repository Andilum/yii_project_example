<?php
/* @var $this DefaultController */
/* @var $result array */
/* @var $errors array */
/* @var $warning array */
/* @var $okCount int */
/* @var $allCount int */


$this->pageTitle = 'тестирование api - резульатты';
$this->breadcrumbs = array(
    'тестирование api' => $this->createUrl('index'),
    $this->pageTitle,
);
?>
<h1><?php echo $this->pageTitle ?></h1>
<strong><?php if ($errors) { ?> <p class="text-error">Ошибок: <?= $errors ?></p> <?php } else { ?><p class="text-success"></p> <?php } ?></strong>
<?php if (!$errors && !$warning && $okCount==$allCount) { ?>
    <div class="alert alert-success">
        <strong>Ошибок не найдено</strong>
    </div>
<?php } else {
    if ($errors || $okCount!=$allCount) {
        ?>
        <div class="alert alert-error">
            <strong><?= $errors ?> ошибок</strong><br>
            <strong><?php echo 'успешных тестов '.$okCount.' из '.$allCount ?></strong>
        </div>
    <?php } ?>

    <?php if ($warning) { ?>
        <div class="alert alert-warning">
            <strong><?= $warning ?> warning</strong>
        </div>
    <?php }
}
?>
<div class="result" style="padding: 5px; border: 1px solid #cccccc;">
    <?php
    foreach ($result as $value) {
        if ($value[0] == 'html') {
            echo $value[1];
        } else {
            echo '<p class="text-' . $value[0] . '">' . CHtml::encode($value[1]) . '</p>';
        }
    }
    ?>
</div>
