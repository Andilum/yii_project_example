<?php

/**
 * This is the model class for table "hi.hi_comment".
 *
 * The followings are the available columns in table 'hi.hi_comment':
 * @property integer $id
 * @property integer $tp_user_id
 * @property string $date
 * @property string $text
 * @property integer $post_id
 * @property string $lang
 * @property boolean $trash
 */
class Comment extends BaseMessage {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tp_user_id, text, post_id', 'required'),
            array('tp_user_id, post_id', 'numerical', 'integerOnly' => true),
            array('date, trash', 'safe'),
        );
    }

    public function beforeValidate() {
        $this->date = date('Y-m-d H:i:s');
        $this->text = str_replace(array('<br>', '<br/>', '<br />'), "\n", $this->text);
        $this->text = trim(strip_tags($this->text));
        return parent::beforeValidate();
    }
    
    public function beforeSave() {
        $this->lang = Yii::app()->language;
        
        return parent::beforeSave();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'photo' => array(self::HAS_MANY, 'Photo', 'owner_id', 'on' => "photo.trash = 'f'" ),
            'user' => array(self::BELONGS_TO, 'User', 'tp_user_id', 'on' => "\"user\".active = 't' AND \"user\".trash = 'f'"),
            'tag' => array(self::HAS_MANY, 'Tag', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'tp_user_id' => 'Tp User',
            'date' => 'Date',
            'text' => 'Text',
            'post_id' => 'Post',
            'trash' => 'Trash',
        );
    }

    /**
     * @return mixed|string
     */
    public function getReplacedText() {
        $text = $this->text;
        $text = Tag::replaceTagsFromText($text);
        $text = SmileHelper::replaceSmilesFromText($text);
        $text = str_replace("\n", '<br/>', $text);
        return $text;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Comment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getCountByPostId($postId) {
        return self::model()->count("trash = 'f' AND post_id = :postId", array(':postId' => $postId));
    }
    
    public static function getListByPostId($postId, $limit = null, $offsetId = null, $order = null) {
        $criteria = new CDbCriteria();
        if ($order) {
            $criteria->order = $order;
        }
        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offsetId) {
            $criteria->addCondition("id < :offsetId");
            $criteria->params[':offsetId'] = $offsetId;
        }
        $criteria->addCondition("trash = 'f' AND post_id = :postId");
        $criteria->params[':postId'] = $postId;
        return self::model()->findAll($criteria);
    }
    
    /**
     * @param $postId
     * @param null $afterCommentId
     * @return array|mixed|null|static
     */
    public static function getListByPostIdInArray($postId, $afterCommentId = null) {
        $criteria = new CDbCriteria();
        $criteria->select = 't.id, t.text, t.date, t.post_id, t.tp_user_id';
        $criteria->with = array(
            'photo' => array(
                'select' => 'id, name, tp_user_id, date, text, ext, size, owner_id',
            ),
            'user' => array(
                'select' => 'nick',
            ),
        );
        $criteria->addCondition("t.trash = 'f' AND t.post_id = :post_id");
        if ($afterCommentId) {
            $criteria->addCondition('t.id > :afterCommentId');
            $criteria->params[':afterCommentId'] = $afterCommentId;
        }
        $criteria->params[':post_id'] = $postId;
        $criteria->order = 't.date ASC';
        $comments = Comment::model()->findAll($criteria);

        $commentList = array();
        foreach ($comments as $key => $comment) {
            $commentList[$key] = $comment->getAttributes();
            $commentList[$key]['text'] = $comment->getReplacedText();
            $commentList[$key]['nick'] = $comment->user->nick;
            $commentList[$key]['userId'] = $comment->user->id;
            $commentList[$key]['photo'] = array();
            if ($comment->photo) {
                foreach ($comment->photo as $photo) {
                    $commentList[$key]['photo'][$photo->id]['url'] = $photo->getFileUrl(Photo::IMAGE_SIZE_129);
                    $commentList[$key]['photo'][$photo->id]['text'] = $photo->text;
                }
            }
        }

        return $commentList;
    }
    
    public static function getListByPostIds($postIds, $params = array()) {
        $defaultParams = array(
            'tag' => null,
        );
        $params = CMap::mergeArray($defaultParams, $params);

        $criteria = new CDbCriteria();
        $criteria->select = 't.id, t.text, t.date, t.post_id, t.tp_user_id';
        $criteria->with = array(
            'photo' => array(
                'select' => 'id, name, tp_user_id, date, text, ext, size, owner_id',
            ),
            'user' => array(
                'select' => 'nick',
            ),
        );
        $criteria->addCondition("t.trash = 'f'");
        $criteria->addInCondition('post_id', $postIds);
        if ($params['tag']) {
            $criteria->with['tag'] = array('select' => false);
            $criteria->addCondition('tag.hash = :hash');
            $criteria->params[':hash'] = md5($params['tag']);
            $criteria->together = true;
        }
        $criteria->order = 't.date ASC';
        $comments = Comment::model()->findAll($criteria);

        $commentList = array();
        foreach ($comments as $comment) {
            $commentList[$comment->post_id][] = $comment;
        }

        return $commentList;
    }
    
     /**
     * @param array $postIds
     * @return array
     */
    public static function getLastByPostId($postIds) {
        $criteria = new CDbCriteria();
        $criteria->select = 't.id, t.text, t.date, t.post_id, t.tp_user_id';
        $criteria->with = array(
            'photo' => array(
                'select' => 'id, name, tp_user_id, date, text, ext, size, owner_id',
            ),
            'user' => array(
                'select' => 'nick',
            ),
        );
        $criteria->addCondition("t.trash = 'f'");
        $criteria->addInCondition('post_id', $postIds);
        $criteria->addCondition("t.id IN (SELECT id FROM hi.hi_comment WHERE post_id = t.post_id ORDER BY date DESC LIMIT 3)");
        $criteria->order = 't.date ASC';
        $comments = Comment::model()->findAll($criteria);

        $commentList = array();
        foreach ($comments as $comment) {
            $commentList[$comment->post_id][] = $comment;
        }

        return $commentList;
    }
    
    /**
     * @param $userId
     * @param $commentData
     * @param $photosData
     * @return bool
     */
    public static function add($userId, $commentData, $photosData = array()) {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $comment = new Comment();
            $comment->attributes = $commentData;
            $comment->tp_user_id = $userId;

            if ($comment->save()) {
                Tag::addTagsFromText($comment->id, $comment->text);
                if (!empty($photosData)) {
                    foreach ($photosData as $photoData) {
                        $photo = new Photo();
                        $photo->attributes = $photoData;
                        $photo->tp_user_id = $userId;
                        $photo->owner_id = $comment->id;
                        if (!$photo->save()) {
                            throw new \Exception('Model "Photo" not saved.');
                        }
                    }
                }
            } else {
                throw new \Exception('Model "Comment" not saved.');
            }

            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
        return true;
    }

    /**
     * @param $userId
     * @param $id
     * @param $commentData
     * @param array $photosData
     * @return bool
     */
    public static function updateById($userId, $id, $commentData, $photosData = array()) {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition("t.trash = 'f' AND t.tp_user_id = :userId AND t.id = :id");
            $criteria->params[':userId'] = $userId;
            $criteria->params[':id'] = $id;
            $comment = Comment::model()->find($criteria);
            if (!$comment) {
                throw new \Exception('Comment not found');
            }
            $comment->attributes = $commentData;

            if ($comment->save()) {
                Tag::addTagsFromText($comment->id, $comment->text);
                if (!empty($photosData)) {
                    foreach ($photosData as $photoData) {
                        $photo = new Photo();
                        $photo->attributes = $photoData;
                        $photo->tp_user_id = $userId;
                        $photo->owner_id = $comment->id;
                        if (!$photo->save()) {
                            throw new \Exception('Model "Photo" not saved.');
                        }
                    }
                }
            } else {
                throw new \Exception('Model "Comment" not saved.');
            }

            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
        return true;
    }
    
}