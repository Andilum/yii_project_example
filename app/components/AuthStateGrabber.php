<?php

/**
 * Класс для кроссдоменной авторизации
 *
 * Для использования необходимо подключить как компонент, настроить $key, $secret
 *
 *
 */
Yii::import('vendor.lightsoft.Auth.OAuth.*');
Yii::import('vendor.lightsoft.Auth.OAuth.Client.*');

class AuthStateGrabber extends \CApplicationComponent {
    /**
     * @var \Lightsoft\Auth\OAuth\Client\UserIdentity
     */
    public $providerClass = 'Lightsoft\Auth\OAuth\Client\UserIdentity';

    /**
     * @var string OAuth consumer key. Defaults to 'anonymous'
     */
    public $key = 'anonymous';

    /**
     * @var string OAuth consumer secret. Defaults to 'anonymous'
     */
    public $secret = 'anonymous';
    public $duration = 1209600; // 2 weeks
    public $authUrl = 'http://beta.travelpassport.ru/oauth';
    public $requestUrl = 'http://beta.travelpassport.ru/oauth/requestToken';
    public $accessUrl = 'http://beta.travelpassport.ru/oauth/accessToken';
    public $userInfoUrl = 'http://beta.travelpassport.ru/oauth/userInfo';
    public $logoutUrl = 'http://beta.travelpassport.ru/auth/logout';

    private $provider;
    private $_error;

    public function init() {
        $this->setProvider();

        if ( !Yii::app()->user->isGuest )
        {
            $this->provider->obtainStateFromWebUser();
        }

        if (
                (!Yii::app()->user->isGuest)
             && (!$this->provider->getIsAuthenticated())
            )
        {
            Yii::app()->user->logout();
        }

        $this->_tryAutoauthenticate();

        if (!Yii::app()->user->isGuest && $this->getPopupEnabled())
        {
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * @return bool
     */
    public function authenticate() {
        if ( Yii::app()->user->isGuest ) {
            try {
                if($this->getPopupEnabled())
                    $this->provider->setAdditionalRequestParamList(array('lang' => \Yii::app()->language));

                if ( $this->provider->authenticate() ) {
                    Yii::app()->user->login($this->provider, $this->duration);

                    if ( $this->getPopupEnabled() ) {
                        $this->redirect();
                    }

                } else if($this->getPopupEnabled()) {
                    $this->redirect(Yii::app()->baseUrl.'/');
                }
            } catch (\OAuthException $e) {
                $this->_error = $e->getMessage();
            }
        } else {
            $this->provider->obtainStateFromWebUser();
        }

        return $this->provider->getIsAuthenticated();
    }

    public function redirect($url =null) {
        require_once __DIR__ . '/AuthRedirectWidget.php';
        $widget = Yii::app()->getWidgetFactory()->createWidget($this, 'AuthRedirectWidget', array(
            'url' => $url,
        ));
        $widget->init();
        $widget->run();
    }

    private function getPopupEnabled()
    {
        return Yii::app()->request->getParam('popup');
    }

    public function getIsAuthenticated() {
        return $this->provider->getIsAuthenticated();
    }

    public function getGlobalUserAuthenticated() {
        return $this->provider->getUserAuthenticated();
    }

    private function setProvider() {
        if ( $this->provider === null ) {
            $class = $this->providerClass;

            $this->provider = $class::init(
                $this->key, // consumer key
                $this->secret, // consumer secret
                $this->requestUrl, //request token url
                $this->accessUrl, //access token url
                $this->authUrl, //auth url
                $this->userInfoUrl //user info url
            );

            if ( !$this->provider ) {
                throw new \CException('Error on create OAuth provider');
            }
        }

        return $this->provider;
    }

    public function __call($method, $args) {
        if (!preg_match("/^getUser(.+)$/", $method, $m)) {
            return parent::__call($method, $args);
        }
        return $this->provider->$method();
    }

    public function logout() {
        Yii::app()->user->logout(true);

        Yii::app()->getRequest()->redirect(
            $this->logoutUrl,
            array("oauth_token" => $this->provider->getAccessToken()->getKey()) // TODO иногда вылетает ошибка
        );

        Yii::app()->end();
    }

    public function getError() {
        return $this->_error;
    }

    protected function _tryAutoauthenticate() {
        $userToken = \Yii::app()->getRequest()->getQuery('user_token');

        if ( $userToken ) {

            if(!Yii::app()->user->isGuest)
            {
                Yii::app()->user->logout(true);
                Yii::app()->request->redirect('?user_token='.$userToken);
            }

            $this->provider->setAdditionalRequestParamList(array("user_token" => $userToken));

            if ( $this->authenticate() ) {
                unset($_GET["user_token"]);
                unset($_GET[\Lightsoft\Auth\OAuth::TOKEN_PARAM_KEY]);
                unset($_GET[\Lightsoft\Auth\OAuth::TOKEN_SECRET_PARAM_KEY]);

                $url = Yii::app()->getRequest()->getHostInfo() . "/";

                if ( Yii::app()->getRequest()->getPathInfo() ) {
                    $url .= "/" . Yii::app()->getRequest()->getPathInfo() . "/";
                }

                if ( !empty($_GET) ) {
                    $url .= "?" . http_build_query($_GET);
                }

                Yii::app()->getRequest()->redirect($url);
            }
        }

        return $this;
    }
}
