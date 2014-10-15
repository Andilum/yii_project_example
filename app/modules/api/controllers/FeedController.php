<?php
use Lightsoft\REST;
class FeedController extends ApiRESTController {

    public function actionIndex() {
        $offsetId = Yii::app()->request->getQuery('offset_id', null);
        $limit = Yii::app()->request->getQuery('limit', null);
        $order = Yii::app()->request->getQuery('order', null);
        if (strtolower($order) == 'asc') {
            $order = strtolower($order);
        } else {
            $order = 'desc';
        }
        $postList = ApiPost::getList(null, $limit, $offsetId, "id $order");
        $result = ApiPost::makeFeedItemsFromPostListApi($postList);
        $this->_responseSuccessView($result);
    }

    public function actionView($postId) {
        $postList[] = ApiPost::get($postId);
        $result = ApiPost::makeFeedItemsFromPostListApi($postList);

        $this->_responseSuccessView($result);
    }

    public function actionCreate() {
        $data = REST\Server::obtainRequestData();
        if ($post = ApiPost::create($data, REST\Server::obtainAuthId())) {
            if (isset($data['photos'])) {
                $post->attachPhotos($data['photos']);
            }
            $result = $post->id;
        }

        if (!empty($result)) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseInternalError("Failed to create the post");
        }
    }

    public function actionUpdate($postId) {
        $data = REST\Server::obtainRequestData();
        if ($post = ApiPost::updateByIdApi($postId, $data, REST\Server::obtainAuthId())) {
            if (isset($data['photos'])) {
                $post->attachPhotos($data['photos']);
            }
            $result = $post->id;
        }

        if (!empty($result)) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseInternalError("Failed to update the post");
        }
    }

    public function actionDestroy($postId) {
        $result = ApiPost::destroyById($postId, REST\Server::obtainAuthId());

        if (!empty($result)) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseNotFoundError("Post not found");
        }
    }
}
