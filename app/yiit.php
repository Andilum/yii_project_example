<?php
/**
 * Yii test script file.
 *
 * This script is meant to be included at the beginning
 * of the unit and function test bootstrap files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

// disable Yii error handling logic
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);
require_once(__DIR__ . '/../vendor/yiisoft/yii/framework/YiiBase.php');
require_once(dirname(__FILE__) . '/yii.php');
require_once(__DIR__ . '/app/WebApplication.php');
require_once(__DIR__ . '/../vendor/autoload.php');

Yii::import('system.test.CTestCase');
Yii::import('system.test.CDbTestCase');
