<?php

abstract class ImageCacheHandler extends CComponent {

    protected $_source;
    protected $_name;

    public function setSource($source) {
        $this->_source = Yii::createComponent($source);
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function publish($url) {
        $fileData = $this->parseFileName($url);
        $tmp = $this->_source->path($fileData);

        //хак для флешек, появился из-за необходимости одинакового урл для получения картинок и флешек
        if ($fileData['ext'] != 'swf') {
            $tmp = $this->handle($tmp, $fileData, $this->_source->getData());
        }

        if ($tmp) {
            return $this->copy($tmp, $url);
        }
        return false;
    }

    public function parseFileName($file) {
        $data = array();

        $data['src'] = $file;
        $data['file'] = basename($data['src']);
        $data['dir'] = dirname($data['src']);

        $tokens = explode('.', $data['file']);
        if (sizeof($tokens) == 1) {
            $data['ext'] = '';
            $data['name'] = $tokens[0];
        } else {
            $data['ext'] = array_pop($tokens);
            $data['name'] = implode('.', $tokens);
        }

        $tokens = explode('_', $data['name']);
        if (is_numeric($tokens[0])) {
            $data['id'] = (int) array_shift($tokens);
        }

        if (sizeof($tokens) > 0) {
            if (preg_match('/^\d+x\d+$/', $tokens[0])) {
                $data['section'] = array_shift($tokens);
            }

            if (sizeof($tokens) > 0) {
                $data['title'] = implode('_', $tokens);
            }
        }

        return $data;
    }

    abstract public function handle($tmp, $fileData, array $extraData = array());

    public function copy($tmp, $dst) {
        $dst = Yii::app()->publicPath . '/' . $this->_name . '/' . $dst;
        if (!is_dir(dirname($dst))) {
            mkdir(dirname($dst), 0777, true);
        }

        if (copy($tmp, $dst)) {
            return $dst;
        }
        return false;
    }
}