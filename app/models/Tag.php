<?php

/**
 * This is the model class for table "hi.hi_tag".
 *
 * The followings are the available columns in table 'hi.hi_tag':
 * @property integer $id
 * @property string $name
 */
class Tag extends CActiveRecord {
    const DEFAULT_POST_LIMIT_ON_PAGE = 20;
    const POST_LIMIT_ON_FIRST_LOAD = 10;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_tag';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, name', 'required'),
            array('id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'post' => array(self::BELONGS_TO, 'Post', 'id', 'on' => "post.trash = 'f'"),
            'comment' => array(self::BELONGS_TO, 'Comment', 'id', 'on' => "comment.trash = 'f'"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Tag the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getTotalCount($userId = null) {
        $command = Yii::app()->db->createCommand()
            ->select('COUNT(DISTINCT t.name)')
            ->from('hi.hi_tag t')
            ->leftJoin('hi.hi_post post', 'post.id = t.id')
            ->leftJoin('hi.hi_comment comment', 'comment.id = t.id');
        if ($userId) {
            $command->where('post.tp_user_id = :userId', array(':userId' => $userId))
                ->orWhere('comment.tp_user_id = :userId', array(':userId' => $userId));
        }
        return $command->queryScalar();
    }

    public static function getTotalCountByAllocationId($allocationId) {
        return Yii::app()->db->createCommand()
            ->select('COUNT(DISTINCT t.name)')
            ->from('hi.hi_tag t')
            ->where('t.id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.allocation_id = :allocationId AND p.trash = \'f\' UNION
                SELECT c.id FROM hi.hi_comment c WHERE c.post_id IN (SELECT t.id FROM hi.hi_post t WHERE t.allocation_id = :allocationId AND t.trash = \'f\')
            )', array(':allocationId' => $allocationId))
            ->queryScalar();
    }

    public static function initSearch($params = array()) {
        $defaultParams = array(
            'userId' => null,
            'allocationId' => null,
            'pageSize' => self::POST_LIMIT_ON_FIRST_LOAD,
            'offset' => null,
            'tagName' => null,
        );
        $params = CMap::mergeArray($defaultParams, $params);

        $command = Yii::app()->db->createCommand()
            ->selectDistinct(array(
                't.name',
                '(SELECT COUNT(*)
                    FROM hi.hi_tag tag
                    LEFT JOIN hi.hi_post p ON p.id = tag.id
                    LEFT JOIN hi.hi_comment c ON c.id = tag.id
                    WHERE tag.hash = t.hash) AS total_count',
                '(SELECT COUNT(*) FROM hi.hi_tag tag
                    LEFT JOIN hi.hi_post p ON p.id = tag.id
                    WHERE tag.hash = t.hash AND p.id IS NOT NULL) AS post_count',
                '(SELECT COUNT(*) FROM hi.hi_tag tag
                    LEFT JOIN hi.hi_comment c ON c.id = tag.id
                    WHERE tag.hash = t.hash AND c.id IS NOT NULL) AS comment_count'
            ))
            ->from('hi.hi_tag t')
            ->leftJoin('hi.hi_post post', 'post.id = t.id')
            ->leftJoin('hi.hi_comment comment', 'comment.id = t.id');

        $count = Tag::getTotalCount();
        if (isset($params['userId'])) {
            $command = $command->where('post.tp_user_id = :userId', array(':userId' => $params['userId']))
                ->orWhere('comment.tp_user_id = :userId', array(':userId' => $params['userId']));
            $count = Tag::getTotalCount($params['userId']);
        }
        if (isset($params['allocationId'])) {
            $command = $command->where('t.id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.allocation_id = :allocationId AND p.trash = \'f\' UNION
                SELECT c.id FROM hi.hi_comment c WHERE c.post_id IN (SELECT t.id FROM hi.hi_post t WHERE t.allocation_id = :allocationId AND t.trash = \'f\')
            )', array(':allocationId' => $params['allocationId']));
            $count = Tag::getTotalCountByAllocationId($params['allocationId']);
        }
        if ($params['tagName']) {
            $command->andWhere('t.name LIKE :tagName', array(':tagName' => "%{$params['tagName']}%"));
        }

        $sort = new CSort();
        $sort->defaultOrder = 'total_count DESC, name ASC';
        $sort->attributes = array(
            'tc' => array(
                'asc' => 'total_count ASC, name ASC',
                'desc' => 'total_count DESC, name ASC',
                'label' => 'Используют',
            ),
            'pc' => array(
                'asc' => 'post_count ASC, name ASC',
                'desc' => 'post_count DESC, name ASC',
                'label' => 'В постах',
            ),
            'cc' => array(
                'asc' => 'comment_count ASC, name ASC',
                'desc' => 'comment_count DESC, name ASC',
                'label' => 'В комментариях',
            ),
        );

        return new CSqlDataProvider($command, array(
            'keyField' => 'name',
            'totalItemCount' => $count,
            'pagination' => array(
                'class' => 'Pagination',
                'pageSize' => $params['pageSize'],
                'offset' => $params['offset'],
            ),
            'sort' => $sort,
        ));
    }

    public static function getTags($text) {
        preg_match_all('/#([a-zа-я\d-_]+)/iu', $text, $tags);
        if (isset($tags[1])) {
            return array_unique($tags[1]);
        }
        return array();
    }

    public static function addTagsFromText($ownerId, $text) {
        $tags = self::getTags($text);
        foreach ($tags as $name) {
            $hash = md5($name);
            if (!Tag::model()->count('id = :id AND hash = :hash', array(':id' => $ownerId, ':hash' => $hash))) {
                $tag = new Tag();
                $tag->id = $ownerId;
                $tag->name = $name;
                $tag->hash = $hash;
                if (!$tag->save()) {
                    throw new \Exception('Model "Tag" not saved.');
                }
            }
        }
    }

    public static function replaceTagsFromText($text) {
        return preg_replace('/#([a-zа-я\d-_]+)/iu', '<a class="comment-td-green" href="/tag/$1">$0</a>', $text);
    }

    public static function initSearchByTag($tag, $params = array()) {
        $defaultParams = array(
            'pageSize' => self::POST_LIMIT_ON_FIRST_LOAD,
            'offset' => null,
        );
        $params = CMap::mergeArray($defaultParams, $params);

        $criteria = new CDbCriteria();
        $criteria->select = 't.id';
        $criteria->with = array(
            'tag' => array(
                'select' => false
            ),
            'comment' => array(
                'select' => false,
            ),
            'comment.tag' => array(
                'alias' => 'ctag',
                'select' => false,
            ),
        );
        $criteria->addCondition('tag.hash = :hash OR ctag.hash = :hash');
        $criteria->params[':hash'] = md5($tag);
        $criteria->addCondition("t.trash = 'f'");
        $postList = Post::model()->findAll($criteria);

        $postIds = array();
        foreach ($postList as $post) {
            $postIds[] = $post->id;
        }

        $criteria = new CDbCriteria();
        $criteria->select = array(
            't.id',
            't.tp_user_id',
            't.date',
            't.text',
            '(SELECT COUNT(id) FROM hi.hi_comment comment WHERE comment.post_id=t.id AND comment.trash = \'f\') AS comment_count',
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
        );
        $criteria->addInCondition('t.id', $postIds);
        $criteria->addCondition("t.trash = 'f'");
        $criteria->order = 't.date DESC';

        return new CActiveDataProvider(Post::model(), array(
            'criteria' => $criteria,
            'pagination' => array(
                'class' => 'Pagination',
                'pageSize' => $params['pageSize'],
                'offset' => $params['offset'],
                'pageVar' => 'page'
            ),
        ));
    }
    
    public function getUrl()
    {
        return Yii::app()->createUrl('tag/view',array('tag'=>$this->name));
    }
    
    public function getCountPost()
    {
        return Post::model()->count('t.trash=FALSE and t.id in (select id from '.$this->tableName().' where hash=\''.$this->hash.'\')');
    }
    
    public function getCountComent()
    {
        return Comment::model()->count('t.trash=FALSE and t.id in (select id from '.$this->tableName().' where hash=\''.$this->hash.'\')');
    }
}
