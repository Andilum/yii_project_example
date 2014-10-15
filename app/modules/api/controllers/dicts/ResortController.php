<?php
use Lightsoft\REST;
class ResortController extends UnauthorizeController {
    public function actionIndex() {
        $updated = Yii::app()->request->getQuery('updated', 0);
        $limit = Yii::app()->request->getQuery('limit', 0);
        
        $result = ApiDictResort::getList($updated, $limit, 'updated asc');
        
        $this->_responseSuccessView($result);
    }
}