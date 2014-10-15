<?php

namespace HI\Model\Entity;

/**
 * Базовый класс объектов
 */

abstract class Base extends \CModel {
    /**
     * @var int
     */
    protected $_id;
    
    /**
     * @return int
     */
    public function getId() {
        return $this->_id;
    }
    
    public function __clone() {
        $this->_id = null;
    }
}

