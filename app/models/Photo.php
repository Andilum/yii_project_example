<?php
   
/**
 * This is the model class for table "hi.hi_photo".
 *
 * The followings are the available columns in table 'hi.hi_photo':
 * @property integer $id
 * @property string $name
 * @property integer $tp_user_id
 * @property string $date
 * @property string $text
 * @property string $ext
 * @property string $size
 * @property string $body
 * @property integer $owner_id
 * @property integer $width
 * @property integer $height
 * @property boolean $trash
 */
class Photo extends CActiveRecord {
    const IMAGE_SIZE_67 = 67,
        IMAGE_SIZE_129 = 129,
        IMAGE_SIZE_172 = 172,
        IMAGE_SIZE_259 = 259,
        IMAGE_SIZE_347 = 347,
        IMAGE_SIZE_522 = 522;

    const IMAGE_PATH = '/icache/photo/';
    const IMAGE_TILE_GROUP_MAX_CONT = 6;
    const DEFAULT_PHOTO_LIMIT_ON_PAGE = 20;

    /**
     * @var array
     */
    public static $tile = array(
        1 => array(
            array('class' => 'image-grid-s', 'size' => self::IMAGE_SIZE_522),
        ),
        2 => array(
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
        ),
        3 => array(
            array('class' => 'image-grid-1', 'size' => self::IMAGE_SIZE_347),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
        ),
        4 => array(
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
        ),
        5 => array(
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
            array('class' => 'image-grid-2', 'size' => self::IMAGE_SIZE_259),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
        ),
        6 => array(
            array('class' => 'image-grid-1', 'size' => self::IMAGE_SIZE_347),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
            array('class' => 'image-grid-3', 'size' => self::IMAGE_SIZE_172),
        )
    );

    /**
     * @var CUploadedFile
     */
    public $file;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Photo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_photo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tp_user_id, ext, size, body, width, height, owner_id', 'required'),
            array('tp_user_id, owner_id, width, height', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('ext', 'length', 'max' => 4),
            //array('file', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
            array('date, text, trash, file', 'safe'),
        );
    }

    public function beforeValidate() {
        $basePath = Yii::app()->getBasePath() . '/runtime/';

        if (!is_null($this->file)) {
            $file = $this->file;
            $this->name = $file->getName();
            $this->ext = $file->getExtensionName();
            $this->body = file_get_contents($file->getTempName());
        }

        if (!is_null($this->body)) {
            if (is_null($this->file)) {
                $this->body = base64_decode($this->body);
            }
            $path = $basePath . 'photo';
            $dataFile = fopen($path, "wb");
            fwrite($dataFile, $this->body);
            $this->body = $path;
            $this->size = filesize($path);
            $sizeInfo = getimagesize($path);
            $this->width = $sizeInfo[0];
            $this->height = $sizeInfo[1];
        }

        return parent::beforeValidate();
    }

    public function beforeSave() {
        if (!is_null($this->body)) {
            $this->body = new CDbExpression(":body", array(":body" => fopen($this->body, "rb")));
        }
        return parent::beforeSave();
    }

    public function afterSave() {
        if ( !is_null($this->body) ) {
            fclose($this->body->params[":body"]);
            unlink(Yii::app()->getBasePath() . '/runtime/photo');
        }
        return parent::afterSave();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'tp_user_id' => 'Tp User',
            'date' => 'Date',
            'text' => 'Text',
            'ext' => 'Ext',
            'size' => 'Size',
            'body' => 'Body',
            'owner_id' => 'Owner',
            'width'  => 'Width', 
            'height' => 'Height',
            'trash' => 'Trash',
        );
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFileName($width = 0, $height = 0) {
        $name = $this->id;
        if ($width && $height) {
            $name .= '_' . $width . 'x' . $height;
        } elseif ($width) {
            $name .= '_' . $width . 'x' . $width;
        }
        return $name . '.' . $this->ext;
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFileUrl($width = 0, $height = 0) {
        return self::IMAGE_PATH . $this->getFileName($width, $height);
    }

    /**
     * @return string
     */
    public function getFilePath() {
        return Yii::getPathOfAlias('webroot') . self::IMAGE_PATH . $this->getFileName();
    }

    /**
     * @param null $userId
     * @return mixed
     */
    public static function getTotalCount($userId = null) {
        return $userId ? self::model()->count("trash = 'f' AND tp_user_id = :userId AND t.owner_id IN (
            SELECT p.id FROM hi.hi_post p WHERE p.trash = 'f' UNION
            SELECT c.id FROM hi.hi_comment c LEFT JOIN hi.hi_post p2 ON p2.id = c.post_id WHERE c.trash = 'f' AND p2.trash = 'f'
        )", array(':userId' => $userId)) : self::model()->count("trash = 'f'");
    }

    /**
     * @param null $allocationId
     * @return mixed
     */
    public static function getTotalCountByAllocationId($allocationId) {
        return Yii::app()->db->createCommand()
            ->select('COUNT(t.id)')
            ->from('hi.hi_photo t')
            ->where('t.owner_id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.allocation_id = :allocationId AND p.trash = \'f\' UNION
                SELECT c.id FROM hi.hi_comment c WHERE c.post_id IN (SELECT t.id FROM hi.hi_post t WHERE t.allocation_id = :allocationId AND t.trash = \'f\')
            )', array(':allocationId' => $allocationId))
            ->andWhere("t.trash = 'f'")
            ->queryScalar();
    }

    public static function getList($ids) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, name, tp_user_id, date, text, ext, size, owner_id';
        $criteria->addInCondition('owner_id', $ids);
        $criteria->addCondition("trash = 'f'");
        $criteria->order = 'date DESC';
        $photos = self::model()->findAll($criteria);

        $photoList = array();
        foreach ($photos as $photo) {
            $photoList[$photo->owner_id][] = $photo;
        }

        return $photoList;
    }

    public static function initSearch($params = array()) {
        $defaultParams = array(
            'userId' => null,
            'allocationId' => null,
            'pageSize' => self::DEFAULT_PHOTO_LIMIT_ON_PAGE,
            'offset' => null,
            'tagName' => null,
            'postOnly' => false,
        );
        $params = CMap::mergeArray($defaultParams, $params);

        $criteria = new CDbCriteria();
        $criteria->select = 't.id, t.ext';
        $criteria->addCondition("t.trash = 'f' AND t.owner_id > 0");
        if ($params['postOnly']) {
            $criteria->addCondition("t.owner_id IN (SELECT p.id FROM hi.hi_post p WHERE p.trash = 'f')");
        } else {
            $criteria->addCondition("t.owner_id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.trash = 'f' UNION
                SELECT c.id FROM hi.hi_comment c LEFT JOIN hi.hi_post p2 ON p2.id = c.post_id WHERE c.trash = 'f' AND p2.trash = 'f'
            )");
        }
        if ($params['userId']) {
            $criteria->addCondition("t.tp_user_Id = :userId");
            $criteria->params[':userId'] = $params['userId'];
        }
        if ($params['allocationId']) {
            $criteria->addCondition("t.owner_id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.allocation_id = :allocationId AND p.trash = 'f' UNION
                SELECT c.id FROM hi.hi_comment c WHERE c.post_id IN (SELECT post.id FROM hi.hi_post post WHERE post.allocation_id = :allocationId AND post.trash = 'f'))");
            $criteria->params[':allocationId'] = $params['allocationId'];
        }
        if ($params['tagName']) {
            $criteria->join = 'LEFT JOIN (SELECT p.id FROM hi.hi_post p WHERE p.trash = \'f\' UNION SELECT c.id FROM hi.hi_comment c WHERE c.trash = \'f\') owner ON owner.id = t.owner_id
                LEFT JOIN hi.hi_tag tag ON tag.id = owner.id';
            $criteria->addCondition("tag.hash = :hash");
            $criteria->params[':hash'] = md5($params['tagName']);
        }
        $criteria->order = 't.date DESC';

        return new CActiveDataProvider(self::model(), array(
            'criteria' => $criteria,
            'pagination' => array(
                'class' => 'Pagination',
                'pageSize' => $params['pageSize'],
                'offset' => $params['offset'],
                'pageVar' => 'page'
            ),
        ));
    }
    
    
    
    public static function destroyById($id, $userId) {
        return self::model()->updateByPk($id, array('trash' => true), 'tp_user_id = :userId', array(':userId' => $userId));
    }

    /**
     * @param $photoId
     * @return int
     */
    public static function getPostId($photoId) {
        $photo = self::model()->findByPk($photoId, array('select' => 'owner_id'));
        if (Post::model()->count('id = :id', array('id' => $photo['owner_id']))) {
            return $photo['owner_id'];
        } else {
            $comment = Comment::model()->findByPk($photo['owner_id'], array('select' => 'post_id'));
            if ($comment) {
                return $comment->post_id;
            }
        }
        return 0;
    }
}