<?php

/**
 * This is the model class for table "dict.dict_alloccat".
 *
 * The followings are the available columns in table 'dict.dict_alloccat':
 * @property integer $id
 * @property string $name
 * @property string $nick
 * @property string $name_eng
 * @property string $description
 * @property string $weight
 */
class ApiDictAlloccat extends DictAlloccat {
    /**
     * @param int $updated
     * @param int $limit
     * @param string $order
     * @return array
     */
    public static function getList($updated = 0, $limit = 0, $order = '') {
        $list = parent::getList($updated, $limit, $order);
        
        if ( empty($list) ) {
            return array();
        }
        
        $result = array();
        
        foreach ($list as $element) {
            $attributeList = $element->getAttributes();
            
            $result[] = array(
                'id' => $attributeList['id'],
                'name' => $attributeList['name'],
                'trash' => $attributeList['trash'],
                'updated' => $attributeList['updated'],
            );
        }
        
        return $result;
    }
}
