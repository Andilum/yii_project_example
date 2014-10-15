<?php

/**
 * This is the model class for table "dict.dict_country".
 *
 * The followings are the available columns in table 'dict.dict_country':
 * @property integer $id
 * @property string $name
 * @property string $name_eng
 * @property string $nick
 * @property string $label
 * @property integer $region
 * @property string $name_genitive
 * @property boolean $active
 * @property string $date_create
 * @property integer $phone_code
 * @property string $updated
 */
class DictCountry extends DictBase {
    public function behaviors()
    {
        return array(
            'readOnly' => 'application.behaviors.ArReadOnly',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'dict.dict_country';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function findAllWithNames()
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'id, name';
        return $this
            ->getCommandBuilder()
            ->createFindCommand($this->getTableSchema(),$criteria)
            ->queryAll();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DictCountry the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
