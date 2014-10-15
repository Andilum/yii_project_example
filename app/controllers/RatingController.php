<?php

class RatingController extends Controller {
    public function actionIndex() {
        Yii::app()->clientScript->registerCssFile('/css/b-searchresults.css');

        $type = Yii::app()->request->getParam('type', SR::TYPE_USER);
        $page = Yii::app()->getRequest()->getParam('page', 1);
        $sort = Yii::app()->getRequest()->getParam('sort');

        $limit = SR::DEFAULT_LIMIT;
        if (Yii::app()->getRequest()->getParam('all')) {
            $limit = SR::DEFAULT_LIMIT * $page;
        }

        $sr = SR::model($type);
        $sr->withoutNullResults();
        if ($name = Yii::app()->getRequest()->getParam('name')) {
            $sr->setNamePattern($name);
        }
        $dataProvider = $sr->get($limit);

        if (Yii::app()->request->isAjaxRequest) {
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
    }
} 