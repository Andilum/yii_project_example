<?php

class SearchController extends Controller {

    public $layout = 'column2';

    public function filters() {
        return array(
        );
    }

    private static function dataSearch() {
        return array(
            'allocation' => array(
                'getDataProvider' => function($t) {
            return new CActiveDataProvider('DictAllocation', array(
                'criteria' => array(
                    'condition' => "name ILIKE '" . pg_escape_string($t) . "%' and trash=FALSE", 'order' => "name"
                )
            ));
        },
                'title' => Yii::t('app', 'Отели'),
                'countText' => '{n} отель|{n} отеля|{n} отелей',
                'all_text' => Yii::t('search', 'Все найденные отели'),
                'view' => '_allocation'
            ),
            'user' => array(
                'getDataProvider' => function($t) {
            return new CActiveDataProvider('User', array(
                'criteria' => array(
                    'condition' => "nick ILIKE '" . pg_escape_string($t) . "%' and trash=FALSE", 'order' => "nick"
                )
            ));
        },
                'title' => Yii::t('app', 'Пользователи'),
                'countText' => '{n} пользователь|{n} пользователя|{n} пользователей',
                'all_text' => Yii::t('search', 'Все найденные пользователи'),
                'view' => '_user'
            ),
            'tag' => array(
                'getDataProvider' => function($t) {


            return new CActiveDataProvider('Tag', array(
                'criteria' => array(
                    'condition' => "name ILIKE '" . pg_escape_string($t) . "%'", 'order' => "name",
                    'select' => 'distinct on (t.name) t.*',
                ),
                'countCriteria' => array(
                    'condition' => "name ILIKE '" . pg_escape_string($t) . "%'",
                    'select' => 'distinct on (t.name) t.*',
                    'group' => '1,2,3'
                ),
            ));
        },
                'title' => Yii::t('app', 'Хэштеги'),
                'countText' => '{n} хэштег|{n} хэштега|{n} хэштегов',
                'all_text' => Yii::t('search', 'Все найденные хэштеги'),
                'view' => '_tag'
            ),
        );
    }

    public function actionIndex($t = null, $type = null) {

        Yii::app()->clientScript->registerCssFile('/css/b-searchresults.css');

        $t = trim($t);
        if ($t == null) {
            $this->render('index_search', array('t' => $t,));
        } else {
            $dataSearch = self::dataSearch();

            $lpath = Yii::getPathOfAlias('ext.TextLangCorrect');
            require_once ($lpath . '/ReflectionTypeHint.php');
            require_once ($lpath . '/Text/LangCorrect.php');
            require_once ($lpath . '/UTF8.php');
            $corrector = new Text_LangCorrect();
            $t2 = $corrector->parse($t, $corrector::KEYBOARD_LAYOUT);

            //подчет  количества каждого элемента поиска

            $countAll = 0;

            foreach ($dataSearch as $k => $value) {
                $dp = $dataSearch[$k]['getDataProvider']($t);
                if (!$dp->getTotalItemCount() && $t2 != $t) {
                    $t = $t2;
                    $dp = $dataSearch[$k]['getDataProvider']($t);
                }
                $dataSearch[$k]['count'] = $dp->getTotalItemCount();
                $dataSearch[$k]['dataProvider'] = $dp;

                $countAll+=$dataSearch[$k]['count'];
            }

            if ($type !== null) {
                if (isset($dataSearch[$type])) {
                    $dataSearch[$type]['items'] = new ItemsPageLoader($dataSearch[$type]['dataProvider'], $dataSearch[$type]['view']);
                    $this->render('index', array('t' => $t, 'type' => $type, 'dataSearch' => $dataSearch, 'countAll' => $countAll));
                } else
                    throw new CHttpException(400);
            } else {
                foreach ($dataSearch as $value) {
                    $value['dataProvider']->pagination = array('pageSize' => 10);
                }

                $this->render('index_all', array('t' => $t, 'dataSearch' => $dataSearch, 'countAll' => $countAll));
            }
        }
    }

    public function actionAutocomplete() {
        if (Yii::app()->request->isAjaxRequest && ($term = trim(Yii::app()->getRequest()->getParam('term')))) {
            $items = DictHelper::getAllocationAndUserAutocompleteData($term);
            if (empty($items)) {
                $lpath = Yii::getPathOfAlias('ext.TextLangCorrect');
                require_once ($lpath . '/ReflectionTypeHint.php');
                require_once ($lpath . '/Text/LangCorrect.php');
                require_once ($lpath . '/UTF8.php');
                $corrector = new Text_LangCorrect();
                $term2 = $corrector->parse($term, $corrector::KEYBOARD_LAYOUT);
                if ($term2 != $term) {
                    $term = $term2;
                    $items = DictHelper::getAllocationAndUserAutocompleteData($term);
                }
            }
            header("Content-type: application/json; charset=utf-8");
            echo CJSON::encode($items);
            Yii::app()->end();
        }
        throw new CHttpException(400);
    }

}
