<?php

/**
 * This is the model class for table "hi.hi_post".
 *
 * The followings are the available columns in table 'hi.hi_post':
 * @property integer $id
 * @property string $name
 * @property integer $tp_user_id
 * @property string $date
 * @property string $text
 * @property integer $allocation_id
 * @property string $lang
 * @property boolean trash
 */
class Post extends BaseMessage {

    const DEFAULT_POST_LIMIT_ON_PAGE = 10;
    const POST_LIMIT_ON_FIRST_LOAD = 5;

    public $like_count;
    public $comment_count;
    public $max_rating;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Post the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_post';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('text', 'required'),
            array('name, tp_user_id, text, allocation_id', 'safe'),
            array('id,name,tp_user_id,text,date,allocation_id,trash,lang', 'safe', 'on' => 'search'),
            array('name,tp_user_id,text,allocation_id,trash,lang', 'safe', 'on' => 'admin'),
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
            'photo' => array(self::HAS_MANY, 'Photo', 'owner_id', 'on' => "photo.trash = 'f'"),
            'comment' => array(self::HAS_MANY, 'Comment', 'post_id', 'on' => "comment.trash = 'f'"),
            'like' => array(self::HAS_MANY, 'Like', 'owner_id', 'on' => "\"like\".trash = 'f'"),
            'user' => array(self::BELONGS_TO, 'User', 'tp_user_id', 'on' => "\"user\".active = 't' AND \"user\".trash = 'f'"),
            'allocation' => array(self::BELONGS_TO, 'DictAllocation', 'allocation_id', 'on' => "allocation.active = 't' AND allocation.trash = 'f'"),
            'tag' => array(self::HAS_MANY, 'Tag', 'id'),
            'user_rating' => array(self::HAS_MANY, 'UserRating', 'post_id', 'on' => "user_rating.trash = 'f'"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'name' => 'Заголовок',
            'text' => 'Текст',
            'tp_user_id' => 'Пользователь',
            'allocation_id' => 'Отель',
            'date' => 'Дата',
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

    public static function initSearch($userId = null, $allocationId = null, $params = array()) {
        $defaultParams = array(
            'pageSize' => self::POST_LIMIT_ON_FIRST_LOAD,
            'offset' => null,
            'ratedPosts' => false,
        );
        $params = CMap::mergeArray($defaultParams, $params);

        $criteria = new CDbCriteria();
        $criteria->select = array(
            't.id',
            't.name',
            't.tp_user_id',
            't.date',
            't.text',
            '(SELECT COUNT(id) FROM hi.hi_comment comment WHERE comment.post_id=t.id AND comment.trash = \'f\') AS comment_count',
            '(SELECT COUNT(id) FROM hi.hi_like "like" WHERE "like".owner_id=t.id AND "like".trash = \'f\') AS like_count',
            '(SELECT MAX(rate) FROM hi.hi_rating rating WHERE rating.trash = \'f\') AS max_rating',
        );
        $criteria->with = array(
            'photo' => array(
                'select' => 'id, name, tp_user_id, date, text, ext, size, owner_id',
            ),
            'user' => array(
                'select' => 'nick',
            ),
            'allocation' => array(
                'select' => 'name',
            ),
            'allocation.alloccat' => array(
                'select' => 'name',
            ),
            'user_rating' => array(
                'select' => 'id',
            ),
            'user_rating.service' => array(
                'select' => 'name',
            ),
            'user_rating.rating' => array(
                'select' => 'rate, label',
            ),
        );
        if ($userId) {
            $criteria->addCondition("t.tp_user_id = :userId");
            $criteria->params[':userId'] = $userId;
        }
        if ($allocationId) {
            $criteria->addCondition("t.allocation_id = :allocationId");
            $criteria->params[':allocationId'] = $allocationId;
        }
        if ($params['ratedPosts']) {
            $criteriaRating = new CDbCriteria();
            $criteriaRating->select = 't.post_id';
            $criteriaRating->with = array(
                'post' => array(
                    'select' => false,
                ),
            );
            $criteriaRating->addCondition('post.allocation_id = :allocationId');
            $criteriaRating->params[':allocationId'] = $allocationId;
            $criteriaRating->addCondition("t.trash = 'f'");
            $postList = UserRating::model()->findAll($criteriaRating);

            $postIds = array();
            foreach ($postList as $post) {
                $postIds[] = $post->post_id;
            }

            $criteria->addInCondition('t.id', $postIds);
        }
        if (Yii::app()->userLanguages->getUserLanguage() == UserLanguages::USER_LANGUAGE) {
            $criteria->addCondition("t.lang = :language");
            $criteria->params[':language'] = Yii::app()->translate->getActiveLang()->code;
        }
        $criteria->addCondition("t.trash = 'f'");

        $sort = new CSort();
        $sort->defaultOrder = 't.date DESC';
        $sort->attributes = array(
            'date' => array(
                'desc' => 't.date DESC',
            ),
            'like' => array(
                'desc' => 'like_count DESC, t.date DESC',
            ),
        );

        return new CActiveDataProvider(Post::model(), array(
            'criteria' => $criteria,
            'pagination' => array(
                'class' => 'Pagination',
                'pageSize' => $params['pageSize'],
                'offset' => $params['offset'],
                'pageVar' => 'page'
            ),
            'sort' => $sort,
        ));
    }

    public static function initSearchByUser($userId = null, $params = array()) {
        return self::initSearch($userId, null, $params);
    }

    public static function initSearchByAllocation($allocationId = null, $params = array()) {
        return self::initSearch(null, $allocationId, $params);
    }

    /**
     * @param null $userId
     * @return mixed
     */
    public static function getTotalCount($userId = null) {
        return $userId ? self::model()->count("trash = 'f' AND tp_user_id = :userId", array(':userId' => $userId)) :
                self::model()->count("trash = 'f'");
    }

    public static function getTotalAllocationCount($allocationId) {
        return self::model()->count("trash = 'f' AND allocation_id = :allocationId", array(':allocationId' => $allocationId));
    }

    public static function get($id) {
        return self::model()->findByPk($id);
    }

    public static function getList($offset = null, $limit = self::DEFAULT_POST_LIMIT_ON_PAGE, $offsetId = null, $order = null, $userId = null, $allocationId = null) {
        $criteria = new CDbCriteria();
        !$order ? $criteria->order = 'date desc' : $criteria->order = $order;
        if ($limit) {
            $criteria->limit = $limit;
        }
        if ($offset) {
            $criteria->offset = $offset;
        }
        if ($offsetId) {
            $criteria->addCondition("id < :offsetId");
            $criteria->params[':offsetId'] = $offsetId;
        }
        if ($userId) {
            $criteria->addCondition("tp_user_id = :userId");
            $criteria->params[':userId'] = $userId;
        }
        if ($allocationId) {
            $criteria->addCondition("allocation_id = :allocationId");
            $criteria->params[':allocationId'] = $allocationId;
        }
        $criteria->addCondition("trash = 'f'");
        return self::model()->findAll($criteria);
    }

    public static function getListByUserId($userId, $offset = null, $limit = self::DEFAULT_POST_LIMIT_ON_PAGE, $offsetId = null, $order = null) {
        return self::getList($offset, $limit, $offsetId, $order, $userId);
    }

    public static function getListByAllocationId($allocationId, $offset = null, $limit = self::DEFAULT_POST_LIMIT_ON_PAGE, $offsetId = null, $order = null) {
        return self::getList($offset, $limit, $offsetId, $order, null, $allocationId);
    }

    public static function add($userId, $postData, $photosData = array(), $ratingsData = array(), $allocationId = null) {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $post = new Post();
            $post->attributes = $postData;
            $post->tp_user_id = $userId;
            if ($allocationId) {
                $post->allocation_id = $allocationId;
            }
            if ($post->save()) {
                Tag::addTagsFromText($post->id, $post->text);
                if (!empty($photosData)) {
                    foreach ($photosData as $photoData) {
                        $photo = new Photo();
                        $photo->attributes = $photoData;
                        $photo->tp_user_id = $userId;
                        $photo->owner_id = $post->id;
                        if (!$photo->save()) {
                            $transaction->rollback();
                        }
                    }
                }
                foreach ($ratingsData as $ratingData) {
                    $rating = new UserRating();
                    $rating->attributes = $ratingData;
                    $rating->post_id = $post->id;
                    $rating->tp_user_id = $userId;
                    if (!$rating->save()) {
                        $transaction->rollback();
                    }
                }
            }

            $transaction->commit();
            return 1;
        } catch (\Exception $e) {
            $transaction->rollback();
            return 0;
        }
    }

    public static function updateById($userId, $id, $postData = array(), $photosData = array(), $allocationId = null) {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition('tp_user_id = :userId AND id = :id');
            $criteria->params[':userId'] = $userId;
            $criteria->params[':id'] = $id;
            if ($allocationId) {
                $criteria->addCondition('allocation_id = :allocationId');
                $criteria->params[':allocationId'] = $allocationId;
            }
            $post = Post::model()->find($criteria);
            $post->attributes = $postData;
            if ($post->save()) {
                Tag::addTagsFromText($post->id, $post->text);
                if (!empty($photosData)) {
                    foreach ($photosData as $photoData) {
                        $photo = new Photo();
                        $photo->attributes = $photoData;
                        $photo->tp_user_id = $userId;
                        $photo->owner_id = $post->id;
                        if (!$photo->save()) {
                            $transaction->rollback();
                        }
                    }
                }
            }

            $transaction->commit();
            return 1;
        } catch (\Exception $e) {
            $transaction->rollback();
            return 0;
        }
    }

 

    

 

   

    /**
     * @param $id
     * @return static
     */
    public static function getFullInfoById($id) {
        $criteria = new CDbCriteria();
        $criteria->select = array(
            't.id',
            't.name',
            't.tp_user_id',
            't.date',
            't.text',
            '(SELECT COUNT(id) FROM hi.hi_comment comment WHERE comment.post_id=t.id AND comment.trash = \'f\') AS comment_count',
            '(SELECT COUNT(id) FROM hi.hi_like "like" WHERE "like".owner_id=t.id AND "like".trash = \'f\') AS like_count',
            '(SELECT MAX(rate) FROM hi.hi_rating rating WHERE rating.trash = \'f\') AS max_rating',
        );
        $criteria->with = array(
            'photo' => array(
                'select' => 'id, name, tp_user_id, date, text, ext, size, owner_id',
            ),
            'user' => array(
                'select' => 'nick',
            ),
            'allocation' => array(
                'select' => 'name',
            ),
            'allocation.alloccat' => array(
                'select' => 'name',
            ),
            'user_rating' => array(
                'select' => 'id',
            ),
            'user_rating.service' => array(
                'select' => 'name',
            ),
            'user_rating.rating' => array(
                'select' => 'rate, label',
            ),
        );
        $criteria->addCondition("t.trash = 'f'");
        return self::model()->findByPk($id, $criteria);
    }

}
