<?php

class UserProfile extends CWidget {

    public function init() {
        $baseAssetsPath = Yii::getPathOfAlias('application.components.assets.UserProfile');
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/userProfile.js'));
    }

    public function run() {
        $user = Yii::app()->controller->model;
        $this->render('userProfile', array(
            'user' => $user,
            'my'=>Yii::app()->controller->my
        ));
    }

}