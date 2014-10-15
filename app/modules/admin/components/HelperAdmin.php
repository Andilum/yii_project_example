<?php

class HelperAdmin extends CComponent {

    public function init() {
        
    }

    private function normItems(&$items) {
        foreach ($items as &$value) {
            if (isset($value['url']) && is_array($value['url'])) {
                $value['url'] = Yii::app()->createUrl($value['url'][0], array_splice($value['url'], 1));
            }
            if (isset($value['items']))
                $this->normItems($value['items']);
        }
    }

    public static function initAdmin() {
        Yii::app()->setComponent('errorHandler', array(
            'errorAction' => null,
            'class'=>'CErrorHandler'
        ));
        
        Yii::app()->configure(include Yii::getPathOfAlias('application.config.admin') . '.php');
    }

    public function getMenuModule() {
        
        $item = Yii::app()->params['admin_menu'];

        // это для поиска модулей которые имеют контролер админки
        /* foreach (Yii::app()->getModules() as $key => $value) {
          if (!in_array($key, array('admin', 'gii'))) {
          $p = Yii::app()->getModulePath() . '/' . $key;
          if (is_dir($p . '/controllers/admin') || file_exists($p . '/controllers/AdminController.php')) {
          if (file_exists($p . '/data/menu.php'))
          $item[] = require $p . '/data/menu.php';
          else
          $item[] = array('url' => array($key . '/admin/default/index'), 'label' => $this->getInfoModuleFromFile($p . '/data/data.xml', 'name'),);
          }
          }
          }
          $this->normItems($item); */

        return $item;
    }

    /* private function getInfoModuleFromFile($xml, $data) {
      $xml = simplexml_load_file($xml);
      return (string) $xml->{$data};
      } */
}
