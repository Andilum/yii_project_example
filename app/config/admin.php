<?php

//Конфигурация для админки
return array(
    'aliases' => array(
        'bootstrap' => Yii::getPathOfAlias('vendor.2amigos.yiistrap'), // change this if necessary
        'yiiwheels' => Yii::getPathOfAlias('vendor.2amigos.yiiwheels'), // change this if necessary
    ),
    'import' => array(
        'bootstrap.helpers.TbHtml',
    //'application.modules.srbac.controllers.SBaseController'
    ),
    'components' => array(
        'bootstrap' => array('class' => 'bootstrap.components.TbApi'),
        'yiiwheels' => array('class' => 'yiiwheels.YiiWheels'),
        'user' => array(
            'loginUrl' => array('admin/default/login'),
            // для того что бы  сесия админки с сайтом была разная
            'stateKeyPrefix' => 'cmsadmin'
        ),
        'helperAdmin' => array('class' => 'admin.components.HelperAdmin'),
        'clientScript' => array(
            'class' => 'CClientScript',
            'packages' => array(
                'admin' => array(
                    'basePath' => 'admin.assets.layouts',
                    'css' => array('css/styles.css')
                ),
                'main' => array(
                ),
            )
        ),
        'authManager' => array(
            'class' => 'PhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
        
    /* 'authManager'=>array(
      'class' => 'CDbAuthManager', //'PhpAuthManager',
      'itemTable' => 'authitem',
      'itemChildTable' => 'authitemchild',
      'assignmentTable' => 'authassignment',
      'connectionID' => 'db',
      'defaultRoles' => array('guest'),
      ) */
    ),
    'params' => array('admin_menu' => array(
            array('label' => 'Посты', 'url' => array('/admin/post/index')),
            array('label' => 'Переводы', 'url' => '/translate')
        ))
);
