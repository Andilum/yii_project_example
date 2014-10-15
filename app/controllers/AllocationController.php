<?php

class AllocationController extends Controller {
    public $layout = 'allocation';

    public function actionIndex() {
        $dataProvider = Post::initSearch();
        $postList = $dataProvider->getData();

        $postIds = array();
        foreach ($postList as $post) {
            $postIds[] = $post->id;
        }
        $commentList = Comment::getLastByPostId($postIds);
        $likeList = Like::getListByOwnerIds($postIds);

        $this->render('view',array(
            'dataProvider' => $dataProvider,
            'commentList' => $commentList,
            'likeList' => $likeList,
        ));
    }

    public function actionView($id) {
        $dataProvider = Post::initSearchByAllocation($id);
        $postList = $dataProvider->getData();

        $postIds = array();
        foreach ($postList as $post) {
            $postIds[] = $post->id;
        }
        $commentList = Comment::getLastByPostId($postIds);
        $likeList = Like::getListByOwnerIds($postIds);

        $this->render('view',array(
            'dataProvider' => $dataProvider,
            'commentList' => $commentList,
            'likeList' => $likeList,
        ));
    }

    public function actionAutocomplete() {
        $result = DictHelper::getAllocationAutocompleteData();
        echo CJSON::encode($result);
        Yii::app()->end();
    }

    public function actionPhoto($id) {
        $allocation = DictAllocation::model()->findByPk($id);
        $page = Yii::app()->getRequest()->getParam('page');
        $type = Yii::app()->getRequest()->getParam('type', '');

        $params['allocationId'] = $id;
        if (Yii::app()->request->isAjaxRequest) {
            $params['pageSize'] = Photo::DEFAULT_PHOTO_LIMIT_ON_PAGE;
            if (Yii::app()->getRequest()->getParam('all')) {
                $_GET['page'] = 1;
                $params['pageSize'] = Photo::DEFAULT_PHOTO_LIMIT_ON_PAGE * $page;
            }
            if ($tagName = Yii::app()->getRequest()->getParam('tagName')) {
                $params['tagName'] = $tagName;
            }
        }
        $dataProvider = Photo::initSearch($params);

        if (Yii::app()->request->isAjaxRequest) {
            $data['result'] = 'success';
            $data['data']['photos'] = $this->renderPartial('_photoList', array('dataProvider' => $dataProvider,'type' => $type), true);
            $data['data']['isLastPage'] = $page >= $dataProvider->pagination->pageCount;

            echo CJSON::encode($data);
            Yii::app()->end();
        }

        $this->render('photo', array(
            'allocation' => $allocation,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ));
    }

    public function actionTag($id) {
        $allocation = DictAllocation::model()->findByPk($id);
        $page = Yii::app()->getRequest()->getParam('page');
        $sort = Yii::app()->getRequest()->getParam('sort');

        $params['allocationId'] = $id;
        if (Yii::app()->request->isAjaxRequest) {
            $params['pageSize'] = Tag::DEFAULT_POST_LIMIT_ON_PAGE;
            $params['offset'] = Tag::POST_LIMIT_ON_FIRST_LOAD;
            if (Yii::app()->getRequest()->getParam('all')) {
                $_GET['page'] = 1;
                $params['pageSize'] = Tag::DEFAULT_POST_LIMIT_ON_PAGE * $page - Tag::POST_LIMIT_ON_FIRST_LOAD;
            }
            if ($tagName = Yii::app()->getRequest()->getParam('tagName')) {
                $params['tagName'] = $tagName;
            }
        }
        $dataProvider = Tag::initSearch($params);

        if (Yii::app()->request->isAjaxRequest) {
            $data['result'] = 'success';
            $data['data']['tags'] = $this->renderPartial('_tagList', array('dataProvider' => $dataProvider), true);
            $data['data']['isLastPage'] = $page >= $dataProvider->pagination->pageCount;

            echo CJSON::encode($data);
            Yii::app()->end();
        }

        $this->render('tag', array(
            'allocation' => $allocation,
            'dataProvider' => $dataProvider,
            'sort' => $sort ? explode('.', $sort) : array('tc', 'desc'),
        ));
    }

    public function actionRating($id) {
        $allocation = DictAllocation::model()->findByPk($id);

        $params['ratedPosts'] = true;
        $dataProvider = Post::initSearchByAllocation($id, $params);
        $postList = $dataProvider->getData();

        $postIds = array();
        foreach ($postList as $post) {
            $postIds[] = $post->id;
        }
        $commentList = Comment::getLastByPostId($postIds);
        $likeList = Like::getListByOwnerIds($postIds);

        $this->render('rating', array(
            'dataProvider' => $dataProvider,
            'allocation' => $allocation,
            'commentList' => $commentList,
            'likeList' => $likeList,
        ));
    }
}