<?php

/**
 * This is the model class for table "hi.hi_rating_service".
 *
 * The followings are the available columns in table 'hi.hi_rating_service':
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property boolean $trash
 */
class RatingService extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_rating_service';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, category_id', 'required'),
            array('category_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
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
            'category' => array(self::BELONGS_TO, 'RatingCategory', 'category_id', 'on' => "category.trash = 'f'"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'category_id' => 'Category',
            'trash' => 'Trash',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RatingService the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
