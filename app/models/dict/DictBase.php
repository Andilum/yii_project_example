<?php

class DictBase extends CActiveRecord {
    /**
     * @param int $updated
     * @param int $limit
     * @param string $order
     * @return CActiveRecord[]
     */
    public static function getList($updated = 0, $limit = 0, $order = '') {
        $criteria = new CDbCriteria();
        
        if ( $updated ) {
            $criteria->addCondition("updated > :updated");
            $criteria->params[':updated'] = $updated;
        }
        
        if ( $limit ) {
            $criteria->limit = $limit;
        }
        
        if ( $order ) {
            $criteria->order = $order;
        }

        return static::model()->findAll($criteria);
    }

    
}
