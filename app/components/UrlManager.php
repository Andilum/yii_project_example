<?php

/**
 * Description of UrlManager
 */
class UrlManager extends CUrlManager {
    
    /**
     * массив с исключениями части урл до /, страниц которым язык устанавливать не нужно
     * @var type 
     */
    public $exceptions=array('api','admin','gii');

    /**
     * Подставляеться язык в начало url
     */
    public function createUrl($route, $params = array(), $ampersand = '&') {
        $lang = '';
        if (isset($params['lang'])) {
            $lang = '/' . $params['lang'];
            unset($params['lang']);
        } elseif ((Yii::app()->getLanguage() != Yii::app()->sourceLanguage)) {
            $lang = '/' . Yii::app()->getLanguage();
        }
        return $lang . parent::createUrl($route, $params, $ampersand);
    }

    /**
     * Удаление языка из части url, перед разбором url
     * @param type $pathInfo
     * @return type
     */
    public function removeLangParam($pathInfo) {

        if (($i = strpos($pathInfo, '/')) !== false) {
            $l = substr($pathInfo, 0, $i);
        } else {
            $l = $pathInfo;
        }
        
        if (in_array($l, $this->exceptions))
        {
            return $pathInfo;
        }

        //если в начале url указан язык
        if (Yii::app()->translate->getHelper()->langExists($l)) {
            $_GET['lang'] = $l;
            Yii::app()->onParseLang(new CEvent($this));
            $pathInfo = (string) substr($pathInfo, strlen($l) + 1);

            //что бы не было дубликатов страниц, язык установленный по умолчанию не ставиться
            if ($l == Yii::app()->sourceLanguage) {
                $this->redirect($pathInfo);
            }
        } else {
            Yii::app()->onParseLang(new CEvent($this));
            //если установлен язык не по умолчанию но в урл его нет, подставляем и делаем редирект
            if ((Yii::app()->getLanguage() != Yii::app()->sourceLanguage)) {
                $this->redirect(Yii::app()->getLanguage() . '/' . $pathInfo);
            }
        }

        return $pathInfo;
    }

    public function removeUrlSuffix($pathInfo, $urlSuffix) {
        return $this->removeLangParam(parent::removeUrlSuffix($pathInfo, $urlSuffix));
    }

    private function redirect($pathInfo) {
        if (!Yii::app()->request->isPostRequest && !Yii::app()->request->isAjaxRequest) {
            $url = '/' . $pathInfo;
            if (($q = Yii::app()->request->getQueryString())) {
                $url.='?' . $q;
            }
            Yii::app()->request->redirect($url);
        }
    }
}