<?php

/**
 * This is the model class for table "hi.hi_message_attachment".
 *
 * The followings are the available columns in table 'hi.hi_message_attachment':
 * @property integer $id
 * @property integer $entity_id
 * @property integer $entity_type
 * @property integer $type
 * @property string $body
 * @property boolean $trash
 */
class MessageAttachment extends CActiveRecord {

    const SLAT = 'fcdw98){id}wmes5';

    /**
     * папка в  runtime для кеширование body
     */
    const RUNTIME = 'attachMessage';
    const ENTITY_MESSAGE_USER = 0;
    const ENTITY_MESSAGE_CHAT = 1;
    const TYPE_MAP = 50;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MessageAttachment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    protected function beforeFind() {
        $this->getDbCriteria()->select='t.id,t.entity_id,t.entity_type,t.type,t.trash'; //все кроме body
        parent::beforeFind();
    }

    /**
     * расшифровка типов Mime файлов для поля type 
     * @return type
     */
    public static function typesFile() {
        return array(
            0 => 'image/jpeg',
            1 => 'image/gif',
            2 => 'image/png',
            3 => 'image/pjpeg'
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_message_attachment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type', 'required'),
            array('entity_id, entity_type, type', 'numerical', 'integerOnly' => true),
            array('trash', 'boolean'),
            array('entity_id, entity_type, type, body, trash', 'default', 'setOnEmpty' => true),
            array('body, trash', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, entity_id, entity_type, type, body, trash', 'safe', 'on' => 'search'),
        );
    }

    protected function beforeValidate() {
        if ($this->type == self::TYPE_MAP) {
            if (!preg_match('/^\\d{1,3}(?:\\.\\d{1,20})?\\,\\d{1,3}(?:\\.\\d{1,20})?$/', $this->body)) {
                $this->addError('body', 'кординаты указаны не верно');
            }
        } elseif (!array_key_exists($this->type, self::typesFile())) {
            $this->addError('type', 'type not valid');
        }

        return parent::beforeValidate();
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
            'entity_id' => 'Entity',
            'entity_type' => 'Entity Type',
            'type' => 'Type',
            'body' => 'Body',
            'trash' => 'Trash',
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
        $criteria->compare('entity_id', $this->entity_id);
        $criteria->compare('entity_type', $this->entity_type);
        $criteria->compare('type', $this->type);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('trash', $this->trash);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getHashById($id) {
        return md5(str_replace('{id}', $id, self::SLAT));
    }

    /**
     * Проверка хеша для скачивания
     * @param type $hash
     * @return type
     */
    public function validHash($hash) {
        return (self::getHashById($this->id) === $hash);
    }

    protected function getNameFileThumb() {
        return Yii::app()->getRuntimePath() . '/' . self::RUNTIME . '/' . $this->id . '_thumb.jpg';
    }

    public function getFileBody() {
        $file = Yii::app()->getRuntimePath() . '/' . self::RUNTIME . '/' . $this->id;
        if (!file_exists($file)) {
            if (!is_dir(Yii::app()->getRuntimePath() . '/' . self::RUNTIME))
            {
                mkdir(Yii::app()->getRuntimePath() . '/' . self::RUNTIME);
            }
            
            $body = !$this->isNewRecord && $this->body  ? $this->body : $this->getDbConnection()->createCommand()->select('body')->from($this->tableName())->where('id=' . $this->id)->queryScalar();
            file_put_contents($file, $body);
        }
        return $file;
    }

    protected function createThumb() {
        $fileBody = $this->getFileBody();
        require Yii::getPathOfAlias('ext') . '/imagine.phar';
        $imagine = new Imagine\Gd\Imagine();
        $transformation = new Imagine\Filter\Transformation();

        $transformation
                ->thumbnail(new Imagine\Image\Box(129, 129), Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND)
                ->save($this->getNameFileThumb());
        $transformation->apply($imagine->open($fileBody));
    }

    protected function afterSave() {
        if ($this->type != self::TYPE_MAP) {
            $this->createThumb();
        }
        parent::afterSave();
    }

    public function getFileThumb() {
        $file = $this->getNameFileThumb();
        if (!file_exists($file)) {
            $this->createThumb();
        }
        return $file;
    }
    
    
    public function getUrlThumb()
    {
        return Yii::app()->createUrl('message/getAttach',array('id'=>  $this->id,'h'=>self::getHashById($this->id),'thumb'=>'1'));
    }
    
     public function getUrlFile()
    {
        return Yii::app()->createUrl('message/getAttach',array('id'=>  $this->id,'h'=>self::getHashById($this->id)));
    }
    
    
    

}
