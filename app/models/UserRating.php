<?php

/**
 * This is the model class for table "hi.hi_user_rating".
 *
 * The followings are the available columns in table 'hi.hi_user_rating':
 * @property integer $id
 * @property integer $post_id
 * @property integer $tp_user_id
 * @property integer $service_id
 * @property integer $rating_id
 * @property string $date
 * @property boolean $trash
 */
class UserRating extends CActiveRecord {
    const DEFAULT_RATING_LIMIT_ON_PAGE = 10;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_user_rating';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_id, tp_user_id, service_id, rating_id', 'required'),
            array('post_id, tp_user_id, service_id, rating_id', 'numerical', 'integerOnly' => true),
            array('date, trash', 'safe'),
        );
    }

    public function beforeValidate() {
        $this->date = date('Y-m-d H:i:s');
        return parent::beforeValidate();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'service' => array(self::BELONGS_TO, 'RatingService', 'service_id', 'on' => "service.trash = 'f'"),
            'rating' => array(self::BELONGS_TO, 'Rating', 'rating_id', 'on' => "rating.trash = 'f'"),
            'post' => array(self::BELONGS_TO, 'Post', 'post_id', 'on' => "post.trash = 'f'"),
            'user' => array(self::BELONGS_TO, 'User', 'tp_user_id', 'on' => '"user".trash = \'f\' AND "user".active = \'t\''),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'post_id' => 'Post',
            'tp_user_id' => 'Tp User',
            'service_id' => 'Service',
            'rating_id' => 'Rating',
            'date' => 'Date',
            'trash' => 'Trash',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserRating the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @param $allocationId
     * @return mixed
     */
    public static function getTotalCountByAllocationId($allocationId) {
        return Yii::app()->db->createCommand()
            ->select('COUNT(id)')
            ->from('hi.hi_user_rating t')
            ->where('t.post_id IN (SELECT p.id FROM hi.hi_post p WHERE p.allocation_id = :allocationId AND p.trash = \'f\')', array(':allocationId' => $allocationId))
            ->andWhere('t.trash = \'f\'')
            ->queryScalar();
    }
}
