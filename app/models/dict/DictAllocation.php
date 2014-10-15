<?php

/**
 * This is the model class for table "dict.dict_allocation".
 *
 * The followings are the available columns in table 'dict.dict_allocation':
 * @property integer $id
 * @property string $name
 * @property string $name_eng
 * @property integer $cat
 * @property integer $resort
 */
class DictAllocation extends DictBase {
    public $id;
    public $name;
    protected $_photoUrl;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dict.dict_allocation';
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
            array('id, cat, resort', 'numerical', 'integerOnly' => true),
            array('name, name_eng', 'length', 'max' => 100),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'alloccat' => array(self::BELONGS_TO, 'DictAlloccat', 'cat'),
            're' => array(self::BELONGS_TO, 'DictResort', 'resort'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'name_eng' => 'Name Eng',
            'cat' => 'Cat',
            'resort' => 'Resort',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DictAllocation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Условия для выбора типов фоток
     * @return string
     */
    public static function getPhotoTypeCriteria() {
        return 'in (5,6,7,8,1,9,11,12,13,14,17)';
    }

    public function getPhotoUrl($size = 'b') {
        if (!$this->_photoUrl) {
            $photoId = Yii::app()->db->createCommand("select id,ext_small from " . DictPhoto::model()->tableName() . " where eid={$this->id} and type_id " . self::getPhotoTypeCriteria() . " order by main desc")->queryRow();
            if ($photoId) {
                $this->_photoUrl = 'http://tophotels.ru/icache/hotel_photos/1/1/1/' . $photoId['id'] . $size . '.' . $photoId['ext_small'];
            } else {
                $this->_photoUrl = DictPhoto::HOTEL_PHOTO_NONE_URL;
            }
        }
        return $this->_photoUrl;
    }
    
    public function getUrl()
    {
        return Yii::app()->createUrl('allocation/view',array('id'=>  $this->id));
    }
    
    public function getName()
    {
        //можно сделать вывод в зависимости от языка $this->name_eng
        return $this->name;
    }

    /**
     * @return CDbDataReader|mixed|string
     */
    public static function getTotalCount() {
        return self::model()->count("active = 't' AND trash = 'f'");
    }
}
