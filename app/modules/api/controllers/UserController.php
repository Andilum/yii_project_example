<?php

class UserController extends UnauthorizeController {
    public function actionView($nick) {
        try {
            $user = ApiUser::model()->find('nick = :nick', array(':nick' => $nick));
                    
            if ( !$user ) {
                $this->_responseNotFoundError('User not found');
            }
            
            $result= ApiUser::getDataApi($user);
        } catch (Exception $e) {
            $result = $e;
        }
        
        if ( !empty($result) ) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseWrongAuth();
        }
    }
}