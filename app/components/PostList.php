<?php

class PostList extends CWidget {
    /**
     * @var CActiveDataProvider
     */
    public $dataProvider;
    /**
     * @var array
     */
    public $commentList;
    /**
     * @var array
     */
    public $likeList;
    /**
     * @var string
     */
    public $ajaxLink;
    /**
     * @var array
     */
    public $ajaxOptions;

    public function init() {
        $baseAssetsPath = Yii::getPathOfAlias('application.components.assets.PostList');
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/postList.js'));
    }

    public function run() {
        $view = 'PostList/postList';
        return $this->render($view, array(
            'dataProvider' => $this->dataProvider,
            'commentList' => $this->commentList,
            'likeList' => $this->likeList,
            'ajaxLink' => $this->ajaxLink,
            'ajaxOptions' => $this->ajaxOptions,
        ));
    }
} 