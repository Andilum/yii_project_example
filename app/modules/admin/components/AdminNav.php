<?php

/**
 * Меню админки которое можно вывести на сайте если общая авторизация (stateKeyPrefix не задан)
 * Размещяеться в макете сайта после <body>
 */
class AdminNav extends CWidget {

    public function run() {
        if (Yii::app()->user->checkAccess('admin') && Yii::app()->user->getState('isadminka')) {
            
            
            Yii::import('admin.components.*');
            Yii::setPathOfAlias('bootstrap', Yii::getPathOfAlias('vendor.2amigos.yiistrap'));
            
            Yii::import('bootstrap.helpers.TbHtml');

            Yii::app()->setComponent('bootstrap', array('class' => 'bootstrap.components.TbApi'));
            Yii::app()->setComponent('helperAdmin', array('class' => 'admin.components.HelperAdmin'));

            Yii::app()->clientScript->registerCssFile(CHtml::asset(Yii::getPathOfAlias('admin.assets.bootstrap-f') . '.css'));
            Yii::app()->bootstrap->registerAllScripts();

            $this->render('controls_module');
        }
    }

}