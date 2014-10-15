<?php

/**
 * This is the model class for table "hi.hi_hotel_chat".
 *
 * The followings are the available columns in table 'hi.hi_hotel_chat':
 * @property integer $id
 * @property integer $hotel_id
 * @property string $title
 * @property string $description
 * @property string $users
 * @property integer $icon
 * @property string $date_create
 * 
 * @property MessageHotelChat[] $messages
 * @property integer $messagesCount
 */
class HotelChat extends CActiveRecord
{
    
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hi.hi_hotel_chat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                      array('title,hotel_id', 'required'),
                    
			array('hotel_id, icon', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('description', 'length', 'max'=>500),
			//array('users, date_create', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, hotel_id, title, description, users, icon, date_create', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                    'messages' => array(self::HAS_MANY, 'MessageHotelChat', 'chat_id'),
                    'messagesCount' => array(self::STAT, 'MessageHotelChat', 'chat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'hotel_id' => 'Hotel',
			'title' => 'Title',
			'description' => 'Description',
			'users' => 'Users',
			'icon' => 'Icon',
			'date_create' => 'Date Create',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('hotel_id',$this->hotel_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('users',$this->users,true);
		$criteria->compare('icon',$this->icon);
		$criteria->compare('date_create',$this->date_create,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HotelChat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        /**
         * 
         * @return MessageHotelChat
         */
        public function getLastMessage()
        {
            return MessageHotelChat::model()->find(array('condition'=>'chat_id='.$this->id,'order'=>'id desc'));
        }
}
