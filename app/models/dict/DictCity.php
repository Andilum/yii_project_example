<?php

/**
 * This is the model class for table "dict.dict_city".
 *
 * The followings are the available columns in table 'dict.dict_city':
 * @property integer $id
 * @property string $name
 * @property boolean $active
 * @property boolean $trash
 * @property string $updated
 * @property string $date_create
 * @property integer $country
 * @property integer $district
 * @property string $name_eng
 * @property integer $staff_modified
 * @property integer $resort
 * @property string $date_modified
 */
class DictCity extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dict.dict_city';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, name, active, trash, updated, date_create, staff_modified, date_modified', 'required'),
            array('id, country, district, staff_modified, resort', 'numerical', 'integerOnly' => true),
            array('name, name_eng', 'length', 'max' => 50),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'active' => 'Active',
            'trash' => 'Trash',
            'updated' => 'Updated',
            'date_create' => 'Date Create',
            'country' => 'Country',
            'district' => 'District',
            'name_eng' => 'Name Eng',
            'staff_modified' => 'Staff Modified',
            'resort' => 'Resort',
            'date_modified' => 'Date Modified',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DictCity the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
