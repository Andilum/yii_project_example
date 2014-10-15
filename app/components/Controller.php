<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/main',
     * meaning using a single column layout. See 'app/views/layouts/main.php'.
     */
    public $layout='//layouts/column2';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    
    public $redirectUrl;
    
      /**
     * Моя страница - для авторизированого пользователя
     * @var bool
     */
    public $my = false;

    /**
     *
     * @var User 
     */
    private $_model;


    public function checkUserAuth() {
        if (Yii::app()->user->isGuest) {
            if (Yii::app()->request->isAjaxRequest) {
                $data = array(
                    'result' => 'error',
                    'error' => 'You must be logged',
                );
                echo CJSON::encode($data);
                Yii::app()->end();
            }
            $this->redirect('/');
        }
    }
    
    public function filters(){
            return array(
                array(
                    'application.filters.XssFilter',
                    'clean' => 'all'
                ),
            );
    }
    
  
}