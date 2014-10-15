<?php

/**
 * поведение для ApiPost и ApiComponent , функции перемещены из BaseMessage
 */
class ApiMessageBehavior extends CActiveRecordBehavior {

    public function getPhotosApi() {
        $result = array();
        if ($this->owner->photo) {
            foreach ($this->owner->photo as $photo) {
                $result[$photo->id]['id'] = $photo->id;
                $result[$photo->id]['width'] = $photo->width;
                $result[$photo->id]['height'] = $photo->height;
                $result[$photo->id]['url'] = Yii::app()->request->hostInfo . $photo->getFileUrl();
            }
        }

        return $result;
    }

    public function getLikesApi() {
        $likesList = Like::getListByOwnerId($this->owner->primaryKey);
        $result = array();
        foreach ($likesList as $like) {
            $result[] = $like->user->nick;
        }
        return $result;
    }

}
