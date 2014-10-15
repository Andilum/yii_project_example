<?php

namespace HI\Model\Entity;

/**
 * Объект поста
 */

class Post extends Base {
    /**
     * @var string
     */
    protected $_name;
    
    /**
     * @var \HI\Model\Entity\User
     */
    protected $_user;
    
    /**
     * @var \DateTime
     */
    protected $_date;
    
    /**
     * @var string
     */
    protected $_text;
    
    /**
     * @var \HI\Model\Value\Allocation
     */
    protected $_allocation;
    
    /**
     * @return array
     */
    public function attributeNames() {
        return array(
            "_id",
            "_name",
            "_user",
            "_date",
            "_text",
            "_allocation"
        );
    }
    
    public function rules() {
		return array(
            array("_user, _text, _allocation", "required"),
        );
	}
    
    /**
     * @return string
     */
    public function getName() {
        return $this->_name;
    }
    
    /**
     * @param string $name
     * @return \HI\Model\Entity\Post 
     */
    public function setName($name) {
        $this->_name = $name;
        
        return $this;
    }
    
    /**
     * @return \HI\Model\Entity\User
     */
    public function getUser() {
        return $this->_user;
    }

    /**
     * @param \HI\Model\Entity\User $user
     * @return \HI\Model\Entity\Post 
     */
    public function setUser(User $user) {
        return $this->_user = $user;
        
        return $this;
    }
    
    /**
     * @return \DateTime 
     */
    public function getDate() {
        return $this->_date;
    }
    
    /**
     * @param \DateTime $date
     * @return \HI\Model\Entity\Post 
     */
    public function setDate(\DateTime $date) {
        $this->_date = $date;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getText() {
        return $this->_text;
    }
    
    /**
     * @param string $text
     * @return \HI\Model\Entity\Post 
     */
    public function setText($text) {
        $this->_text = $text;
        
        return $this;
    }
}

