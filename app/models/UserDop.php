<?php

/**
 * This is the model class for table "tp.tp_user_dop".
 *
 * The followings are the available columns in table 'tp.tp_user_dop':
 * @property integer $id
 * @property string $facebook
 * @property string $twitter
 * @property string $vkontakte
 * @property string $livejournal
 * @property string $odnoklassniki
 * @property string $plusgoogle
 * @property string $soc_www
 * @property string $updated
 */
class UserDop extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tp.tp_user_dop';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'required'),
            array('id', 'numerical', 'integerOnly' => true),
            array('facebook, twitter, vkontakte, livejournal, odnoklassniki, plusgoogle, soc_www', 'length', 'max' => 255),
            array('updated', 'safe'),
        );
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
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'vkontakte' => 'Vkontakte',
            'livejournal' => 'Livejournal',
            'odnoklassniki' => 'Odnoklassniki',
            'plusgoogle' => 'Plusgoogle',
            'soc_www' => 'Soc Www',
            'updated' => 'Updated',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserDop the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
