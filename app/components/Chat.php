<?php

/**
 * виджет чата
 */
class Chat extends CWidget {

    
    /**
     *
     * @var User
     */
    public $user_to;
    
    /**
     *
     * @var HotelChat 
     */
    public $chat;

    /**
     * количество сообщений загрузить из истории
     * @var int
     */
    const ITEMS_COUNT = 20;

    public function run() {

        if ($this->chat) {
            $lastMessage =  MessageHotelChat::model()->findAll(array(
                'limit' => self::ITEMS_COUNT + 1,
                'condition'=>'chat_id='.$this->chat->id,
                'order' => 'id desc',
            ));
            $type = 'chat_hotel';
        } else {
            $lastMessage = MessageUser::model()->setCriteriaInterlocutors($this->user_to->id, Yii::app()->user->id)->findAll(array(
                'limit' => self::ITEMS_COUNT + 1,
                'order' => 'id desc',
            ));
            $type = 'chat_user';
        }

        $isHistory = count($lastMessage) > self::ITEMS_COUNT;
        if ($isHistory) {
            array_pop($lastMessage);
        }

        $lastMessage = array_reverse($lastMessage);

        $id = $this->getId();

        $this->render('chat/'.$type, array('userTo' => $this->user_to, 'chat'=>  $this->chat, 'lastMessage' => $lastMessage, 'id' => $id));


        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile('/css/b-chat.css');
        $cs->registerScriptFile('/js/event.js');
        $cs->registerScriptFile('/js/chat.js');
        
        
      //  mCustomScrollbar("scrollTo","bottom");


        $cs->registerScriptFile('/js/jquery.json-2.4.min.js');

        $users = array(Yii::app()->user->id => User::getData(Yii::app()->controller->getUser()));

        if ($this->user_to) {
            $users[$this->user_to->id] = User::getData($this->user_to);
        }

        $lastMessageData = array();
        if ($lastMessage) {
            foreach ($lastMessage as $value) {
                if (!empty($value->user_from_id) && !(isset($users[$value->user_from_id]))) {
                    $users[$value->user_from_id] = User::getData($value->user_from);
                }
                $lastMessageData[] = self::getDataItem($value);
            }
        }

        $js = 'window.smiles = new SmileClass('.CJavaScript::encode(SmileHelper::$smiles).');'. PHP_EOL;

        if ($users) {

            foreach ($users as $userData) {
                $js.='window.usersStorage.add(' . CJavaScript::encode($userData) . ');' . PHP_EOL;
            }
        }
        
        
        
        $jsMore = PHP_EOL . 'window.siteEvent.timeout=' . (Event::TIMEOUT + 10) . ';';
        
        
        

        $cs->registerScript($id, $js . '$("#' . $id . '").chat(' . CJavaScript::encode(array(
                    'user_id' => Yii::app()->user->id,
                    'chat_id'=>  $this->chat?$this->chat->id:null,
                    'user_to_id' =>  $this->user_to?$this->user_to->id:null,
                    'messages' => $lastMessageData,
                    'history' => $isHistory,
                    'url'=>Yii::app()->createUrl('message/index',$this->user_to?array('user'=>$this->user_to->id):array('chat'=>$this->chat->id))
                )) . '); ' . $jsMore);
    }

    public static function getDataItem($modelMessage) {
        $dataMsg = array(
            'message' => $modelMessage->getMessageEncode(),
            'user_from_id' => $modelMessage->user_from_id,
            'id' => $modelMessage->id,
            'date_create' => DateHelper::getDateFormat2Post($modelMessage->date_create),
            'time' =>  strtotime($modelMessage->date_create), 
            'attachment'=>  self::getAttachmentsData($modelMessage),
        );
        
        if (isset($modelMessage->read))
            $dataMsg['read'] = $modelMessage->read;
        
        if (isset($modelMessage->user_to_id))
            $dataMsg['user_to_id'] = $modelMessage->user_to_id;
        if (isset($modelMessage->chat_id))
            $dataMsg['chat_id'] = $modelMessage->chat_id;
        return $dataMsg;
    }
    
    
    
     public static function getAttachmentsData($modelMessage) {
        $data = array();
        $models = $modelMessage->getAttachments();
       
        foreach ($models as $model) {
            $d = array();
            if ($model->type == MessageAttachment::TYPE_MAP) {
                $d['type'] = 'map';
                $d['body'] = $model->getDbConnection()->createCommand()->select('body')->from($model->tableName())->where('id=' . $model->id)->queryScalar();
            } else {
                $d['type'] = 'file';
                $d['url'] = $model->getUrlFile();
                $d['url_thumb'] = $model->getUrlThumb();
            }
            $data[] = $d;
        }
        return $data;
    }

}
