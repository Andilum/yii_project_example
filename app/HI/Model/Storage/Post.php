<?php

namespace HI\Model\Storage;

/**
 * @author Alexandr S. Krotov (akrotov@lightsoft.ru)
 */

class Post extends Base {
    const DEFAULT_LIMIT = 10;
    
    /**
     * @var \HI\Model\Storage\Post
     */
    protected static $_instance;

    /**
     * @var string
     */
    protected $_objectBuilderClassName = "\\HI\\Model\\Entity\\Post\\ObjectBuilder";

    /**
     * @param int $id
     * @return \HI\Model\Storage\Post 
     */
    public function byUserId($id) {
        $this->_currentCountSelectField = $this->_createBaseFindCommand()
            ->andWhere("p.tp_user_id = :user_id")
            ->order($columns)
            ->bindParam("user_id", $id, \PDO::PARAM_INT);
                
        return $this;
    }

    /**
     * @param int $id
     * @return \HI\Model\Storage\Post 
     */
    public function byAllocationId($id) {
        $this->_currentCountSelectField = $this->_createBaseFindCommand()
            ->andWhere("p.allocation_id = :allocation_id")
            ->order($columns)
            ->bindParam("allocation_id", $id, \PDO::PARAM_INT);
                
        return $this;
    }
    
    /**
     * @return \CDbCommand 
     */
    protected function _createBaseFindCommand() {
        return $this->_connection->createCommand()
            ->select("
                p.id AS id,
                p.name AS name,
                p.text AS text,
                p.date AS date,
                u.id AS user_id,
                u.email AS user_email,
                u.nick AS user_nick,
                u.name AS user_name,
                al.id AS allocation_id,
                al.name AS allocation_name
            ")
            ->from("hi.hi_post AS p")
            ->join("dict.dict_allocation AS al", "al.id = p.allocation_id")
            ->join("tp.tp_user AS u", "u.id = p.tp_user_id")
            ->where("p.trash = false")
            ->order("p.date DESC");
    }
    
    /**
     * @param array $rawData
     * @return array 
     */
    protected function _prepareRawData($rawData) {
        foreach($rawData as $key => $element) {
            $rawData[$key] = array(
                "id" => $element['id'],
                "name" => $element['name'],
                "text" => $element['text'],
                "date" => $element['date'],
                "allocation" => array(
                    "id" => $element['allocation_id'],
                    "email" => $element['allocation_email'],
                ),
                "user" => array(
                    "id" => $element['user_id'],
                    "name" => $element['user_name'],
                    "nick" => $element['user_nick'],
                    "email" => $element['user_email'],
                )
            );
        }
        
        return $rawData;
    }
}

