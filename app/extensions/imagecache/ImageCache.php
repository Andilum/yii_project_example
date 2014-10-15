<?php
class ImageCache extends CConsoleApplication {

    const DEFAULT_DIR = 'icache';

    public $commandMap = array(
        'publish' => array(
            'class' => 'ext.imagecache.commands.PublishCommand'
        )
    );

    protected $_files;
    protected $_handlers = array();
    protected $_tmpPath;
    protected $_publicPath;

    public function init() {
        parent::init();
        Yii::import('ext.imagecache.*');
    }

    public function setFiles($files) {
        $this->_files = $files;
    }

    public function getHandler($type) {
        if (!isset($this->_handlers[$type])) {
            if (!isset($this->_files[$type])) {
                throw new CException(Yii::t('system', 'Handler {type} not registered', array('{type}' => $type)));
            }
            $this->_handlers[$type] = Yii::createComponent(CMap::mergeArray($this->_files[$type], array('name' => $type)));
        }

        return $this->_handlers[$type];
    }

    public function setTmpPath($path) {
        $this->_tmpPath = $path;
    }

    public function getTmpPath() {
        return $this->_tmpPath;
    }

    public function setPublicPath($publicPath) {
        $this->_publicPath = $publicPath;
    }

    public function getPublicPath() {
        return $this->_publicPath;
    }

}