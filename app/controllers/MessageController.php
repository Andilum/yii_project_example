<?php

/**
 * Контроллер для чата
 */
class MessageController extends AuthController {

    public $layout = false;
    private $_obj;

    /**
     * компонент для чата
     * @return MessageChat
     */
    public function getComponentChat() {
        if (!$this->_obj) {
            $this->_obj = new MessageChat(Yii::app()->user->id);
        }
        return $this->_obj;
    }

    public function accessRules() {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('getAttach'),
                'users' => array('*'),
            ),
            array('deny', // deny all ForumForumss
                'users' => array('*'),
            ),
        );
    }

    /**
     * Прочтение сообщений
     * @throws CHttpException
     */
    public function actionRead() {
        $ids = filter_input(INPUT_POST, 'ids', FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^(\\d+,?)+$/')));
        $type = filter_input(INPUT_POST, 'type');
        if ($ids && $type) {
            switch ($type) {
                case 'user':
                    $this->getComponentChat()->read(explode(',', $ids));
                    break;
                default:
                    throw new CHttpException(400);
            }
        } else {
            throw new CHttpException(400);
        }
    }

    /**
     *  получение новых сообщений
     * @throws CHttpException
     */
    public function actionEvent() {
        if (isset($_POST['r'])) {
            $data = json_decode($_POST['r'], true);
            if (isset($data['type'])) {
                if ($o = $this->getComponentChat()->event($data['type'], isset($data['data']) ? $data['data'] : array())) {
                    echo json_encode($o);
                }
            }
        }
    }

    public function actionDelteChat() {
        if ($userTo = filter_input(INPUT_POST, 'user', FILTER_VALIDATE_INT)) {
            $user = $this->loadModelUser($userTo);
            $this->getComponentChat()->deleteUserChat($user->id);
            if (Yii::app()->request->isAjaxRequest) {
                echo '1';
            } else {
                $this->redirect(Yii::app()->createUrl('messageUser/chats'));
            }
        }
    }

    /**
     * 
     * отправка сообщения, загрузка истории
     * 
     */
    public function actionIndex() {

        // загрузка предыдуших сообщений
        if (Yii::app()->request->isAjaxRequest && filter_input(INPUT_GET, 'act') == 'loadHistory' && ($firstId = filter_input(INPUT_GET, 'firstId', FILTER_VALIDATE_INT))) {

            if (isset($_GET['user'])) {
                $o = $this->getComponentChat()->getMessagesUser($_GET['user'], $firstId);
            } elseif (isset($_GET['chat'])) {
                $o = $this->getComponentChat()->getMessagesChat($_GET['chat'], $firstId);
            } else {
                throw new CHttpException(400);    
            }
            echo json_encode($o);
            Yii::app()->end();
        }



        if (isset($_POST['message'])) {
            $message = trim($_POST['message']);

            if (isset($_POST['attachSend'])) {
                $attach = json_decode($_POST['attachSend']);
            } else {
                $attach = array();
            }

            if (isset($_POST['user_to_id'])) {
                $o = $this->getComponentChat()->sendMessageUser($_POST['user_to_id'], $message, $attach);
            } elseif (isset($_POST['chat_id'])) {
                $o = $this->getComponentChat()->sendMessageChat($_POST['chat_id'], $message, $attach);
            } else {
                throw new CHttpException(400);    
            }

            if (Yii::app()->request->isAjaxRequest) {
                echo json_encode($o);
                Yii::app()->end();
            } else {
                $this->refresh();
            }
        }
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
     * 
     * @return HotelChat
     * @throws CHttpException
     */
    public function loadModelChat($id) {
        $model = HotelChat::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404);
        }
        return $model;
    }

    /**
     * загрузка прикриплений
     */
    public function actionAttachment() {
        $file = CUploadedFile::getInstanceByName('file');
        echo $this->getComponentChat()->loadAttach($file);
    }

    /**
     * открытие картинки
     * @param type $id
     * @throws CHttpException
     */
    public function actionGetAttach($id) {
        $model = MessageAttachment::model()->findByPk($id, 'trash=false');
        if (!$model || !$model->validHash(filter_input(INPUT_GET, 'h'))) {
            throw new CHttpException(404);
        }
        $thumb = isset($_GET['thumb']);

        $eTag = 'attach-' . $model->id . ($thumb ? 'thumb' : '');

        header('ETag: ' . $eTag);
        header('Expires:');
        header('Pragma:');
        header('Cache-Control: public, max-age=2592000');


        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            if ($eTag == $_SERVER['HTTP_IF_NONE_MATCH']) {
                header('HTTP/1.1 304 Not Modified');
                exit();
            }
        }

        if ($thumb) {
            $file = $model->getFileThumb();
            $type = 'image/jpeg';
        } else {
            $file = $model->getFileBody();
            $types = MessageAttachment::typesFile();
            $type = $types[$model->type];
        }


        header('Content-Type: ' . $type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

}
