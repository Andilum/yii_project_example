<?php

class PhotoUpload extends CWidget {
    /**
     * @var int
     */
    public $postId = 0;
    /**
     * @var array
     */
    public $multiFileUpload = array();
    /**
     * @var bool
     */
    public $displayGallery = false;

    public function init() {
        $baseAssetsPath = Yii::getPathOfAlias('application.components.assets.PhotoUpload');
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/image-flash-preview.js'));
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/photoUpload.js'));
    }

    public function run() {
        $defaultMultiFileUpload = array(
            'id' => 'Photo_file_' . $this->postId,
            'model' => Photo::model(),
            'attribute' => 'file',
            'accept' => 'jpg|png|gif|jpeg|bmp',
            'htmlOptions' => array('style' => 'display:none'),
            'options' => array(
                'onFileAppend' => 'photoFileAppend',
            ),
            'duplicate' => "Вы уже выбрали этот файл:\n\$file",
            'denied' => "Файлы, с расширением \$ext, загружать запрещено",
        );
        $multiFileUpload = CMap::mergeArray($defaultMultiFileUpload, $this->multiFileUpload);

        $this->render('photoUpload', array(
            'postId' => $this->postId,
            'multiFileUpload' => $multiFileUpload,
            'displayGallery' => $this->displayGallery,
        ));
    }
}