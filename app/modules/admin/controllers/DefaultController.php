<?php

class DefaultController extends ControllerAdmin {

    public $layout = '//layouts/column1';
    /**
     * Declares class-based actions.
     */
    public function accessRules() {
        return array(
            array('allow', 'users' => array('*'), 'actions' => array('login', 'logout', 'error')),
            array('allow', // allow admin Seo to perform 'admin' and 'delete' actions
                'roles' => array('admin'),
            ),
            array('allow', 'allow' => array('@'), 'actions' => array('index'), 'expression' => '!empty(Yii::app()->user->isadminka)'),
            array('deny', 'users' => array('*')),
        );
    }

    public function actionIndex() { 
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $this->layout = '//layouts/login';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionLogin() {

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->layout = '//layouts/login';
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}
