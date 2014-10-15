<?php

/**
 * This is the model class for table "dict.dict_photo".
 *
 * The followings are the available columns in table 'dict.dict_photo':
 * @property integer $id
 * @property integer $eid
 * @property integer $type_id
 * @property integer $cat
 * @property integer $big_size
 * @property integer $big_width
 * @property integer $big_height
 * @property integer $updated
 * @property integer $format
 * @property integer $main
 * @property integer $active
 * @property integer $trash
 * @property string $date_create
 * @property string $ext_small
 * @property string $ext_big
 * @property string $ext_gig
 * @property string $name
 * @property integer $is_restored
 */
class DictPhoto extends CActiveRecord {
    const HOTEL_PHOTO_NONE_URL = '/i/hotel_photo_none.jpg';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DictPhoto the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dict.dict_photo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, eid, type_id, cat, big_size, big_width, big_height, updated, format, main, active, trash, date_create, is_restored', 'required'),
            array('id, eid, type_id, cat, big_size, big_width, big_height, updated, format, main, active, trash, is_restored', 'numerical', 'integerOnly' => true),
            array('ext_small, ext_big, ext_gig', 'length', 'max' => 4),
            array('ext_small, ext_big, ext_gig, name', 'default', 'setOnEmpty' => true),
            array('name', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, eid, type_id, cat, big_size, big_width, big_height, updated, format, main, active, trash, date_create, ext_small, ext_big, ext_gig, name, is_restored', 'safe', 'on' => 'search'),
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
            'eid' => 'Eid',
            'type_id' => 'Type',
            'cat' => 'Cat',
            'big_size' => 'Big Size',
            'big_width' => 'Big Width',
            'big_height' => 'Big Height',
            'updated' => 'Updated',
            'format' => 'Format',
            'main' => 'Main',
            'active' => 'Active',
            'trash' => 'Trash',
            'date_create' => 'Date Create',
            'ext_small' => 'Ext Small',
            'ext_big' => 'Ext Big',
            'ext_gig' => 'Ext Gig',
            'name' => 'Name',
            'is_restored' => 'Is Restored',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('eid', $this->eid);
        $criteria->compare('type_id', $this->type_id);
        $criteria->compare('cat', $this->cat);
        $criteria->compare('big_size', $this->big_size);
        $criteria->compare('big_width', $this->big_width);
        $criteria->compare('big_height', $this->big_height);
        $criteria->compare('updated', $this->updated);
        $criteria->compare('format', $this->format);
        $criteria->compare('main', $this->main);
        $criteria->compare('active', $this->active);
        $criteria->compare('trash', $this->trash);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('ext_small', $this->ext_small, true);
        $criteria->compare('ext_big', $this->ext_big, true);
        $criteria->compare('ext_gig', $this->ext_gig, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('is_restored', $this->is_restored);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    /**
     * Адрес картинки
     * @param string $size  s/b
     * @return type 
     */
    public function getUrl($size = 'b') {
        return 'http://tophotels.ru/icache/hotel_photos/1/1/1/' . $this->id . $size . '.' . $this->ext_small;
    }

    public static function getListByEids($eids) {
        $criteria = new CDbCriteria();
        $criteria->select = 'DISTINCT ON (eid) t.id, t.ext_small, eid';
        $criteria->addInCondition('t.eid', $eids);
        $criteria->addCondition('active = 1 AND trash = 0 AND type_id ' . DictAllocation::getPhotoTypeCriteria());
        $criteria->order = 'eid, main DESC';
        $photoList = self::model()->findAll($criteria);

        $photos = array();
        foreach ($photoList as $photo) {
            $photos[$photo->eid] = $photo;
        }

        return $photos;
    }
}