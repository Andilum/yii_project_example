<?php

/**
 * для установки локальных настроек, переименуйте в params.php , и дополните настойки
 */

return array(
    'components' => array(
        'db' => array(
            'class'=>'system.db.CDbConnection',
            'connectionString' => 'pgsql:host=192.168.150.24;port=5432;dbname=hotelsinspector',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'enableParamLogging' => true,
            'enableProfiling' => true,
        ),
         'tp' => array(
            'class'=>'system.db.CDbConnection',
            'connectionString' => 'pgsql:host=192.168.150.21;port=5432;dbname=travelpassport',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'enableParamLogging' => true,
            'enableProfiling' => true,
        ),
        'travelpassport' => array(
                "class" => 'Lightsoft\REST\Client\Travelpassport',
                "serverUrl" => "http://travelpassport.thy"
            ),
    ),
    
);