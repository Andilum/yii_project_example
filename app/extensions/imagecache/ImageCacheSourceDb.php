<?php

class ImageCacheSourceDb extends ImageCacheSource {

    public $cleanUp = true;

    protected $_table;
    protected $_pk = 'id';
    protected $_body = 'body';
    protected $_ext = 'ext';

    private $_tmpFile;

    public function path(array $file) {

        $db = Yii::app()->db;
        $select = array($this->_pk, $this->_body);
        if ($this->_ext) {
            $select[] = $this->_ext;
        }

        $fileData = Yii::app()->db->createCommand()
            ->select()
            ->from($this->_table)
            ->where($db->quoteColumnName($this->_pk) . '=?', array((int) $file['id']))
            ->queryRow();
        
        if (!isset($fileData[$this->_pk])) {
            return false;
        }
        
        if (!$this->_ext) {
            $this->_ext = 'ext';
            $fileData[$this->_ext] = $file['ext'];
        }

        $this->_data = $fileData;
        unset($this->_data[$this->_body]);

        return $this->putFile($fileData);
    }

    protected function putFile(&$fileData) {
        $tmpDir = Yii::app()->tmpPath;
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir);
        }

        $this->_tmpFile = $tmpDir . '/' . $fileData[$this->_pk] . '.' . $fileData[$this->_ext];
        if ($res = file_put_contents($this->_tmpFile, $fileData[$this->_body], FILE_BINARY)) {
            return $this->_tmpFile;
        } else {
            return false;
        }
    }

    public function __destruct() {
        if($this->cleanUp) {
            @unlink($this->_tmpFile);
        }
    }

    public function setTable($table) {
        $this->_table = $table;
    }

    public function setPk($pk) {
        $this->_pk = $pk;
    }

    public function setExt($ext) {
        $this->_ext = $ext;
    }

    public function setBody($body) {
        $this->_body = $body;
    }
}