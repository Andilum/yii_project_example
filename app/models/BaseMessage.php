<?php
   
class BaseMessage extends CActiveRecord {

  
    public function attachPhotos(array $photosIds) {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $photosIds);
        $photos = Photo::model()->findAll($criteria);
        if ($photos) {
            foreach ($photos as $photo){
                if ($photo->tp_user_id == $this->tp_user_id && $photo->owner_id == -1) {
                    Photo::model()->updateByPk($photo->id, array('owner_id'=>$this->id));
                }
            }
        }
    }
    
    public static function destroyById($id, $userId) {
        return static::model()->updateByPk($id, array('trash' => true), 'tp_user_id = :userId', array(':userId' => $userId));
    }
}