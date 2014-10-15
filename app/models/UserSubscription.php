<?php

/**
 * This is the model class for table "hi.hi_user_subscription".
 *
 * The followings are the available columns in table 'hi.hi_user_subscription':
 * @property integer $subscriber_id
 * @property integer $tp_user_id
 * @property boolean $trash
 */
class UserSubscription extends CActiveRecord {
    public $subscribed;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_user_subscription';
    }

    public function primaryKey() {
        return array('subscriber_id', 'tp_user_id');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('subscriber_id, tp_user_id', 'required'),
            array('subscriber_id, tp_user_id', 'numerical', 'integerOnly' => true),
            array('trash', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user_s' => array(self::BELONGS_TO, 'User', 'subscriber_id', 'on' => "user_s.active = 't' AND user_s.trash = 'f'"),
            'user' => array(self::BELONGS_TO, 'User', 'tp_user_id', 'on' => "\"user\".active = 't' AND \"user\".trash = 'f'"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'subscriber_id' => 'Subscriber',
            'tp_user_id' => 'Tp User',
            'trash' => 'Trash',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserSubscription the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @param $subscriberId
     * @param $userId
     * @return bool
     */
    public static function add($subscriberId, $userId) {
        $subscription = self::model()->find("subscriber_id = :subscriber_id AND tp_user_id = :tp_user_id", array(':subscriber_id' => $subscriberId, ':tp_user_id' => $userId));
        if (!$subscription) {
            $subscription = new UserSubscription();
            $subscription->subscriber_id = $subscriberId;
            $subscription->tp_user_id = $userId;
            if ($subscription->save()) {
                return true;
            }
        } elseif ($subscription->trash) {
            $subscription->trash = false;
            if ($subscription->save()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $subscriberId
     * @param $userId
     * @return bool
     */
    public static function remove($subscriberId, $userId) {
        if (self::isAlreadySubscribed($subscriberId, $userId)) {
            $subscription = self::model()->find("subscriber_id = :subscriber_id AND tp_user_id = :tp_user_id", array(':subscriber_id' => $subscriberId, ':tp_user_id' => $userId));
            $subscription->trash = true;
            if ($subscription->save()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $subscriberId
     * @param $userId
     * @return bool
     */
    public static function isAlreadySubscribed($subscriberId, $userId) {
        if ($subscriberId != $userId) {
            $alreadySubscribed = self::model()->count("subscriber_id = :subscriber_id AND tp_user_id = :tp_user_id AND trash = 'f'", array(':subscriber_id' => $subscriberId, ':tp_user_id' => $userId));
            return (bool) $alreadySubscribed;
        }
        return false;
    }

    /**
     * @param $userId
     * @return array|mixed|null|static
     */
    public static function getReaders($userId) {
        $criteria = new CDbCriteria();
        $criteria->select = array(
            "(SELECT COUNT(*) FROM hi.hi_user_subscription us WHERE us.subscriber_id = :userId AND us.tp_user_id = t.subscriber_id AND us.trash = 'f') AS subscribed",
        );
        $criteria->with = array(
            'user_s' => array(
                'select' => 'name, surname, nick',
            ),
        );
        $criteria->addCondition('t.tp_user_id = :userId');
        $criteria->params[':userId'] = $userId;
        $criteria->addCondition("t.trash = 'f'");
        $readers = self::model()->findAll($criteria);
        return $readers;
    }

    /**
     * @param $userId
     * @return array|mixed|null|static
     */
    public static function getList($userId) {
        $criteria = new CDbCriteria();
        $criteria->with = array(
            'user' => array(
                'select' => 'name, surname, nick',
            ),
        );
        $criteria->addCondition('t.subscriber_id = :userId');
        $criteria->params[':userId'] = $userId;
        $criteria->addCondition("t.trash = 'f'");
        $list = self::model()->findAll($criteria);
        return $list;
    }

    /**
     * @param $userId
     * @return CDbDataReader|mixed|string
     */
    public static function getReadersCount($userId) {
        return self::model()->count("tp_user_id = :userId AND trash = 'f'", array(':userId' => $userId));
    }

    /**
     * @param $userId
     * @return CDbDataReader|mixed|string
     */
    public static function getSubscriptionsCount($userId) {
        return self::model()->count("subscriber_id = :userId AND trash = 'f'", array(':userId' => $userId));
    }
}
