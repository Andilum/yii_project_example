<?php

/**
 * Description of WfileLoad
 */
class WLoadAttach extends CWidget {

    /**
     *
     * @var CActiveRecord
     */
    public $modelName = 'MessageUser';

    public function init() {
        $assets = __DIR__ . '/assets/LoadAttach';
        $path = Yii::app()->assetManager->publish($assets);
        Yii::app()->clientScript->registerScriptFile($path . '/attach.js');
        Yii::app()->clientScript->registerCssFile($path . '/attach.css');
    }

    public function run() {

        $id = $this->getId();

        echo '<div id="' . $id . '"></div>';

        echo '<div class="attach-control" id="' . $id . '-control">
                <span class="pull-left color-medium-grey m8t">Добавьте </span>
                <a href="#" class="btn bg-grey color-medium-grey type_file">
                    <i class="icon-foto"></i>
                    ФОТО
                </a>
              
            </div>';
        
        
           $c = Yii::app()->clientScript;

        $c->registerScript('WLoadAttach' . $id, '$(\'#' . $id . '\').attachWidget('.CJavaScript::encode(array(
            'name'=>  $this->modelName,
            'typesMime'=>  MessageAttachment::typesFile() )).')');
        
    }

}
