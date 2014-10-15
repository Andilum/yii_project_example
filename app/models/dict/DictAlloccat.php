<?php

/**
 * This is the model class for table "dict.dict_alloccat".
 *
 * The followings are the available columns in table 'dict.dict_alloccat':
 * @property integer $id
 * @property string $name
 * @property string $nick
 * @property string $name_eng
 * @property string $description
 * @property string $weight
 */
class DictAlloccat extends DictBase {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dict.dict_alloccat';
    }

    public function primaryKey() {
        return 'id';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'numerical', 'integerOnly' => true),
            array('name, nick, name_eng', 'length', 'max' => 50),
            array('description', 'length', 'max' => 255),
            array('weight', 'length', 'max' => 6),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, nick, name_eng, description, weight', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'nick' => 'Nick',
            'name_eng' => 'Name Eng',
            'description' => 'Description',
            'weight' => 'Weight',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('nick', $this->nick, true);
        $criteria->compare('name_eng', $this->name_eng, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('weight', $this->weight, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DictAlloccat the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
