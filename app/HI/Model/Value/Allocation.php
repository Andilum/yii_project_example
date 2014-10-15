<?php

namespace HI\Model\Value;

/**
 * Объект отеля
 */

class Allocation extends Base {
    /**
     * @var string
     */
    protected $_name;
    
    /**
     * @return string
     */
    public function getName() {
        return $this->_name;
    }
}

