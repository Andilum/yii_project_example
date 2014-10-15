<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ControllerAdmin extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    public $breadcrumbs = array();

    public function init() {
        Yii::app()->setComponent('translate', array('apiName'=>null),true);
        Yii::import('admin.components.*');
        Yii::import('admin.models.*');
        HelperAdmin::initAdmin();


        parent::init();
    }

    public function filters() {

        return array(
            'accessControl',
        );
        
    }

    public function accessRules() {

        return array(
            array('allow', // allow admin ForumForums to perform 'admin' and 'delete' actions
                'roles' => array('admin'),
            ),
            array('deny', // deny all ForumForumss
                'users' => array('*'),
            ),
        );
    }

    public function error($cod = 404, $msg = 'error') {
        throw new CHttpException($cod, $msg);
    }

    public function getLayoutFile($layoutName) {
        return $this->resolveViewFile($layoutName, null, Yii::getPathOfAlias('admin.views'));
    }

}
