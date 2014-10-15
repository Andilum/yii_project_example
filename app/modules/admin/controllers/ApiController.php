<?php

class ApiController extends ControllerAdmin {

    /**
     * Declares class-based actions.
     */
    public $layout = false;

    public function actions() {
        return array(
            //действие для  загрузки изображений
            'imgLoad' => array('class' => 'ext.images.ALoadImg',
            )
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow admin Page to perform 'admin' and 'delete' actions
                'users' => array('@'), 'expression' => '!empty(Yii::app()->user->isadminka)'
            ),
            array('deny', // deny all Pages
                'users' => array('*'),
            ),
        );
    }

    public function actionAutocompleteUser() {
        $term = Yii::app()->getRequest()->getParam('term');

        if (Yii::app()->request->isAjaxRequest && $term) {
            Yii::import('application.models.User');
            $criteria = new CDbCriteria;

            // формируем критерий поиска
            if (is_numeric($term))
                $criteria->condition = 'id=' . Yii::app()->db->quoteValue($term);
            $criteria->addSearchCondition('name', $term, true, 'OR','ILIKE');
            $criteria->addSearchCondition('nick', $term, true, 'OR','ILIKE');
            $criteria->addSearchCondition('email', $term, true, 'OR','ILIKE');

            $criteria->limit = 15;
            $criteria->select='id,nick';
            $customers = User::model()->findAll($criteria);

            // обрабатываем результат
            $result = array();
            foreach ($customers as $customer) {
                $result[] = array('id' => $customer->id, 'label' => $customer->nick.' #'.$customer->id);
            }
            header('Content-type: application/json; charset=utf-8');
            echo CJSON::encode($result);
            Yii::app()->end();
        }
    }
    
    
     public function actionAutocompleteHotel() {
        $term = Yii::app()->getRequest()->getParam('term');

        if (Yii::app()->request->isAjaxRequest && $term) {
            Yii::import('application.models.dict.DictAllocation');
            $criteria = new CDbCriteria;

            // формируем критерий поиска
            if (is_numeric($term))
                $criteria->condition = 'id=' . Yii::app()->db->quoteValue($term);
            $criteria->addSearchCondition('name', $term, true, 'OR','ILIKE');
            $criteria->addSearchCondition('name_eng', $term, true, 'OR','ILIKE');
            
            $criteria->limit = 15;
            $criteria->select='id,name';
            $customers = DictAllocation::model()->findAll($criteria);

            // обрабатываем результат
            $result = array();
            foreach ($customers as $customer) {
                $result[] = array('id' => $customer->id, 'label' => $customer->name.' #'.$customer->id);
            }
            header('Content-type: application/json; charset=utf-8');
            echo CJSON::encode($result);
            Yii::app()->end();
        }
    }

  
}
