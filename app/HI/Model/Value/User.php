<?php

namespace HI\Model\Value;

/**
 * Объект пользователя
 */

class User extends Base {
    /**
     * @var string
     */
    protected $_name;
    
    /**
     * @var string
     */
    protected $_nick;
    
    /**
     * @var string
     */
    protected $_email;
    
    /**
     * @return string
     */
    public function getName() {
        return $this->_name;
    }
    
    /**
     * @return string
     */
    public function getNick() {
        return $this->_nick;
    }
    
    /**
     * @return string
     */
    public function getEmail() {
        return $this->_email;
    }
}

