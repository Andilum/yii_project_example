<?php

/**
 * This is the model class for table "hi.hi_rating".
 *
 * The followings are the available columns in table 'hi.hi_rating':
 * @property integer $id
 * @property integer $rate
 * @property string $label
 * @property string $description
 * @property boolean $trash
 */
class Rating extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_rating';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('rate, label, description', 'required'),
            array('rate', 'numerical', 'integerOnly' => true),
            array('label, description', 'length', 'max' => 255),
            array('trash', 'safe'),
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
            'rate' => 'Rate',
            'label' => 'Label',
            'description' => 'Description',
            'trash' => 'Trash',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Rating the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getMaxRating() {
        return Yii::app()->db->createCommand()
            ->select('MAX(rate)')
            ->from('hi.hi_rating rating')
            ->where("rating.trash = 'f'")
            ->queryScalar();
    }
}
