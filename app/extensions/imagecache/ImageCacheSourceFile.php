<?php

class ImageCacheSourceFile extends ImageCacheSource {

    protected $_path;

    public function path(array $file) {
        if (!file_exists($this->_path . '/' . $file['src'])) {
            throw new CException(Yii::t('system', 'File not found'));
        }

        return $this->_path . '/' . $file['src'];
    }

    public function setPath($path) {
        $this->_path = $path;
    }
}