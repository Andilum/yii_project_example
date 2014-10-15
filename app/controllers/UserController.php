<?php

class UserController extends Controller {

    public $layout = 'user';

    /**
     * Моя страница - для авторизированого пользователя
     * @var bool
     */
    public $my = false;

    /**
     *
     * @var User 
     */
    private $_model;

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return User
     */
    public function loadModel($id) {
        $this->_model = User::model()->findByPk($id);
        if ($this->_model === null)
            throw new CHttpException(404, 'Not Found');
        $this->my = $this->_model->id == Yii::app()->user->id;
        return $this->_model;
    }

    public function getModel() {
        if (!$this->_model) {
            $this->loadModel(@$_GET['id']);
        }
        return $this->_model;
    }

    public function actionView($id) {
        $this->loadModel($id);

        $dataProvider = Post::initSearchByUser($id);
        $postList = $dataProvider->getData();

        $postIds = array();
        foreach ($postList as $post) {
            $postIds[] = $post->id;
        }
        $commentList = Comment::getLastByPostId($postIds);
        $likeList = Like::getListByOwnerIds($postIds);

        $this->render('view', array(
            'dataProvider' => $dataProvider,
            'commentList' => $commentList,
            'likeList' => $likeList,
            'userId' => $id,
        ));
    }

    public function actionSubscriptions($id, $t = null) {
        $user = $this->loadModel($id);

        if ($t == 'sub') {
            $data = UserSubscription::getList($id);
        } elseif ($t == 'alloc') {
            $data = AllocationSubscription::getList($id, true);
        } else {
            $data = UserSubscription::getReaders($id);
        }

        $this->render('subscriptions', array(
            'user' => $user,
            'data' => $data,
            'type' => $t,
        ));
    }

    public function actionTag($id = null) {
        if (is_null($id)) {
            $id = Yii::app()->user->id;
        }
        $user = User::model()->findByPk($id);

        $page = Yii::app()->getRequest()->getParam('page');
        $sort = Yii::app()->getRequest()->getParam('sort');

        $params['userId'] = $id;
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
            'user' => $user,
            'dataProvider' => $dataProvider,
            'sort' => $sort ? explode('.', $sort) : array('tc', 'desc'),
        ));
    }

    public function actionInfo($id = null) {
        if (is_null($id)) {
            $id = Yii::app()->user->id;
        }
        $criteria = new CDbCriteria();
        $criteria->with = array(
            'co' => array(
                'select' => 'name',
            ),
            'ct' => array(
                'select' => 'name',
            ),
            'dop' => array(
                'select' => 'facebook, twitter, vkontakte',
            ),
        );
        $user = User::model()->findByPk($id, $criteria);

        $this->render('info', array(
            'user' => $user,
        ));
    }

    public function actionPhoto($id = null) {
        if (is_null($id)) {
            $id = Yii::app()->user->id;
        }
        $user = User::model()->findByPk($id);

        $page = Yii::app()->getRequest()->getParam('page');
        $type = Yii::app()->getRequest()->getParam('type', '');

        $params['userId'] = $id;
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
            $data['data']['photos'] = $this->renderPartial('_photoList', array('dataProvider' => $dataProvider, 'type' => $type), true);
            $data['data']['isLastPage'] = $page >= $dataProvider->pagination->pageCount;

            echo CJSON::encode($data);
            Yii::app()->end();
        }


        $this->render('photo', array(
            'user' => $user,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ));
    }

    public function actionGetData($id) {
        $user = User::model()->findByPk($id);
        if (!$user) {
            throw new CHttpException(404);
        }
        echo json_encode(User::getData($user));
    }

}
