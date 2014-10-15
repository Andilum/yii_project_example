<?php

class AuthController extends CController {

    public function actionLogin() {
        if ( Yii::app()->user->isGuest )
        {
            if ( !Yii::app()->authGrabber->authenticate() ) {
                echo "Невозможно авторизоваться";
            }
        }
        else
        {
            echo "Вы авторизованы под пользователем " . Yii::app()->user->getState("nick") . " ( " . Yii::app()->user->getState("email") . " )";
        }
        Yii::app()->end();
    }

    public function actionLogout() {
        Yii::app()->authGrabber->logout();
        $this->redirect('/');
    }
}