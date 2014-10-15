<?php
use Lightsoft\REST;
class MessageController extends ApiRESTController {

    private $_obj;

    /**
     * компонент для чата
     * @return MessageChat
     */
    public function getComponentChat() {
        if (!$this->_obj) {
            $this->_obj = new MessageChat(Server::obtainAuthId());
        }
        return $this->_obj;
    }

    public function filters() {
        return array(
            'postOnly + sendMessage,delteChat,read,event',
        );
    }

    /**
     * Список чатов пользователя
     * пример вывода {result:'success',data:{items=>[..массив элементов..]}}
     */
    public function actionUserChats() {
        $data = REST\Server::obtainRequestData();

        if (isset($data['limit']) && is_numeric($data['limit'])) {
            $limit = $data['limit'];
        } else {
            $limit = 20;
        }

        if (isset($data['offset']) && is_numeric($data['offset'])) {
            $offset = $data['offset'];
        } else {
            $offset = 0;
        }


        // для листания
        $sql = Yii::app()->db->getCommandBuilder()->applyLimit(MessageUser::getSqlChats(), $limit, $offset);

        $items = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':uid' => Server::obtainAuthId()
        ));

        $this->_responseSuccessView($items);
    }

    /**
     * Получение сообщений
     * примеры запроса {user:5} {user:5,firstId:56} {chat:11}  
     * пример ответа {result:'success',data:{"items":[{"message":"g","user_from_id":6176,"id":347,"date_create":"08.08.2014 03:05","time":1407452732,"attachment":[{type:"file",url:'/message/getAttach',url_thumb:'/url..'}],"read":true,"user_to_id":6176},{"message":"(smirk)","user_from_id":6176,"id":293,"date_create":"05.08.2014 14:22","time":1407234155,"attachment":[],"read":true,"user_to_id":6176}],"history":false}} 
     */
    public function actionMessages() {
        $data = REST\Server::obtainRequestData();
        $firstId = empty($data['firstId']) ? null : $data['firstId'];
        if (!empty($data['user'])) {
            $o = $this->getComponentChat()->getMessagesUser($data['user'], $firstId);
        } elseif (!empty($data['chat'])) {
            $o = $this->getComponentChat()->getMessagesChat($data['chat'], $firstId);
        } else {
            $this->_responseError("needed user or chat");
        }

        $this->_responseSuccessView($o);
    }

    /**
     * отправка сообщения
     * примеры запроса {user:5, message:'text', attachments:[{type:['id1','id2']]}
     * пример  ответа {result:'success',data:{id:6,attachment:[{type:"file",url:'/message/getAttach',url_thumb:'/url..'}]}}   
     */
    public function actionSendMessage() {
        $data = REST\Server::obtainRequestData();
        if (isset($data['message'])) {
            $attachments = isset($data['attachments']) ? $data['attachments'] : array();
            if (!empty($data['user'])) {
                $o = $this->getComponentChat()->sendMessageUser($data['user'], $data['message'], $attachments);
            } elseif (!empty($data['chat'])) {
                $o = $this->getComponentChat()->sendMessageUser($data['chat'], $data['message'], $attachments);
            } else {
                $this->_responseError("needed user or chat");
            }
            $this->_responseSuccessView($o);
        }

        $this->_responseError("no message");
    }

    /**
     * загрузка прикрипления
     * пример  ответа {result:'success',data{id:'id_file'}}
     */
    public function actionAttachment() {
        $file = CUploadedFile::getInstanceByName('file');
        $this->_responseSuccessView(array('id' => $this->getComponentChat()->loadAttach($file)));
    }

    /**
     * Удаление чата пользователя
     * примеры запроса {user:5}
     * пример  ответа {result:'success',data:{}} 
     */
    public function actionDelteChat() {
        $data = REST\Server::obtainRequestData();
        if ($userTo = filter_var($data['user'], FILTER_VALIDATE_INT)) {
            $this->getComponentChat()->deleteUserChat($userTo);
            $this->_responseSuccessView((object)array());
        }
        $this->_responseError("no user");
    }

    /**
     * пометка сообщений что прочитанно
     * примеры запроса {ids:[1,2,3,4]}
     * пример  ответа {result:'success',data:{}} 
     */
    public function actionRead() {
        $data = REST\Server::obtainRequestData();
        if (isset($data['ids']) && is_array($data['ids'])) {
            $this->getComponentChat()->read($data['ids']);
            $this->_responseSuccessView((object)array());
        } else {
            $this->_responseError("no ids");
        }
    }

    /**
     * сонхронизация (долгий запрос на события)
     * пример запроса {"type":"message_user","data":{"user_from_id":11970,"lastId":452}}
     * приммер ответа {result:'success',data:{"message":"(smirk)","user_from_id":6176,"id":293,"date_create":"05.08.2014 14:22","time":1407234155,"attachment":[],"read":true,"user_to_id":6176}} 
     * или если получено несколько сообщений: {result:'success',data:{items:[..масив с элементами сообщений как выще..],user_from_id:5}} 
     */
    public function actionEvent() {
        $data = REST\Server::obtainRequestData();
        if (isset($data['type'])) {
            if ($o = $this->getComponentChat()->event($data['type'], isset($data['data']) ? $data['data'] : array())) {
                $this->_responseSuccessView($o);
            }
        }

        $this->_responseError("type");
    }

}
