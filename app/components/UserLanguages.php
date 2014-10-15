<?php

class UserLanguages extends CApplicationComponent {
    const ALL_LANGUAGE = 'all',
        USER_LANGUAGE = 'user';

    /**
     * @var string
     */
    public $cookieDomain = '.hotelsinspector.com';
    /**
     * @var string
     */
    public $cookieName = 'user_language';
    /**
     * @var string
     */
    protected $userLanguage;

    public function init() {
        if (!isset(Yii::app()->request->cookies[$this->cookieName]->value)) {
            $cookie = new \CHttpCookie($this->cookieName, self::USER_LANGUAGE);
            $cookie->domain = $this->cookieDomain;
            Yii::app()->request->cookies[$this->cookieName] = $cookie;
        }

        $this->userLanguage = Yii::app()->request->cookies[$this->cookieName]->value;

        $cs = Yii::app()->clientScript;
        $cs->registerScript('userLanguages', "
            userLanguages = {
                'cookieDomain': '{$this->cookieDomain}',
                'cookieName': '{$this->cookieName}',
                'userLanguage': '{$this->userLanguage}'
            };
        ");
    }

    /**
     * @return string
     */
    public function getCookieDomain() {
        return $this->cookieDomain;
    }
    /**
     * @return string
     */
    public function getCookieName() {
        return $this->cookieName;
    }
    /**
     * @return string
     */
    public function getUserLanguage() {
        return $this->userLanguage;
    }
}