<?php

/**
 * базовый контроллер для страниц где требуеться авторизация
 */
abstract class AuthController extends Controller {

    private $_user;

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * 
     * @return User
     */
    public function getUser() {
        if (!$this->_user)
            $this->_user = User::model()->findByPk(Yii::app()->user->id);
        return $this->_user;
    }

    public function accessRules() {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny', // deny all ForumForumss
                'users' => array('*'),
            ),
        );
    }

}