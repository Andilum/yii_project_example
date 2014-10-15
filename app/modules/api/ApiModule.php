<?php

class ApiModule extends CWebModule {

    public function init() {

        // import the module-level models and components
        $this->setImport(array(
            'api.models.*',
            'api.models.dict.*',
            'api.components.*',
            'admin.components.ControllerAdmin',
        ));

        Yii::app()->setComponent('errorHandler', array(
            'class' => 'ApiErrorHandler',
        ));

        Lightsoft\REST\Server::setAuthenticator(UserToken::model());
    }

}
