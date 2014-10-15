<?php

/**
 * Description of ApiUser
 */
class ApiUser extends User {

    public static function getDataApi($user) {
        return array(
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'nick' => $user->nick,
            'email' => $user->email,
            'avatar' => self::getAvatarPath($user->id, self::AVATAR_SIZE_50),
        );
    }

    public function behaviors() {
        return array(
            'apiMsg' => array('class' => 'ApiMessageBehavior'));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}