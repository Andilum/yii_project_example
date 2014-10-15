<?php

/**
 * This is the model class for table "dict.dict_resort".
 *
 * The followings are the available columns in table 'dict.vw_dict_resort':
 * @property integer $id
 * @property integer $country
 * @property string $name
 * @property string $name_eng
 * @property integer $capital
 */
class DictResort extends DictBase {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dict.dict_resort';
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
            array('id, country, capital', 'numerical', 'integerOnly' => true),
            array('name, name_eng', 'length', 'max' => 50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, country, name, name_eng, capital', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'co' => array(self::BELONGS_TO, 'DictCountry', 'country'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'country' => 'Country',
            'name' => 'Name',
            'name_eng' => 'Name Eng',
            'capital' => 'Capital',
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
        $criteria->compare('country', $this->country);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('name_eng', $this->name_eng, true);
        $criteria->compare('capital', $this->capital);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DictResort the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
