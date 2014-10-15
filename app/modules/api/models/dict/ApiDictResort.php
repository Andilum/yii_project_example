<?php

/**
 * This is the model class for table "dict.dict_resort".
 *
 * The followings are the available columns in table 'dict.vw_dict_resort':
 * @property integer $id
 * @property integer $country
 * @property string $name
 * @property string $name_eng
 * @property integer $capital
 */
class ApiDictResort extends DictResort {
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
                'country' => $attributeList['country'],
            );
        }
        
        return $result;
    }
}
