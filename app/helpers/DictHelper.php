<?php

abstract class DictHelper {
    
    public static function getAllocationAutocompleteData() {
        $term = Yii::app()->getRequest()->getParam('term');

        if(Yii::app()->request->isAjaxRequest && $term) {
            $allocations = DictAllocation::model()->findAll(array('condition'=>"name LIKE '$term%'", 'order'=>"name"));
            $result = array();
            foreach($allocations as $allocation) {
                $result[] = array('id'=>$allocation->id, 'text'=>$allocation->name, 'value'=>$allocation->name);
            }
            return $result;
        }
        return array();
    }
    
    public static function getAllocationAndUserAutocompleteData($term) {
            $term=  pg_escape_string($term);
            $allocations = DictAllocation::model()->findAll(array('condition'=>"name ILIKE '$term%'", 'order'=>"name",'limit'=>10));
            $users = User::model()->findAll(array('condition'=>"nick ILIKE '$term%'", 'order'=>"nick",'limit'=>10));
            $result = array();
            foreach($allocations as $allocation) {
                $result[] = array('id'=>$allocation->id, 'text'=>$allocation->name, 'value'=>$allocation->name,'url'=>$allocation->getUrl());
            }
            foreach($users as $user) {
                $result[] = array('id'=>$user->id, 'text'=>$user->nick, 'value'=>$user->nick,'url'=>$user->getUrl());
            }
            return $result;
        
        return array();
    }
}