<meta charset="utf-8">
<title><?= CHtml::encode($this->pageTitle) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
/** @var EClientScript $clientScript */
$clientScript = Yii::app()->getClientScript();

$baseUrl = Yii::app()->getBaseUrl();


$clientScript->registerCoreScript('jquery');
//$clientScript->registerPackage('bootstrap');
$clientScript->registerPackage('main');

?>