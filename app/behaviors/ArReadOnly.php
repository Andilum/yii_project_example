<?php

class ArReadOnly extends CActiveRecordBehavior
{
    protected function beforeSave($event)
    {
        // add an error for the first attribute of the primary key
        $pk = $this->primaryKey();
        if (!$pk) {
            $pk = $this->metaData->tableSchema->primaryKey;
        }
        if (is_array($pk)) {
            $pk = $pk[0];
        }

        $this->addError('id', 'Модель доступна только для чтения');

        // disallow saving
        return false;
    }
}
