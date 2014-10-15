<?php

$config = CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), array(//some vars

            'components' => array(
                'log' => array(//configure log
                    'class' => 'CLogRouter',
                    'routes' => array(
                        array(
                            'class' => 'CEmailLogRoute',
                            'levels' => 'error, warning',
                            'except' => 'exception.CHttpException.*',
                            // add your email in this section
                            'emails' => array(),
                        ),
                    ),
                ),
            )
                )
);

if (file_exists(__DIR__ . '/params.php')) {
    $config = CMap::mergeArray(
                    $config, include __DIR__ . '/params.php'
    );
}
return $config;