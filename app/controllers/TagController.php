<?php

class TagController extends Controller {
    public function actionIndex() {
        $page = Yii::app()->getRequest()->getParam('page');
        $sort = Yii::app()->getRequest()->getParam('sort');

        $params = array();
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

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'sort' => $sort ? explode('.', $sort) : array('tc', 'desc'),
        ));
    }

    public function actionView($tag) {
        $params = array();
        if (Yii::app()->getRequest()->isAjaxRequest) {
            $params['pageSize'] = Tag::DEFAULT_POST_LIMIT_ON_PAGE;
            $params['offset'] = Tag::POST_LIMIT_ON_FIRST_LOAD;
        }

        $dataProvider = Tag::initSearchByTag($tag, $params);
        $postList = $dataProvider->getData();

        $postIds = array();
        foreach ($postList as $post) {
            $postIds[] = $post->id;
        }
        $commentList = Comment::getListByPostIds($postIds, array('tag' => $tag));

        $page = Yii::app()->getRequest()->getParam('page');

        if (Yii::app()->getRequest()->isAjaxRequest) {
            $data['result'] = 'success';
            $data['data']['items'] = $this->renderPartial('view', array(
                'dataProvider' => $dataProvider,
                'commentList' => $commentList,
                'tag' => $tag,
            ), true);
            $data['data']['isLastPage'] = $page >= $dataProvider->pagination->pageCount;

            echo CJSON::encode($data);
            Yii::app()->end();
        }

        $this->render('view',array(
            'dataProvider' => $dataProvider,
            'commentList' => $commentList,
            'tag' => $tag,
        ));
    }
} 