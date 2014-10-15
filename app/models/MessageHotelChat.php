<?php

/**
 * This is the model class for table "hi.hi_message_hotel_chat".
 *
 * The followings are the available columns in table 'hi.hi_message_hotel_chat':
 * @property integer $id
 * @property integer $user_from_id
 * @property integer $chat_id
 * @property string $message
 * @property string $date_create
 * 
 * @property User $user_from
 * @property HotelChat $chat
 */
class MessageHotelChat extends CActiveRecord {
    
     public $newAttachment = array();

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_message_hotel_chat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('chat_id,user_from_id', 'required'),
            array('user_from_id, chat_id', 'numerical', 'integerOnly' => true),
            array('message', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_from_id, chat_id, message, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user_from' => array(self::BELONGS_TO, 'User', 'user_from_id'),
            'chat' => array(self::BELONGS_TO, 'HotelChat', 'chat_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_from_id' => 'User From',
            'chat_id' => 'Chat',
            'message' => 'Message',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_from_id', $this->user_from_id);
        $criteria->compare('chat_id', $this->chat_id);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('date_create', $this->date_create, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MessageHotelChat the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function behaviors() {

        return array(
            'fileload' => array('class' => 'AttachmentBehavior',
                'entityType' => MessageAttachment::ENTITY_MESSAGE_CHAT
        ));
    }
    
    public function getMessageEncode()
    {
        return str_replace("\n", '',nl2br(CHtml::encode($this->message)));
    }
    
     protected function afterSave() {
          parent::afterSave();
          
        if ($this->isNewRecord) {
            Event::object()->send(Event::EVENT_CHAT, array('chat_id' => $this->chat_id,  'id' => $this->id));
        }
      
    }

}