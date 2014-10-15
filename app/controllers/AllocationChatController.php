<?php

class AllocationChatController extends AuthController {

    public $layout = 'allocation';

    /**
     *
     * @var DictAllocation 
     */
    public $model;
    
    
     protected function beforeRender($view) {
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile('/css/b-chat.css');
        return parent::beforeRender($view);
    }

    protected function beforeAction($action) {
        if (isset($_GET['id'])) {
            $this->loadModelAllocation($_GET['id']);
        } else {
            throw new CHttpException(400, 'no param id');
        }
        Yii::app()->clientScript->registerCssFile('/css/b-chat-hotel.css');
        
        return parent::beforeAction($action);
    }

    /**
     * чаты отеля
     */
    public function actionIndex() {
        $hotel = $this->model;

        $dataProvider = new CActiveDataProvider('HotelChat', array(
            'criteria' => array(
                'condition' => 't.hotel_id=' . $hotel->id,
                'order' => 'id desc'
            )
        ));

        $items = new ItemsPageLoader($dataProvider, '_item_chat');
        $items->emptText = '<p>Чатов не создано</p>';


        $this->render('index', array('items' => $items));
    }
    
    public function actionChat($chat) {
        $this->layout='allocation_chat';
        
        
        $chat=  HotelChat::model()->findByPk($chat,'hotel_id='.$this->model->id);
        if (!$chat)
        {
            throw new CHttpException(404);
        }
        $this->render('chat',array('chat'=>$chat));
    }

    public function actionCreate() {
        $this->accessHotelier();

        $model = new HotelChat('user');

        if (isset($_POST['HotelChat'])) {
            $model->attributes = $_POST['HotelChat'];
            $model->hotel_id = $this->model->id;
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest) {
                    exit('ok');
                } else {
                    $this->redirect(array('index'));
                }
            }
        }
        
        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('create_ajax',array('model'=>$model));
        } else {
            $this->render('create',array('model'=>$model));  
        }
      
    }

    /**
     * 
     * @param int $id
     * @return DictAllocation
     * @throws CHttpException
     */
    public function loadModelAllocation($id) {
        $this->model = DictAllocation::model()->findByPk($id);
        if (!$this->model) {
            throw new CHttpException(404);
        }
        return $this->model;
    }

    public function accessHotelier() {
        if (!$this->isHotelier()) {
            throw new CHttpException(403);
        }
    }

    /**
     * являеться ли авторизированый пользоваатель отельером отеля из $this->model
     * @return boolean
     */
    public function isHotelier() {
        return true;
    }
    
    public function createUrl($route, $params = array(), $ampersand = '&') {
        $params['id']=$this->model->id;
        return parent::createUrl($route, $params, $ampersand);
    }

}
