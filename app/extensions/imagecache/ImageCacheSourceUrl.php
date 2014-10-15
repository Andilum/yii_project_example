<?php

class ImageCacheSourceUrl extends ImageCacheSource {

    protected $_host;

    public function path(array $file) {
        return $this->putFile($this->_host . '/' . $file['src']);
    }

    protected function putFile($url) {
        $tmpDir = Yii::app()->tmpPath;
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir);
        }

        $filePath = $tmpDir . '/' . basename($url);
        if (@copy($url, $filePath)) {
            return $filePath;
        } else {
            return false;
        }
    }

    public function setHost($host) {
        $this->_host = $host;
    }
}