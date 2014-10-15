<?php

abstract class SR extends CModel {
    const TYPE_ALLOCATION = 'allocation';
    const TYPE_USER = 'user';

    const DEFAULT_LIMIT = 50;

    /**
     * @var CSort
     */
    protected $_sort = null;

    /**
     * @var string
     */
    protected $_namePattern = '';

    /**
     * @var bool
     */
    protected $_nullResults = true;

    /**
     * @param string $type
     * @return \SRAllocation|\SRUser
     * @throws CException 
     */
    public static function model($type) {
        switch ($type) {
            case static::TYPE_ALLOCATION:
                return new SRAllocation();
            break;
            case static::TYPE_USER:
                return new SRUser();
            break;
            default:
                throw new CException("Wron rating type");
                break;
        }
    }

    /**
     * @param string $name
     * @return \SR 
     */
    public function setNamePattern($name) {
        $this->_namePattern = preg_replace("/[^\w\d\s]+/", "", mb_strtolower($name));

        return $this;
    }

    public function withoutNullResults() {
        $this->_nullResults = false;

        return $this;
    }

    abstract protected function _getSort();

    abstract protected function _getNameCondition();

    abstract protected function _getNullResultsCondition();

    abstract public function get($limit = self::DEFAULT_LIMIT, $offset = 0);
}