<?php

class AuthLink extends CWidget {

    public $text;
    public $url;
    public $htmlOptions;
    public $selector;
    public $showLink = true;

    public $scriptPosition = CClientScript::POS_END;

    public function run() {

        if ($this->showLink) {
            $this->selector = '.auth-link';
            if (isset($this->htmlOptions['class']))
                $this->htmlOptions['class'].=' auth-link';
            else
                $this->htmlOptions['class'] = 'auth-link';
            echo CHtml::link($this->text, $this->url, $this->htmlOptions);
        }
        $this->registerAssets($this->selector);
    }

    private function registerAssets($s) {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $dir = __DIR__ . '/assets/auth_link/project_list_auth_link.js';
        $cs->registerScriptFile(Yii::app()->assetManager->publish(__DIR__ . '/assets/auth_link/project_list_auth_link.js'), $this->scriptPosition);
        $cs->registerScript('eauth', "$('$s').eauth();", CClientScript::POS_READY);
    }

}