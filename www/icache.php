<?php
define('ROOT_DIR', dirname(dirname(__FILE__)) . '/');
define('APP_DIR', ROOT_DIR . 'app/');

require ROOT_DIR . 'vendor/yiisoft/yii/framework/yii.php';
require APP_DIR . 'extensions/imagecache/ImageCache.php';

if (isset($_GET['uri'])) {
    $_SERVER['argv'] = array(__FILE__, 'publish', '--url=' . $_GET['uri']);
}

function imageCropCords($fileData, $extraData) {
    return ImageHelper::getCropCords($fileData, $extraData);
}

$app = Yii::createApplication(
    'ImageCache',
    APP_DIR . 'config/imagecache.php'
);

$app->run();