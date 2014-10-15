<?php

class MessageUserController extends AuthController {

    public $layout = 'user';
    public $model;
    public $my = true;

    protected function beforeRender($view) {
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile('/css/b-chat.css');
        return parent::beforeRender($view);
    }

    /**
     * чат с пользователем
     */
    public function actionUser($to) {
        $this->layout = 'user_chat';

        $userTo = $this->loadModelUser($to);
        $this->render('user', array('userTo' => $userTo));
    }

    protected function beforeAction($action) {
        $this->model = $this->getUser();
        return parent::beforeAction($action);
    }

    /**
     * 
     * @return User
     * @throws CHttpException
     */
    public function loadModelUser($id) {
        $model = User::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404);
        }
        return $model;
    }

    /**
     * Мои чаты
     * @param type $id
     */
    public function actionChats() {

        $sql = MessageUser::getSqlChats();

        $dataProvider = new CSqlDataProvider($sql, array(
            //'totalItemCount'=>0,
            'params' => array(
                ':uid' => Yii::app()->user->id
            ),
            'pagination' => false,
        ));

        $items = new ItemsPageLoader($dataProvider, '_msg', 'msg-items');

        $this->render('chats', array('items' => $items));
    }

}