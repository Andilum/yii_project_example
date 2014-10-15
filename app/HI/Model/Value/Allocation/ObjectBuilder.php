<?php

namespace HI\Model\Value\Allocation;

use HI\Model as HM;

abstract class ObjectBuilder extends HM\Value\Allocation implements HM\ObjectBuilding {
    /**
     * @param array $rawData
     * @return CTypedList
     */
    public static function buildList($rawData) {
        $list = new \CTypedList("\\HI\\Model\\Value\\Allocation");
        
        foreach($rawData as $element) {
            $list[] = self::buildOne($element);
        }
        
        return $list;
    }
    
    /**
     * @param array $rawData
     * @return \HI\Model\Value\Allocation
     */
    public static function buildOne($rawData) {
        $post = new HM\Value\Allocation();
        
        $post->_id = $rawData['id'];
        $post->_name = $rawData['name'];
        
        return $post;
    }
}

