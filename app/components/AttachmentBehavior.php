<?php

class AttachmentBehavior extends CActiveRecordBehavior {

    public $entityType;

    // public $newAttachment=array();

    public function afterSave($event) {
        $key = $this->getKeyName();
        $attachment = !empty($this->owner->newAttachment) ? $this->owner->newAttachment : ((isset($_POST[$key]['attachments']) && is_array($_POST[$key]['attachments'])) ? $_POST[$key]['attachments'] : null);

        if ($attachment) {
            //добавление загруженных файов

            foreach ($attachment as $type => $values) {
                if ($type == 'file') {

                    foreach ($values as $value) {
                        list($id, $hash) = explode('_', $value);
                        $model = MessageAttachment::model()->findByPk($id, 'trash=TRUE');
                        if ($model && $model->validHash($hash)) {
                            $model->saveAttributes(array('trash' => false, 'entity_id' => $this->owner->primaryKey, 'entity_type' => $this->entityType));
                        }
                    }
                } elseif ($type == 'map') {
                    foreach ($values as $value) {
                        $model = new MessageAttachment();
                        $model->body = $value;
                        $model->entity_id = $this->owner->primaryKey;
                        $model->entity_type = $this->entityType;
                        $model->type = MessageAttachment::TYPE_MAP;
                        $model->trash = false;
                        $model->save();
                    }
                }
            }
        }
    }

    private function getKeyName() {
        return get_class($this->owner);
    }

    public function getAttachments() {
        if ($this->owner->isNewRecord)
            return array();
        return MessageAttachment::model()->findAll('entity_type=' . $this->entityType . ' and entity_id=' . $this->owner->primaryKey . ' and trash=FALSE');
    }

   

}
