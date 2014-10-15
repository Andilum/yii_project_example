<?php

$config = CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'preload' => array('debug'),
        'components' => array(
            'fixture' => array(
                'class' => 'system.test.CDbFixtureManager',
            ),

        ),
        'modules' => array(
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => 'password',
                'generatorPaths' => array(
                    'bootstrap.gii',
                ),
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => array('127.0.0.1', '::1'),
            ),
        ),
    )
);

if (file_exists(__DIR__ . '/params.php')) {
    $config = CMap::mergeArray(
        $config,
        include __DIR__ . '/params.php'
    );
}

return $config;