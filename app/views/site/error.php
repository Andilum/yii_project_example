<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>

    <h2>Error <?php echo $code; ?></h2>

<?php if ($code < 5001): ?>
    <div class="error">
        <?php echo CHtml::encode($message); ?>
    </div>

<?php endif; ?>