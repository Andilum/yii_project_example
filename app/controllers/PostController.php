<?php

class PostController extends Controller {

    public function actionCreate() {
        $this->checkUserAuth();
        if (isset($_POST['Post'])) {
            $photos = array();
            $files = CUploadedFile::getInstances(Photo::model(), 'file');
            if ($files) {
                foreach ($files as $file) {
                    $photo['file'] = $file;
                    $photos[] = $photo;
                }
            }
            $_POST['Post']['allocation_id'] = isset($_POST['DictAllocation']['id']) ? $_POST['DictAllocation']['id'] : 0;
            $ratings = isset($_POST['UserRating']) ? $_POST['UserRating'] : array();
            Post::add(Yii::app()->user->id, $_POST['Post'], $photos, $ratings);
            isset($_POST['DictAllocation']['id']) ?
                $this->redirect("/allocation/{$_POST['DictAllocation']['id']}") :
                $this->redirect("/user/" . Yii::app()->user->id);
        }
        $this->redirect('/');
    }
    
    public function actionUpdate($id) {
        $this->checkUserAuth();
        $model = Post::model()->findByPk($id);
        if(isset($_POST['Post'])) {
            $photos = array();
            $files = CUploadedFile::getInstances(Photo::model(), 'file');
            if ($files) {
                foreach ($files as $file) {
                    $photo['file'] = $file;
                    $photos[] = $photo;
                }
            }
            Post::updateById(Yii::app()->user->id, $id, $_POST['Post'], $photos);
            $this->redirect('/');
        }
        $this->render('update',array('model'=>$model));
    }
    
    public function actionDelete($id) {
        $this->checkUserAuth();
        Post::destroyById($id, Yii::app()->user->id);
        $this->redirect('/');
    }

    public function actionList() {
        $params['pageSize'] = Post::DEFAULT_POST_LIMIT_ON_PAGE;
        $params['offset'] = Post::POST_LIMIT_ON_FIRST_LOAD;

        $page = Yii::app()->getRequest()->getParam('page');

        $params['ratedPosts'] = Yii::app()->getRequest()->getParam('ratedPosts', false);
        if (Yii::app()->getRequest()->getParam('all')) {
            $_GET['page'] = 1;
            $params['pageSize'] = Post::DEFAULT_POST_LIMIT_ON_PAGE * $page - Post::POST_LIMIT_ON_FIRST_LOAD;
        }

        if ($userId = Yii::app()->getRequest()->getParam('userId')) {
            $dataProvider = Post::initSearchByUser($userId, $params);
        } elseif ($allocId = Yii::app()->getRequest()->getParam('allocId')) {
            $dataProvider = Post::initSearchByAllocation($allocId, $params);
        } else {
            $dataProvider = Post::initSearch(null, null, $params);
        }
        $postList = $dataProvider->getData();

        $postIds = array();
        foreach ($postList as $post) {
            $postIds[] = $post->id;
        }
        $commentList = Comment::getLastByPostId($postIds);
        $likeList = Like::getListByOwnerIds($postIds);

        $data['result'] = 'success';
        $data['data']['items'] = $this->widget('PostList', array(
            'dataProvider' => $dataProvider,
            'commentList' => $commentList,
            'likeList' => $likeList,
        ), true);
        $data['data']['isLastPage'] = $page >= $dataProvider->pagination->pageCount;

        echo CJSON::encode($data);
        Yii::app()->end();
    }
}