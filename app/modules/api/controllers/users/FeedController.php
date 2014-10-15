<?php
use Lightsoft\REST;
class FeedController extends ApiRESTController {

    public function actionIndex($nick) {
        $user = User::model()->find('nick = :nick', array(':nick' => $nick));

        if (!$user) {
            $this->_responseNotFoundError("User not found");
        }

        $offsetId = Yii::app()->request->getQuery('offset_id', null);
        $limit = Yii::app()->request->getQuery('limit', null);
        $order = Yii::app()->request->getQuery('order', 'desc');

        if (strtolower($order) == 'asc') {
            $order = 'asc';
        } else {
            $order = 'desc';
        }

        $postList = ApiPost::getListByUserId($user->id, null, $limit, $offsetId, "id $order");
        $result = ApiPost::makeFeedItemsFromPostListApi($postList, REST\Server::obtainAuthId());

        $this->_responseSuccessView($result);
    }

    public function actionCreate() {
        $data = REST\Server::obtainRequestData();
        
        if ($post = ApiPost::create($data, REST\Server::obtainAuthId())) {
            $result = $post->id;
            $this->_responseSuccessView($result);
        } else {
            $this->_responseInternalError("Failed to create the post");
        }
        
    }

}
