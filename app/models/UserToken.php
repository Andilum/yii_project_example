<?php
   
use Lightsoft\REST;

/**
 * This is the model class for table "hi.hi_user_token".
 *
 * The followings are the available columns in table 'hi.hi_user_token':
 * @property integer $id
 * @property string $token
 * @property integer $tp_user_id
 * @property boolean trash
 */
class UserToken extends CActiveRecord implements REST\Server\Authenticator {
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return IstatVwAllocationStatFastCommon the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'hi.hi_user_token';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tp_user_id, token', 'required'),
            array('tp_user_id', 'numerical', 'integerOnly' => true),
            array('token', 'length', 'max' => 32),
            array('token, trash', 'safe'),
        );
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
        return array();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
    }

    /**
     * @param string $value
     * @return int
     */
    public function getAuthIdByAuthParamValue($value) {
        $token = $this->findByAttributes(array("token" => $value, "trash" => false));
        
        return $token ? $token->tp_user_id : 0;
    }
    
    /**
     * @param int $id
     * @return UserToken
     * @throws CException 
     */
    public static function createForUserId($id) {
        $token = new static("insert");
        $token->attributes = array(
            "tp_user_id" => $id,
            "token" => md5(microtime())
        );
        
//        $transaction = $token->getDbConnection()->beginTransaction();
        
        try {
            if ( $token->save() ) {
//                $token->updateAll(
//                    array("trash" => true,), 
//                    "tp_user_id = :user_id AND token != :token",
//                    array(
//                        "user_id" => $id,
//                        "token" => $token->token
//                    )
//                );
//
//                $transaction->commit();
                
                return $token;
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_NOTICE);
        }
        
//        $transaction->rollback();
        
        throw new CException("Unable to save auth info");
    }
}