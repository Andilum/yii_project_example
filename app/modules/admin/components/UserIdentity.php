<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    public function authenticate() {
        $deadm = array('admin' => 'admin');
      
      //  $record = Adminuser::model()->find('username=:l', array(':l' => $this->username));
       
            if ((isset($deadm[$this->username])) && ($deadm[$this->username] == $this->password)) {
                $this->setUserState(0, 'admin');
                $this->errorCode = self::ERROR_NONE;
            } else
                $this->errorCode = self::ERROR_USERNAME_INVALID;
        
       /* else if ($record->password !== md5($this->password . Yii::app()->params['slat']))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->setUserState($record->primaryKey, $record->role);
            $this->errorCode = self::ERROR_NONE;
        }*/
        return !$this->errorCode;
    }

    private function setUserState($id, $role) {
        $this->_id = $id;
        $this->setState('role', $role);
        $this->setState('isadminka', true);
    }

    public function getId() {
        return $this->_id;
    }

}