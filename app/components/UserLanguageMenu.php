<?php

class UserLanguageMenu extends CWidget {

    public function init() {
        $baseAssetsPath = Yii::getPathOfAlias('application.components.assets.UserLanguageMenu');
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/userLanguageMenu.js'));
    }

    public function run() {
        $this->render('userLanguageMenu', array(
            'userLanguage' => Yii::app()->userLanguages->getUserLanguage(),
        ));
    }
}