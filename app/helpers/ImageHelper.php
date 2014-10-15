<?php

class ImageHelper {
    /**
     * @param $fileData
     * @param $extraData
     * @return array|bool
     */
    public static function getCropCords($fileData, $extraData) {
        $x = 0;
        $y = 0;
        $width = $extraData['width'];
        $height = $extraData['height'];

        if (!$width || !$height) {
            require Yii::app()->basePath . '/models/Photo.php';
            $criteria = new CDbCriteria();
            $criteria->select = 'id, ext';
            $photo = Photo::model()->findByPk($extraData['id'], $criteria);
            list($width, $height) = getimagesize($photo->getFilePath());
        }

        if ($width > $height) {
            $x = ($width - $height) / 2;
            $width = $height;
        } elseif ($width < $height) {
            $y = ($height - $width) / 2;
            $height = $width;
        }

        //list($newWidth, $newHeight) = explode('x', $fileData['section']);
        //if ($newWidth >= $width || $newHeight >= $height) {
        //    return false;
        //}

        return array($x, $y, $width, $height);
    }
} 