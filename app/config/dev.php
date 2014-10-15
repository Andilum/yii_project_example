<?php

$config = CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), array(
            'preload' => array('debug'),
            'components' => array(
                'authGrabber' => array(
                    'key' => '6e8eabf4a39c46d95feb8f66b12ad42afd5d551a',
                    'secret' => '08f9505f6674f584c05c490db5fa105ed3ada1bd',
                ),
                'travelpassport' => array(
                    "class" => 'Lightsoft\REST\Client\Travelpassport',
                    "serverUrl" => "http://beta.travelpassport.ru"
                ),
                'debug' => array(
                    'class' => 'vendor.zhuravljov.yii2-debug.Yii2Debug',
                    'allowedIPs' => array('127.0.0.1', '::1'),
                    'historySize' => 10,
                ),
                'fixture' => array(
                    'class' => 'system.test.CDbFixtureManager',
                ),
                'log' => array(//configure log
                    'class' => 'CLogRouter',
                    'routes' => array(
                        array(
                            'class' => 'CProfileLogRoute',
                            'enabled' => isset($_REQUEST['debug']),
                            'levels' => 'trace, info, profile, warning, error',
                            'categories' => array('system.*', 'ar.*'),
                        ),
                    ),
                ),
            ),
            'modules' => array(
                'gii' => array(
                    'class' => 'system.gii.GiiModule',
                    'password' => 'password',
                    
                    // If removed, Gii defaults to localhost only. Edit carefully to taste.
                    'ipFilters' => array('127.0.0.1', '::1'),
                ),
            ),
                )
);


if (file_exists(__DIR__ . '/params.php')) {
    $config = CMap::mergeArray(
                    $config, include __DIR__ . '/params.php'
    );
}

return $config;