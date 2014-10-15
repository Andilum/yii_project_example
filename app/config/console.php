<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

Yii::setPathOfAlias('vendor', __DIR__ . '/../../vendor');

return
    CMap::mergeArray(
        array(
            'basePath' => __DIR__ . DIRECTORY_SEPARATOR . '..',
            'name' => 'Console Hotelinspector',
            'sourceLanguage' => 'ru',
            'language' => 'ru',
            'import' => array(
                'application.models.*',
                'application.models.dict.*',
                'application.components.*',
                'application.components.widgets.*',
                'application.helpers.*',
            ),
            'params' => require __DIR__ . '/params.php',
        ), 
        require 'params.php'
);
