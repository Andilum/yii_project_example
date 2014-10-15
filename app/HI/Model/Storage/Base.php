<?php

namespace HI\Model\Storage;

/**
 * Базовый класс хранилищ
 */

use HI\Model as HM;

abstract class Base extends \CDataProvider {
    const DEFAULT_LIMIT = 10;
    
    /**
     * @var \HI\Model\Storage\Base 
     */
    protected static $_instance;
    
    /**
     * @var \CDbConnection 
     */
    protected $_connection;
    
    /**
     * @var \CDbCommand
     */
    protected $_currentFindCommand;

    /**
     * @var string
     */
    protected $_currentCountSelectField;
    
    /**
     * @var string
     */
    protected $_objectBuilderClassName;
    
    /**
     * @var string|callable
     */
    protected $_dataKeysGetter;
    
    /**
     * @var int
     */
    protected $_limit = 0;
    
    /**
     * @var int
     */
    protected $_offset = 0;
    
    /**
     * @return \HI\Model\Storage\Base 
     */
    public static function instance() {
        if ( !static::$_instance ) {
            static::$_instance = new static();
        }
        
        return static::$_instance;
    }
    
    protected function __construct() {
        $this->_setupConnection()->_setupDataKeysGetter();
        
        if ( !$this->_objectBuilderClassName || !class_exists($this->_objectBuilderClassName)) {
            throw new \CException("Correct object builder class name must be setted");
        }
    }
    
    /**
     * @param int $offset
     * @return \HI\Model\Storage\Base 
     */
    public function setOffset($offset) {
        $this->_offset = (int)$offset;
        
        return $this;
    }
    
    /**
     * @param int $limit
     * @return \HI\Model\Storage\Base 
     */
    public function setLimit($limit) {
        $this->_limit = (int)$limit;
        
        return $this;
    }
    
    /**
     * @return \HI\Model\Storage\Base 
     */
    public function useDefaults() {
        $this->_limit = $this->_getDefaultLimit();
        
        return $this;
    }

    /**
     * @return \HI\Model\Storage\Base 
     */
    protected function _setupConnection() {
        $this->_connection = \Yii::app()->getDb();
        
        return $this;
    }
    
    /**
     * @return \HI\Model\Storage\Base 
     */
    protected function _setupDataKeysGetter() {
        $this->_dataKeysGetter = "getId";
        
        return $this;
    }
    
    /**
     * @return \CTypedList 
     */
    protected function fetchData() {
        $this->_checkCurrentFindCommand();
        
        if ( $this->_limit ) {
            $this->_currentFindCommand->setLimit($this->_limit);
        }
        
        if ( $this->_offset ) {
            $this->_currentFindCommand->setLimit($this->_offset);
        }

        $rawData = $this->_currentFindCommand->execute()->queryAll();
        $rawData = $this->_prepareRawData($rawData);
        
        return call_user_func(array($this->_currentFindCommand, "buildList"), $rawData);
    }
    
	/**
	 * @return array
	 */
	protected function fetchKeys() {
        if ( empty($this->getData()) ) {
            throw new \CException("Key list cannot be getter from empy. Use getData before");
        }
        
        $result = array();
        
        if ( is_callable($this->_dataKeysGetter) ) {
            $dataKeysGetter = $this->_dataKeysGetter;
            
            foreach($this->getData() as $element) {
                $result[] = $dataKeysGetter($element);
            }
        } else if ( is_string($this->_dataKeysGetter) ) {
            foreach($this->getData() as $element) {
                $result[] = $element->{$this->_dataKeysGetter}();
            }
        } else {
            throw new \CException("Data keys getter must be callable or string, not else");
        }
        
        return $result;
    }
    
	/**
	 * @return integer
	 */
	protected function calculateTotalItemCount() {
        $this->_checkCurrentFindCommand();
        
        if ( !$this->_currentCountSelectField ) {
            throw new \CException("Count query cannot be proccessed without sql field defitiniton");
        }
        
        $limit = $this->_currentFindCommand->getLimit();
        $offset = $this->_currentFindCommand->getOffset();
        $select = $this->_currentFindCommand->getSelect();
        
        $this->_currentFindCommand->limit(0, 0);
        $this->_currentFindCommand->setSelect($this->_currentCountSelectField);
        $count = (int)$this->_currentFindCommand->queryColumn();
        
        $this->_currentFindCommand->limit($limit, $offset);
        $this->_currentFindCommand->setSelect($select);
        
        return $count;
    }

    /**
     * @return \HI\Model\Storage\Base
     * @throws \CException 
     */
    protected function _checkCurrentFindCommand() {
        if ( !$this->_currentFindCommand instanceof \CDbCommand ) {
            throw new \CException("Please, define find command before fetch data");
        }
        
        return $this;
    }
    
    /**
     * @return int
     */
    protected function _getDefaultLimit() {
        return static::DEFAULT_LIMIT;
    }
    
    /**
     * @param \HI\Model\Entity\Base
     * @return boolean
     */
    abstract public function save(HM\Entity\Base $object);
    
    /**
     * @return \CDbCommand 
     */
    abstract protected function _createBaseFindCommand();
    
    /**
     * @return array 
     */
    abstract protected function _prepareRawData($rawData);
}

