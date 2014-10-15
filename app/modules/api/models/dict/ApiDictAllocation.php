<?php

/**
 * This is the model class for table "dict.dict_allocation".
 *
 * The followings are the available columns in table 'dict.dict_allocation':
 * @property integer $id
 * @property string $name
 * @property string $name_eng
 * @property integer $cat
 * @property integer $resort
 */

class ApiDictAllocation extends DictAllocation {
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
                'cat' => $attributeList['cat'],
                'resort' => $attributeList['resort'],
            );
        }
        
        return $result;
    }
}
