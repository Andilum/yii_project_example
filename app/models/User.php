<?php

/**
 * This is the model class for table "tp.tp_user".
 *
 * The followings are the available columns in table 'tp.tp_user':
 * @property integer $id
 * @property integer $tm_id
 * @property string $nick
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $sex
 * @property integer $city
 * @property integer $country
 * @property string $city_other
 * @property string $date_create
 * @property string $my_updated
 * @property string $avatar_ext
 * @property string $birthday
 * @property string $icq
 * @property string $www
 * @property string $profession
 * @property string $phone
 * @property boolean $active
 * @property boolean $show_email
 * @property boolean $show_icq
 * @property string $description
 * @property string $interests
 * @property boolean $manager
 * @property string $company
 * @property integer $stage
 * @property integer $reg_proj
 * @property string $specs
 * @property integer $specialization_id
 * @property integer $password_strength
 * @property boolean $news_subscription_tophotels
 * @property boolean $news_subscription_turpoisk
 * @property boolean $news_subscription_rutraveler
 * @property boolean $news_subscription_travelview
 * @property boolean $news_subscription_traveltalk
 * @property boolean $news_subscription_travelpassport
 * @property boolean $confirmed
 * @property string $updated
 * @property boolean $trash
 * @property integer $hp_id
 * @property string $hp_updated
 * @property integer $agent_ti_id
 * @property boolean $news_subscription_hotelsbroker
 * @property boolean $not_show_age
 */
class User extends CActiveRecord {
    const AVATAR_SIZE_25 = 25,
        AVATAR_SIZE_50 = 50,
        AVATAR_SIZE_100 = 100,
        AVATAR_SIZE_290 = 290;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tp.tp_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nick, email, password, city, country, date_create, my_updated, updated', 'required'),
            array('tm_id, city, country, stage, reg_proj, specialization_id, password_strength, hp_id, agent_ti_id', 'numerical', 'integerOnly' => true),
            array('nick, email, name, surname, patronymic, city_other, www, interests, company, specs', 'length', 'max' => 255),
            array('password', 'length', 'max' => 32),
            array('avatar_ext', 'length', 'max' => 5),
            array('icq, profession, phone', 'length', 'max' => 100),
            array('sex, birthday, active, show_email, show_icq, description, manager, news_subscription_tophotels, news_subscription_turpoisk, news_subscription_rutraveler, news_subscription_travelview, news_subscription_traveltalk, news_subscription_travelpassport, confirmed, trash, hp_updated, news_subscription_hotelsbroker, not_show_age', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'co' => array(self::BELONGS_TO, 'DictCountry', 'country'),
            'ct' => array(self::BELONGS_TO, 'DictCity', 'city'),
            'dop' => array(self::HAS_ONE, 'UserDop', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'tm_id' => 'Tm',
            'nick' => 'Nick',
            'email' => 'Email',
            'password' => 'Password',
            'name' => 'Name',
            'surname' => 'Surname',
            'patronymic' => 'Patronymic',
            'sex' => 'Sex',
            'city' => 'City',
            'country' => 'Country',
            'city_other' => 'City Other',
            'date_create' => 'Date Create',
            'my_updated' => 'My Updated',
            'avatar_ext' => 'Avatar Ext',
            'birthday' => 'Birthday',
            'icq' => 'Icq',
            'www' => 'Www',
            'profession' => 'Profession',
            'phone' => 'Phone',
            'active' => 'Active',
            'show_email' => 'Show Email',
            'show_icq' => 'Show Icq',
            'description' => 'Description',
            'interests' => 'Interests',
            'manager' => 'Manager',
            'company' => 'Company',
            'stage' => 'Stage',
            'reg_proj' => 'Reg Proj',
            'specs' => 'Specs',
            'specialization_id' => 'Specialization',
            'password_strength' => 'Password Strength',
            'news_subscription_tophotels' => 'News Subscription Tophotels',
            'news_subscription_turpoisk' => 'News Subscription Turpoisk',
            'news_subscription_rutraveler' => 'News Subscription Rutraveler',
            'news_subscription_travelview' => 'News Subscription Travelview',
            'news_subscription_traveltalk' => 'News Subscription Traveltalk',
            'news_subscription_travelpassport' => 'News Subscription Travelpassport',
            'confirmed' => 'Confirmed',
            'updated' => 'Updated',
            'trash' => 'Trash',
            'hp_id' => 'Hp',
            'hp_updated' => 'Hp Updated',
            'agent_ti_id' => 'Agent Ti',
            'news_subscription_hotelsbroker' => 'News Subscription Hotelsbroker',
            'not_show_age' => 'Not Show Age',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function getUrl(){
        return Yii::app()->createUrl('user/view', array('id' => $this->id)) ;
    }
    
    /**
     * @param $userId
     * @param $size
     * @return string
     */
    public static function getAvatarPath($userId, $size) {
        $tpHost = Yii::app()->travelpassport->serverUrl;
        return "{$tpHost}/avatar/{$size}/{$userId}";
    }
    
    public static function getData($user) {
        return array(
            'id'=>$user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'nik' => $user->nick,
            'email' => $user->email,
            'url' => $user->getUrl(),
            'ava' => self::getAvatarPath($user->id, User::AVATAR_SIZE_50),
        );
    }
    
   
    
    public function getSendMessageUrl()
    {
        return Yii::app()->createUrl('messageUser/user', array('to' => $this->id)) ;
    }

    /**
     * @return CDbDataReader|mixed|string
     */
    public static function getTotalCount() {
        return self::model()->count("active = 't' AND trash = 'f'");
    }
}
