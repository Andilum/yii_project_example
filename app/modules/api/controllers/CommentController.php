<?php
use Lightsoft\REST;
class CommentController extends ApiRESTController {

    public function actionCreate() {
        $data = REST\Server::obtainRequestData();

        if ( $comment = ApiComment::create($data, REST\Server::obtainAuthId()) ) {
            if ( isset($data['photos']) ) {
                $comment->attachPhotos($data['photos']);
            }
            
            $result = $comment->id;
        }

        if (isset($result)) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseInternalError("Failed to create the comment");
        }
    }

    public function actionUpdate($id) {
        $data = REST\Server::obtainRequestData();
        
        if ( $comment = ApiComment::updateByIdApi($id ,$data, REST\Server::obtainAuthId()) ) {
            if ( isset($data['photos']) && !empty($data['photos']) ) {
                $comment->attachPhotos($data['photos']);
            }
            
            $result = $comment->id;
        }

        if (isset($result)) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseInternalError("Failed to create the comment");
        }
    }

    public function actionDestroy($id) {
        $result = ApiComment::destroyById($id, REST\Server::obtainAuthId());

        if (isset($result)) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseNotFoundError("HotelsInspector info not found");
        }
    }
}
