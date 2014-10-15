<?php

namespace HI\Model\Entity\Post;


use HI\Model as HM;

abstract class ObjectBuilder extends HM\Entity\Post implements HM\ObjectBuilding {
    /**
     * @param array $rawData
     * @return CTypedList
     */
    public static function buildList($rawData) {
        $list = new \CTypedList("\\HI\\Model\\Entity\\Post");
        
        foreach($rawData as $element) {
            $list[] = self::buildOne($element);
        }
        
        return $list;
    }
    
    /**
     * @param array $rawData
     * @return \HI\Model\Entity\Post
     */
    public static function buildOne($rawData) {
        $post = new HM\Entity\Post();
        
        $post->_id = $rawData['id'];
        $post->_name = $rawData['name'];
        $post->_date = \DateTime::createFromFormat("Y-m-d H:i:s", $rawData['date']);
        $post->_text = $rawData['text'];
        $post->_allocation = HM\Value\Allocation\ObjectBuilder::buildOne($rawData['allocation']);
        $post->_user = HM\Value\User\ObjectBuilder::buildOne($rawData['user']);
        
        return $post;
    }
}

