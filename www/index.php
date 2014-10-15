<?php
$yiiBase = __DIR__ . '/../vendor/yiisoft/yii/framework/YiiBase.php';
$yii = __DIR__ . '/../app/yii.php';
$config = __DIR__ . '/../app/config/prod.php';


// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
$config = __DIR__ . '/../app/config/dev.php';


require_once __DIR__ . '/../vendor/autoload.php';
require_once($yiiBase);
require_once($yii);
require_once __DIR__ . '/../app/app/WebApplication.php';

$app = Yii::createWebApplication($config);
$app->run();
