<?php

namespace HI\Model\Value;

/**
 * Базовый класс объектов
 */

abstract class Base extends \CComponent {
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
}

