<?php

abstract class ImageCacheSource extends CComponent {
    protected $_data = array();

    public function getData() {
        return $this->_data;
    }

    abstract public function path(array $fileData);
}