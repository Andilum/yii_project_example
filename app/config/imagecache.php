<?php
if ($_SERVER['APP_ENV'] == 'prod') {
    $main = require 'prod.php';
} else {
    $main = require 'dev.php';
}

return array(
    'basePath' => dirname(dirname(__FILE__)),
    'commandPath' => dirname(dirname(__FILE__)) . '/extensions/imagecache/commands',
    'import' => array(
        'ext.imagecache.*',
        'application.helpers.*',
    ),
    'tmpPath' => dirname(dirname(dirname(__FILE__))) . '/app/runtime',
    'publicPath' => dirname(dirname(dirname(__FILE__))) . '/www/icache',
    'components' => array(
        'db' => $main['components']['db'],
    ),
    'files' => array(
        'photo' => array(
            'class' => 'ext.imagecache.ImageCacheHandlerImage',
            'source' => array(
                'class' => 'ext.imagecache.ImageCacheSourceDb',
                'table' => 'hi.hi_photo',
                'pk' => 'id'
            ),
            'sections' =>array(
                '67x67' => array(
                    'crop' => 'imageCropCords',
                    'width' => 67,
                    'height' => 67,
                ),
                '129x129' => array(
                    'crop' => 'imageCropCords',
                    'width' => 129,
                    'height' => 129,
                ),
                '172x172' => array(
                    'crop' => 'imageCropCords',
                    'width' => 172,
                    'height' => 172,
                ),
                '259x259' => array(
                    'crop' => 'imageCropCords',
                    'width' => 259,
                    'height' => 259,
                ),
                '347x347' => array(
                    'crop' => 'imageCropCords',
                    'width' => 347,
                    'height' => 347,
                ),
                '522x522' => array(
                    'crop' => 'imageCropCords',
                    'width' => 522,
                    'height' => 522,
                ),
            ),
        ),
    )
);