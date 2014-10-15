<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
date_default_timezone_set('Europe/Moscow');
Yii::setPathOfAlias('vendor', __DIR__ . '/../../vendor');
Yii::setPathOfAlias('app', __DIR__ . '/..');

$config = array(
    'basePath' => __DIR__ . DIRECTORY_SEPARATOR . '..',
    'name' => 'HotelsInspector',
    'sourceLanguage' => 'ru',
    'language' => 'ru',
    // preloading 'log' component
    'preload' => array('translate', 'log', 'authGrabber'),
    
    'modules' => array(
        'api','admin'
    ),
    'aliases' => array(
        'bootstrap' => realpath(__DIR__ . '/../../vendor/2amigos/yiistrap'), // change this if necessary
        'yiiwheels' => realpath(__DIR__ . '/../../vendor/2amigos/yiiwheels'), // change this if necessary
    ),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.dict.*',
        'application.models.SR.*',
        'application.components.*',
        'application.components.widgets.*',
        'application.helpers.*',
        'bootstrap.helpers.TbHtml',
    ),
    // application components
    'components' => array(
        'authGrabber' => array(
            'class' => 'AuthStateGrabber',
            'key' => '',
            'secret' => '',
        ),
        'translate' => array(
            'class' => 'vendor.lightsoft.translate.Translate',
            'cookieDomain'=>'',
            'eventSetLang'=>'onParseLang',
            'defaultLanguage' => 'ru',
            'layout'=>'//layouts/admin',
        ),
        'user' => array(
            'class' => 'CWebUser',
            // enable cookie-based authentication
            'loginUrl' => array('auth/login'),
            'allowAutoLogin' => true,
        ),
        'userLanguages' => array(
            'class' => 'UserLanguages',
        ),
        'format' => array(
            'datetimeFormat' => 'd.m.Y H:i:s',
            'timeFormat' => 'H:i:s',
            'dateFormat' => 'd.m.Y',
            'booleanFormat' => array('Нет', 'Да')
        ),
        'cache' => array(
            'class' => 'CFileCache',
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.TbApi',
        ),
        'yiiwheels' => array(
            'class' => 'yiiwheels.YiiWheels',
        ),
        'urlManager' => array(
            'class'=>'UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                
                ''=>'site/index',

                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/auth',
                    'idName' => 'id',
                    'controller' => 'api/auth',
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/comment',
                    'idName' => 'id',
                    'controller' => 'api/comment',
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/dict/allocation',
                    'idName' => 'id',
                    'controller' => 'api/dicts/allocation',
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/dict/alloccat',
                    'idName' => 'id',
                    'controller' => 'api/dicts/alloccat',
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/dict/country',
                    'idName' => 'id',
                    'controller' => 'api/dicts/country',
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/dict/resort',
                    'idName' => 'id',
                    'controller' => 'api/dicts/resort',
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/user',
                    'idName' => 'nick',
                    'idPattern' => '%[a-zA-Z0-9_-]+%',
                    'controller' => 'api/user',
                    'subresources' => array(
                        array(
                            'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                            'path' => 'feed',
                            'idName' => 'postId',
                            'controller' => 'api/users/feed',
                        ),
                        array(
                            'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                            'path' => 'avatar',
                            'idName' => 'avatarId',
                            'controller' => 'api/users/avatar',
                        ),
                    )
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/allocation',
                    'idName' => 'allocationId',
                    'controller' => 'api/allocation',
                    'subresources' => array(
                        array(
                            'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                            'path' => 'feed',
                            'idName' => 'postId',
                            'controller' => 'api/allocations/feed',
                        ),
                    )
                ),
                array(
                    'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                    'path' => 'api/feed',
                    'idName' => 'postId',
                    'controller' => 'api/feed',
                    'subresources' => array(
                        array(
                            'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                            'path' => 'like',
                            'idName' => 'likeId',
                            'controller' => 'api/feeds/like',
                        ),
                        array(
                            'class' => '\Lightsoft\REST\PluralResourceUrlRule',
                            'path' => 'comment',
                            'idName' => 'commentId',
                            'controller' => 'api/feeds/comment',
                        ),
                    )
                ),
                
                array('api/photo/create','pattern'=>'api/photo','verb' => 'POST'),
                array('api/photo/create2','pattern'=>'api/photo2','verb' => 'POST'),
                array('api/photo/destroy','pattern'=>'api/photo/<id:\d+>','verb' => 'DELETE'),
                
                '/tag/index' => '/tag/index',
                '/tag/<tag:\w+>' => '/tag/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'clientScript' => array(
            'class' => 'vendor.bestxp.EClientScript.EClientScript',
            'combineScriptFiles' => !YII_DEBUG,
// By default this is set to false, set this to true if you'd like to combine the script files
            'combineCssFiles' => !YII_DEBUG,
// By default this is set to false, set this to true if you'd like to combine the css files
            'optimizeCssFiles' => !YII_DEBUG,
            'optimizeScriptFiles' => !YII_DEBUG,
// CSS files for ignore
            'cssForIgnore' => array(
                'bootstrap.min.css',
                'jquery-ui-1.7.1.custom.css',
                'jquery-ui.multiselect.css',
                'redactor.css',
                'bootstrap.css',
                'bootstrap-responsive.css',
                'additions.css',
                'orange.css',
                'responsive.css',
                'default.css',
                'font-awesome.min.css',
                'scroll-vertical-menu.css'
            ),
// JS files for ignore
            'scriptsForIgnore' => array(
                'jquery.js',
                'jquery.min.js',
                'jquery.ui.js',
                'jquery-ui.min.js',
                'bootstrap.min.js',
                'bootstrap.js',
                'angular.min.js',
                'angular-resource.min.js',
                'amcharts.js',
                'redactor.min.js',
                'redactor.js'
            ),
            'packages' => array(
                'main' => array(
                    // общая часть URL ресурсов пакета
                    'baseUrl' => '',
                    // список js-файлов
                    'js' => array(
                        'js/jquery.placeholder.js',
                        'js/main.js',
                        'js/b-side-menu.js',
                        'js/b-subscribe.js',
                        'js/ui-feedback.js',
                        'js/jquery.mCustomScrollbar.js',
                    ),
                    // список css-файлов
                    'css' => array(
                        'css/reset-ls.css',
                        'css/main.css',
                        'css/b-header.css',
                        'css/b-profile.css',
                        'css/b-leftmenu.css',
                        'css/b-comment.css',
                        'css/b-rightbar.css',
                        'css/b-feedback.css',
                        'css/b-hashtags.css',
                        'css/b-hotel-toolbar.css',
                        'css/b-my-profile.css',
                        'css/b-subscribe.css',
                        'css/b-userinfo.css',
                        'css/b-userphoto.css',
                        'css/b-image-grid.css',
                        'css/b-post-pp.css',
                        'css/jquery.mCustomScrollbar.css',
                    ),
                    'depends' => array('jquery', 'jquery.ui')
                ),
                'bootstrap' => array(
                    'baseUrl' => '/js/a/bootstrap',
                    'css' => array(
                        'css/bootstrap.css',
                        'css/bootstrap-responsive.css',
//                        'css/default.css',
                        'font-awesome/css/font-awesome.min.css',
                        'css/additions.css',
//                        'css/orange.css'
                    ),
                    'js' => array(
                        'js/bootstrap.js'
                    ),
                    'depends' => array(
                        'jquery'
                    )
                ),
            ),
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(),
);


return $config;