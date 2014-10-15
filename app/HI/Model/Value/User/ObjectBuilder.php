<?php

namespace HI\Model\Value\User;

use HI\Model as HM;

abstract class ObjectBuilder extends HM\Value\User implements HM\ObjectBuilding {
    /**
     * @param array $rawData
     * @return CTypedList
     */
    public static function buildList($rawData) {
        $list = new \CTypedList("\\HI\\Model\\Value\\User");
        
        foreach($rawData as $element) {
            $list[] = self::buildOne($element);
        }
        
        return $list;
    }
    
    /**
     * @param array $rawData
     * @return \HI\Model\Value\User
     */
    public static function buildOne($rawData) {
        $post = new HM\Value\User();
        
        $post->_id = $rawData['id'];
        $post->_name = $rawData['name'];
        $post->_nick = $rawData['nick'];
        $post->_email = $rawData['email'];
        
        return $post;
    }
}

