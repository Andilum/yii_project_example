<?php

/**
 * This is the model class for table "hi.hi_message_user".
 *
 * The followings are the available columns in table 'hi.hi_message_user':
 * @property integer $id
 * @property integer $user_from_id
 * @property integer $user_to_id
 * @property string $message
 * @property string $date_create
 * @property bool $read
 * 
 * @property User $user_from
 * @property User $user_to
 * 
 * @property boolean $trash_user1
 * @property boolean $trash_user2
 */
class MessageUser extends CActiveRecord {

    public $newAttachment = array();

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_message_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_from_id,user_to_id', 'required'),
            array('user_from_id, user_to_id', 'numerical', 'integerOnly' => true),
            array('message, date_create', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_from_id, user_to_id, message, date_create,read', 'safe', 'on' => 'search'),
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
            'user_to' => array(self::BELONGS_TO, 'User', 'user_to_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_from_id' => 'User From',
            'user_to_id' => 'User To',
            'message' => 'Message',
            'date_create' => 'Date Create',
            'read' => 'Прочитанно'
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
        $criteria->compare('user_to_id', $this->user_to_id);
        $criteria->compare('read', $this->read);
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
     * @return MessageUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function afterSave() {
        parent::afterSave();

        if ($this->isNewRecord) {
            Event::object()->send(Event::EVENT_MESSAGE, array('user_from_id' => $this->user_from_id, 'user_to_id' => $this->user_to_id, 'id' => $this->id));
        }
    }

    public function setCriteriaInterlocutors($userId1, $userId2, $authUserId = null) {
        if ($authUserId == null)
            $authUserId = Yii::app()->user->id;
        if (!$authUserId)
            throw new Exception('authUserId empty');
        if (min($userId1, $userId2) == $authUserId) {
            $conditionTrash = 'trash_user1=FALSE';
        } else {
            $conditionTrash = 'trash_user2=FALSE';
        }

        $this->getDbCriteria()->mergeWith(array(
            'condition' => self::getConditionChat($userId1, $userId2) . ' and ' . $conditionTrash,
        ));
        return $this;
    }

    /**
     * условия для выборки переписки между двумя пользователями
     * @param int $userId1
     * @param int $userId2
     * @return type
     */
    public static function getConditionChat($userId1, $userId2) {
        $userId1 = intval($userId1);
        $userId2 = intval($userId2);
        return '((user_from_id=' . $userId1 . ' AND user_to_id=' . $userId2 . ') OR (user_from_id=' . $userId2 . ' AND user_to_id=' . $userId1 . '))';
    }

    /**
     * количество не прочитанных сообщений
     * @param int $user_id
     * @return int
     */
    public static function getCountNoRead($user_id, $user_from_id = null) {
        $c = 'user_to_id=' . $user_id . ' and read=FALSE';
        if ($user_from_id)
            $c.=' and user_from_id=' . $user_from_id;
        return self::model()->count($c);
    }

    public function behaviors() {

        return array(
            'fileload' => array('class' => 'AttachmentBehavior',
                'entityType' => MessageAttachment::ENTITY_MESSAGE_USER
        ));
    }

    public function getMessageEncode() {
        return str_replace("\n", '', nl2br(CHtml::encode($this->message)));
    }

    public static function getSqlChats() {
        return 'SELECT * FROM (SELECT DISTINCT ON (CASE WHEN (t.user_from_id=:uid) THEN t.user_to_id ELSE t.user_from_id END)
            substring(t.message,1,100) as message, t.id,t.user_from_id,t.user_to_id,t.date_create,t.read 
FROM hi.hi_message_user t
WHERE (t.user_from_id=:uid OR t.user_to_id=:uid) and NOT  (CASE WHEN ((CASE WHEN (t.user_from_id=:uid) THEN t.user_to_id ELSE t.user_from_id END)>:uid) THEN  t.trash_user1  ELSE t.trash_user2 END)   
ORDER  BY (case when (t.user_from_id=:uid) then t.user_to_id else t.user_from_id end),t.id DESC) a ORDER BY a.id DESC';
    }

}
