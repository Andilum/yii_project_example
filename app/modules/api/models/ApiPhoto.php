<?php

/**
 * Description of ApiPhoto
 */
class ApiPhoto extends Photo {
    public static function create($data, $userId) {
        if (!$userId){
            return 0;
        }
        $data['owner_id'] = -1;
        $photo = new ApiPhoto();
        $photo->attributes = $data;
        $photo->tp_user_id = $userId;
        if ($photo->save()) {
            $result['id'] = $photo->id;
            $result['width'] = $photo->width;
            $result['height'] = $photo->height;
            $result['url'] = Yii::app()->request->hostInfo.$photo->getFileUrl();
            return $result;
        } else {
            return 0;
        }
    }
    
     public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
