<?php
   
/**
 * This is the model class for table "hi.hi_like".
 *
 * The followings are the available columns in table 'hi.hi_like':
 * @property integer $id
 * @property integer $tp_user_id
 * @property string $date
 * @property integer $owner_id
 * @property boolean $trash
 */
class Like extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Like the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_like';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tp_user_id, owner_id', 'required'),
            array('tp_user_id, owner_id', 'numerical', 'integerOnly' => true),
            array('date, trash', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'tp_user_id', 'on' => "\"user\".active = 't' AND \"user\".trash = 'f'"),
        );
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
            'owner_id' => 'Owner',
            'trash' => 'Trash',
        );
    }

    /**
     * @param null $userId
     * @return mixed
     */
    public static function getTotalCount($userId = null) {
        return $userId ? self::model()->count("trash = 'f' AND tp_user_id = :userId", array(':userId' => $userId)) :
            self::model()->count("trash = 'f'");
    }
    
    public static function getCountByOwnerId($ownerId) {
        return self::model()->count("trash = 'f' AND owner_id = :ownerId", array(':ownerId' => $ownerId));
    }
    
    public static function getListByOwnerId($ownerId) {
        return self::model()->findAll("trash = 'f' AND owner_id = :ownerId", array(':ownerId' => $ownerId));
    }

    public static function getListByOwnerIds($ownerIds) {
        $criteria = new CDbCriteria();
        $criteria->with = array(
            'user' => array(
                'select' => 'nick',
            ),
        );
        $criteria->addInCondition('t.owner_id', $ownerIds);
        $criteria->addCondition("t.trash = 'f'");
        $criteria->order = 'date';
        $likes = self::model()->findAll($criteria);

        $likeList = array();
        foreach ($likes as $like) {
            $likeList[$like->owner_id][] = $like;
        }

        return $likeList;
    }

    /**
     * @param $userId
     * @param $ownerId
     * @return int
     */
    public static function add($userId, $ownerId) {
        if (!self::isAlreadyLiked($userId, $ownerId)) {
            $like = new Like();
            $like->tp_user_id = $userId;
            $like->owner_id = $ownerId;
            if ($like->save()) {
                return true;
            }
        }
        return false;
    }
    
    public static function destroy($id, $userId) {
        return self::model()->updateByPk($id, array('trash' => true), 'tp_user_id = :userId', array(':userId' => $userId));
    }
    
    public static function destroyByOwnerId($ownerId, $userId) {
        return self::model()->updateAll(array('trash' => true), 
                                        'owner_id = :ownerId AND tp_user_id = :userId', 
                                        array(':ownerId' => $ownerId, ':userId' => $userId));
    }
    
    /**
     * @param $userId
     * @param $ownerId
     * @return bool
     */
    public static function isAlreadyLiked($userId, $ownerId) {
        $alreadyLiked = Like::model()->count("tp_user_id = :userId AND owner_id = :ownerId AND trash = 'f'", array(':userId' => $userId, ':ownerId' => $ownerId));
        return (bool) $alreadyLiked;
    }

    public static function getFirstUserByOwnerId($ownerId) {
        $criteria = new CDbCriteria();
        $criteria->select = 'owner_id';
        $criteria->with = array(
            'user' => array(
                'select' => 'id, nick',
            ),
        );
        $criteria->addCondition('t.owner_id = :ownerId');
        $criteria->params[':ownerId'] = $ownerId;
        $criteria->addCondition("t.trash = 'f'");
        $criteria->order = 'date';
        $criteria->limit = 3;
        $likes = self::model()->findAll($criteria);

        $users = array();
        foreach ($likes as $like) {
            $users[] = array(
                'id' => $like->user->id,
                'nick' => $like->user->nick,
                'url' => Yii::app()->createUrl('/user/view', array('id' => $like->user->id)),
            );
        }

        return $users;
    }
}