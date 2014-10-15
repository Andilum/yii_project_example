<?php
use Lightsoft\REST;
class FeedController extends ApiRESTController {
    public function actionIndex($allocationId) {
        $offsetId = Yii::app()->request->getQuery('offset_id', 0);
        $limit = Yii::app()->request->getQuery('limit', 0);
        $order = Yii::app()->request->getQuery('order', 'desc');
        
        if ( strtolower($order) == 'asc' ) {
            $order = 'asc';
        } else {
            $order = 'desc';
        }
       
        $postList = ApiPost::getListByAllocationId($allocationId, null, $limit, $offsetId, "id $order"); 
       
        $result = ApiPost::makeFeedItemsFromPostListApi($postList, REST\Server::obtainAuthId());

        $this->_responseSuccessView($result);
    }
}
