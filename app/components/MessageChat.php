<?php

/**
 * операции с сообщениями и чатом
 */
class MessageChat {

    private $user_id;

    public function __construct($userId) {
        $this->user_id = $userId;
    }

    /**
     * чтение сообшений MessageUser
     * @param array $ids массив id проситанных сообщений
     * @return boolean
     */
    public function read(array $ids) {
        if ($ids) {
            $condition = 'id in (' . implode(',', $ids) . ') and user_to_id=' . $this->user_id;
            $models = MessageUser::model()->findAll(array('condition' => $condition, 'select' => 't.id,t.user_from_id'));
            $e = array();
            foreach ($models as $value) {
                $e[$value->user_from_id][] = $value->id;
            }
            MessageUser::model()->updateAll(array('read' => true), $condition);
            foreach ($e as $user_from_id => $value) {
                Event::object()->send(Event::EVENT_READ_MESSAGE, array(
                    'user_from_id' => $user_from_id,
                    'user_to_id' => $this->user_id,
                    'ids' => implode(',', $value)));
            }
            return true;
        }
    }
    
    /**
     * удаление чата с пользователем (всех сообщений в чате)
     * @param type $userId
     */
    public function deleteUserChat($userId)
    {
        $attr=array();
        if ($this->user_id<$userId)
        {
            $attr['trash_user1']=true;
        } else {
              $attr['trash_user2']=true;
        }
        
        $attr['read']=true;
        
        MessageUser::model()->updateAll($attr,MessageUser::getConditionChat($userId, $this->user_id));
    }

    /**
     * ожидание события 
     * @param type $type тип события, один из Event::EVENT_CHAT, Event::EVENT_MESSAGE, Event::EVENT_READ_MESSAGE
     * @param array $data масив с данными lastId, user_from_id, chat_id
     * @return array если наступит событие ответ в виде масива,  которые содержид свойства сообщения или элемент items с масивом сообщений +chat_id или user_from_id
     * @throws CHttpException
     */
    public function event($type, array $data = array()) {
        $events = array(Event::EVENT_CHAT, Event::EVENT_MESSAGE, Event::EVENT_READ_MESSAGE);
        if (in_array($type, $events)) {
            $expCondition = '';
            switch ($type) {
                case Event::EVENT_MESSAGE:
                    if (isset($data['lastId'])) {
                        $lastId = (int) $data['lastId'];
                        $models = MessageUser::model()->setCriteriaInterlocutors($this->user_id, $data['user_from_id'],  $this->user_id)->findAll(array(
                            'order' => 'id desc',
                            'condition' => 'id>' . $lastId
                        ));
                        if ($models) {
                            $dataAns = array();
                            foreach ($models as $value) {
                                $dataAns[] = array_merge($value->getAttributes(), array('attachment' => Chat::getAttachmentsData($value)));
                            }
                            return (array('items' => $dataAns, 'user_from_id' => $data['user_from_id']));
                        }
                    }

                    $expCondition = '$user_to_id==' . $this->user_id;
                    if (isset($data['user_from_id']) && is_numeric($data['user_from_id'])) {
                        $expCondition.=' && $user_from_id==' . (int) $data['user_from_id'];
                    }
                    break;
                default:
                case Event::EVENT_READ_MESSAGE:
                    if (isset($data['user_to_id']) && is_numeric($data['user_to_id'])) {
                        $expCondition = '$user_to_id==' . (int) $data['user_to_id'] . ' and $user_from_id=' . $this->user_id;
                    } else {
                        throw new CHttpException(400);
                    }

                    break;

                case Event::EVENT_CHAT:


                    if (isset($data['chat_id']) && is_numeric($data['chat_id'])) {
                        $expCondition = '$chat_id==' . (int) $data['chat_id'];
                    } else {
                        throw new CHttpException(400);
                    }

                    if (isset($data['lastId'])) {
                        $lastId = (int) $data['lastId'];
                        $models = MessageHotelChat::model()->findAll(array(
                            'order' => 'id desc',
                            'condition' => 'id>' . $lastId . ' and chat_id=' . $data['chat_id']
                        ));
                        if ($models) {
                            $dataAns = array();
                            foreach ($models as $value) {
                                $dataAns[] = array_merge($value->getAttributes(), array('attachment' => Chat::getAttachmentsData($value)));
                            }
                            return array('items' => $dataAns, 'chat_id' => $data['chat_id']);
                        }
                    }

                    break;
                    throw new CHttpException(400);
                    break;
            }

            if (($ans = Event::object()->listen($type, $expCondition))) {
                $dataAns = $ans['data'];

                if ($type == Event::EVENT_MESSAGE) {
                    $messageModel = MessageUser::model()->findByPk($dataAns['id']);
                    $dataAns = array_merge($dataAns, Chat::getDataItem($messageModel));
                } elseif ($type == Event::EVENT_CHAT) {
                    $messageModel = MessageHotelChat::model()->findByPk($dataAns['id']);
                    $dataAns = array_merge($dataAns, Chat::getDataItem($messageModel));
                }

                return $dataAns;
            }
        }
    }

    /**
     * получение сообщений между пользователем
     * @param int $userId пользователель с которм диалог
     * @param type $firstId для загрузки истории ид сообщения от которого загрузить имторию
     * @return array array('items' => $items, 'history' => есть ли еще там сообщения)
     * @throws CHttpException
     */
    public function getMessagesUser($userId, $firstId = null) {
        return $this->getMessages($this->loadModelUser($userId), $firstId);
    }

    /**
     * получение сообщений между пользователем
     * @param int $idChat ид чата
     * @param type $firstId для загрузки истории ид сообщения от которого загрузить имторию
     * @return array array('items' => $items, 'history' => есть ли еще там сообщения)
     * @throws CHttpException
     */
    public function getMessagesChat($chatId, $firstId = null) {

        return $this->getMessages($this->loadModelChat($chatId), $firstId);
    }

    /**
     * отправка сообщения пользователю
     * @param ineger $userId  ИД пользователя
     * @param type $message сообщения
     * @param type $attachment массив приекриплений, в котором ключи тип прикрипления (пока только file) а в значении масив ид полученых при загрузке<br> пример  {"file":["108_7a49cdb3e511210c3b5620306389b4eb"]}
     * @return array [id => ид добавленного сообщения, attachment => масив с добавленными прикриплениями]
     */
    public function sendMessageUser($userId, $message = '', $attachment = array()) {
        return $this->sendMessage($this->loadModelUser($userId), $message, $attachment);
    }

    /**
     * отправка сообщения в чат отеля
     * @param ineger $chatId  ИД чата
     * @param type $message сообщения
     * @param type $attachment массив приекриплений, в котором ключи тип прикрипления (пока только file) а в значении масив ид полученых при загрузке<br> пример  {"file":["108_7a49cdb3e511210c3b5620306389b4eb"]}
     * @return array [id => ид добавленного сообщения, attachment => масив с добавленными прикриплениями]
     */
    public function sendMessageChat($chatId, $message = '', $attachment = array()) {
        return $this->sendMessage($this->loadModelChat($chatId), $message, $attachment);
    }

    /**
     *  $file = CUploadedFile::getInstanceByName('file'); <br> $obj->loadAttach($file);
     * @param CUploadedFile $file
     * @return string ид-строка загруженного прикрипления
     */
    public function loadAttach($file) {
        if (($type = array_search($file->getType(), MessageAttachment::typesFile())) !== false) {
            $model = new MessageAttachment();
            $model->body = new CDbExpression(":body", array(":body" => fopen($file->getTempName(), "rb")));
            $model->type = $type;
            $model->trash = true;
            if ($model->save()) {
                return $model->id . '_' . MessageAttachment::getHashById($model->id);
            } else {
                header("Status: 400");
                print_r($model->getErrors());
                exit;
            }
        }
        return false;
    }

    private function getMessages($model, $firstId = null) {
        if (filter_var($firstId, FILTER_VALIDATE_INT)) {
            $condition = 't.id<' . $firstId;
        } else {
            $condition = '';
        }

        if ($model instanceof User) {
            $lastMessage = MessageUser::model()->setCriteriaInterlocutors($model->id, $this->user_id, $this->user_id)->findAll(array(
                'limit' => Chat::ITEMS_COUNT + 1,
                'order' => 't.id desc',
                'condition' => $condition
            ));
        } else {
            $lastMessage = MessageHotelChat::model()->findAll(array(
                'limit' => Chat::ITEMS_COUNT + 1,
                'order' => 't.id desc',
                'condition' => 't.chat_id=' . $model->id . ( $condition ? ' and ' . $condition : '')
            ));
        }
        $isHistory = count($lastMessage) > Chat::ITEMS_COUNT;
        if ($isHistory) {
            array_pop($lastMessage);
        }
        $items = array();
        foreach ($lastMessage as $value) {
            $items[] = Chat::getDataItem($value);
        }
        return array('items' => $items, 'history' => $isHistory);
    }

    private function sendMessage($model, $message = '', $attachment = array()) {
        $message = trim($message);

        if (!$message && !$attachment) {
            throw new CHttpException(400);
        }

        if ($model instanceof User) {
            $modelMsg = new MessageUser();
            $modelMsg->newAttachment = $attachment;
            $modelMsg->message = $message;
            $modelMsg->user_from_id = $this->user_id;
            $modelMsg->user_to_id = $model->id;
            $modelMsg->save();
        } else {
            $modelMsg = new MessageHotelChat();
            $modelMsg->newAttachment = $attachment;
            $modelMsg->message = $message;
            $modelMsg->user_from_id = $this->user_id;
            $modelMsg->chat_id = $model->id;
            $modelMsg->save();
        }
        return array('id' => $modelMsg->id, 'attachment' => Chat::getAttachmentsData($modelMsg));
    }

    /**
     * 
     * @return User
     * @throws CHttpException
     */
    private function loadModelUser($id) {
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
    private function loadModelChat($id) {
        $model = HotelChat::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404);
        }
        return $model;
    }

}
