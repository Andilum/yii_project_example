<?php
use Lightsoft\REST;
class LikeController extends ApiRESTController {
    public function actionIndex($postId) {
        $model = ApiPost::model()->findByPk($postId);
        
        if ( !$model ) {
            $this->_responseNotFoundError('Post not found');
        }

        $result = $model->getLikesApi();

        $this->_responseSuccessView($result);
    }

    public function actionCreate($postId) {
        if ( !$postId ) {
            $this->_responseWrongRequest("Post info is required");
        }
        
        $result = Like::add(REST\Server::obtainAuthId(), $postId);
        
        $this->_responseSuccessView($result);
        
        if ( !empty($result) ) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseInternalError("Unable to add like");
        }
    }

    public function actionDestroy($likeId) {
        $result = Like::destroyByOwnerId($likeId, REST\Server::obtainAuthId());
        
        if ( isset($result) ) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseNotFoundError("Like is undefined");
        }
    }
}
