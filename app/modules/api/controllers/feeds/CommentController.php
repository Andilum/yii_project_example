<?php
use Lightsoft\REST;
class CommentController extends ApiRESTController {
    public function actionIndex($postId) {
        $offsetId = Yii::app()->request->getQuery('offset_id', null);
        $limit = Yii::app()->request->getQuery('limit', 0);
        $order = Yii::app()->request->getQuery('order', 'desc');
        
        if ( !$postId ) {
            $this->_responseWrongRequest("Post info is required");
        }
        
        if ( strtolower($order) == 'asc' ) {
            $order = strtolower($order);
        } else {
            $order = 'desc';
        }
        
        $result = ApiPost::getCommentsApi($postId, $limit, $offsetId, "id $order");

        $this->_responseSuccessView($result);
    }
}
