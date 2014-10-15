<?php

/**
 * This is the model class for table "hi.hi_allocation_subscription".
 *
 * The followings are the available columns in table 'hi.hi_allocation_subscription':
 * @property integer $subscriber_id
 * @property integer $allocation_id
 * @property boolean $trash
 */
class AllocationSubscription extends CActiveRecord {
    /**
     * @var DictPhoto
     */
    public $photo;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_allocation_subscription';
    }

    public function primaryKey() {
        return array('subscriber_id', 'allocation_id');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('subscriber_id, allocation_id', 'required'),
            array('subscriber_id, allocation_id', 'numerical', 'integerOnly' => true),
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
            'allocation' => array(self::BELONGS_TO, 'DictAllocation', 'allocation_id', 'on' => "allocation.active = 't' AND allocation.trash = 'f'"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'subscriber_id' => 'Subscriber',
            'allocation_id' => 'Allocation',
            'trash' => 'Trash',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AllocationSubscription the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @param $subscriberId
     * @param $allocationId
     * @return bool
     */
    public static function isAlreadySubscribed($subscriberId, $allocationId) {
        $alreadySubscribed = self::model()->count("subscriber_id = :subscriber_id AND allocation_id = :allocation_id AND trash = 'f'", array(':subscriber_id' => $subscriberId, ':allocation_id' => $allocationId));
        return (bool) $alreadySubscribed;
    }

    /**
     * @param $subscriberId
     * @param $allocationId
     * @return bool
     */
    public static function add($subscriberId, $allocationId) {
        $subscription = self::model()->find("subscriber_id = :subscriber_id AND allocation_id = :allocation_id", array(':subscriber_id' => $subscriberId, ':allocation_id' => $allocationId));
        if (!$subscription) {
            $subscription = new AllocationSubscription();
            $subscription->subscriber_id = $subscriberId;
            $subscription->allocation_id = $allocationId;
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
     * @param $allocationId
     * @return bool
     */
    public static function remove($subscriberId, $allocationId) {
        if (self::isAlreadySubscribed($subscriberId, $allocationId)) {
            $subscription = self::model()->find("subscriber_id = :subscriber_id AND allocation_id = :allocation_id", array(':subscriber_id' => $subscriberId, ':allocation_id' => $allocationId));
            $subscription->trash = true;
            if ($subscription->save()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $subscriberId
     * @param bool $withPhoto
     * @return array|mixed|null|static
     */
    public static function getList($subscriberId, $withPhoto = false) {
        $criteria = new CDbCriteria();
        $criteria->with = array(
            'allocation' => array(
                'select' => 'id, name',
            ),
            'allocation.alloccat' => array(
                'select' => 'name',
            ),
        );
        $criteria->addCondition('t.subscriber_id = :subscriberId');
        $criteria->params[':subscriberId'] = $subscriberId;
        $criteria->addCondition("t.trash = 'f'");
        $list = self::model()->findAll($criteria);

        if ($withPhoto) {
            $eids = array();
            foreach ($list as $item) {
                $eids[] = $item->allocation->id;
            }
            $photos = DictPhoto::getListByEids($eids);

            foreach ($list as $key => $item) {
                if ($photos[$item->allocation_id]) {
                    $list[$key]->photo = $photos[$item->allocation_id];
                } else {
                    $list[$key]->photo = DictPhoto::HOTEL_PHOTO_NONE_URL;
                }
            }
        }

        return $list;
    }

    /**
     * @param $userId
     * @return CDbDataReader|mixed|string
     */
    public static function getSubscriptionsCount($userId) {
        return self::model()->count("subscriber_id = :userId AND trash = 'f'", array(':userId' => $userId));
    }

    /**
     * @param $allocationId
     * @return CDbDataReader|mixed|string
     */
    public static function getSubscribersCount($allocationId) {
        return self::model()->count("allocation_id = :allocationId AND trash = 'f'", array(':allocationId' => $allocationId));
    }
}
