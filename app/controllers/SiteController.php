<?php

class SiteController extends Controller {

    public $layout = 'column2';

    public function actionIndex() {
        if (Yii::app()->theme) {
            Yii::app()->clientScript->registerCssFile('/css/b-searchresults.css');

            $type = Yii::app()->request->getParam('type', SR::TYPE_ALLOCATION);
            $page = Yii::app()->getRequest()->getParam('page', 1);
            $sort = Yii::app()->getRequest()->getParam('sort');

            $limit = SR::DEFAULT_LIMIT;
            
            if ( Yii::app()->getRequest()->getParam('all') ) {
                $limit = SR::DEFAULT_LIMIT * $page;
            }

            $sr = SR::model($type)->withoutNullResults();
            
            if ( $name = Yii::app()->getRequest()->getParam('name') ) {
                $sr->setNamePattern($name);
            }
            
            $dataProvider = $sr->get($limit);

            if ( Yii::app()->request->isAjaxRequest ) {
                $data['result'] = 'success';
                $data['data']['items'] = $this->renderPartial('_ratingList', array(
                    'dataProvider' => $dataProvider,
                    'type' => $type,
                    'page' => $page,
                ), true);
                $data['data']['isLastPage'] = $page >= $dataProvider->pagination->pageCount;

                echo CJSON::encode($data);
                Yii::app()->end();
            }

            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'type' => $type,
                'page' => $page,
                'sort' => $sort ? explode('.', $sort) : array('tc', 'desc'),
            ));
        } else {
            $dataProvider = Post::initSearch();
            $postList = $dataProvider->getData();

            $postIds = array();
            foreach ($postList as $post) {
                $postIds[] = $post->id;
            }
            $commentList = Comment::getLastByPostId($postIds);
            $likeList = Like::getListByOwnerIds($postIds);

            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'commentList' => $commentList,
                'likeList' => $likeList,
            ));
        }
    }

    public function actionError() {
        if (Yii::app()->controller->module && Yii::app()->controller->module->id == 'admin') {
            $this->layout = 'main';
        }

        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }
}
