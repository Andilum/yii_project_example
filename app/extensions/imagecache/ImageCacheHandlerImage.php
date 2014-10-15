<?php
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Fill;
use Imagine\Image\Color;

class ImageCacheHandlerImage extends ImageCacheHandler {

    protected $_image;
    protected $_sections;
    protected $_maxwidth;
    protected $_dummy = false;

    private $_tmpFile;

    public function handle($tmp, $fileData, array $extraData = array()) {
        require Yii::getPathOfAlias('ext') . '/imagine.phar';

        $imagine = new Imagine\Gd\Imagine();

        try {
            $file = $imagine->open($tmp);

            if (!isset($fileData['section'])) {
                return $this->handleOriginal($file, $tmp);
            }

            if (!isset($this->_sections[$fileData['section']])) {
                throw new CException(Yii::t('system', 'Image config section not found'));
            }

            $transformation = new Imagine\Filter\Transformation();
            $section =& $this->_sections[$fileData['section']];
            if (isset($section['crop']) && is_callable($callback = $section['crop'])) {
                $cords = $callback($fileData, $extraData);
                if (is_array($cords)) {
                    $transformation->crop(new Imagine\Image\Point($cords[0], $cords[1]), new Imagine\Image\Box($cords[2], $cords[3]));
                }
            }

            $type = (!isset($section['type']) || $section['type'] == 'inset') ? Imagine\Image\ImageInterface::THUMBNAIL_INSET : Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
            $transformation
                ->thumbnail(new Box($section['width'], $section['height']), $type)
                ->save($tmp, array('quality' => 100));
            $transformation->apply($file);

            return $tmp;
        } catch (Imagine\Exception\InvalidArgumentException $e) {
            return ($this->_dummy) ? $this->createDummy($fileData) : false;
        }
    }

    protected function handleOriginal(Imagine\Image\ImageInterface $file, $tmp) {
        $transformation = new Imagine\Filter\Transformation();

        if ($this->_maxwidth) {
            $box = $file->getSize();
            if ($this->_maxwidth < $box->getWidth()) {
                $transformation->resize($box->scale($this->_maxwidth / $box->getWidth()));
            }
        }

        $transformation->save($tmp, array('quality' => 100));
        $transformation->apply($file);
        return $tmp;
    }

    public function setSections($array) {
        $this->_sections = $array;
    }

    protected function createDummy($fileData) {
        $imagine = new Imagine\Gd\Imagine();

        $tmpDir = Yii::app()->tmpPath;
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir);
        }


        $this->_tmpFile = $tmpDir . '/' . $fileData['file'];

        if(isset($fileData['section']) && isset($this->_sections[$fileData['section']])) {
            $section =& $this->_sections[$fileData['section']];
            $box = new Box($section['width'], $section['height']);
        } else {
            $box = new Box(1, 1);
        }

        $thumb = $imagine->create($box, new Color('#fff'));
        $thumb->save($this->_tmpFile);

        return $this->_tmpFile;
    }

    public function setMaxwidth($maxwidth) {
        $this->_maxwidth = (int) $maxwidth;
    }

    public function setDummy($dummy) {
        $this->_dummy = (boolean)$dummy;
    }


    public function __destruct() {
        if(isset($this->_tmpFile) && is_file($this->_tmpFile)) {
            unlink($this->_tmpFile);
        }
    }

}