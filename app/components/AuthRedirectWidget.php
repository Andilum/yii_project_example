<?php

class AuthRedirectWidget extends CWidget {

    public $url = null;

    public function run() {

        $this->render('redirect', array(
            'id' => $this->getId(),
            'url' => $this->url,
        ));
        Yii::app()->end();
    }
}