<?php

abstract class AuthTokenHelper {
    
    public static function getToken() {
        if ( Yii::app()->user->isGuest ) {
            return null;
        }
        
        $token = UserToken::model()->find('tp_user_id = :id AND trash = false', array(':id' => Yii::app()->user->id));
        if (!$token) {
            $token = UserToken::model()->createForUserId(Yii::app()->user->id);
        }
        
        if ($token) {
            return $token->token;
        }
        return null;
    }
    
}