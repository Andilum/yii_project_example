<?php

/**
 * Description of Event
 */
class Event {

    const EVENT_MESSAGE = 'message_user';
    const EVENT_CHAT = 'message_chat';
    const EVENT_READ_MESSAGE = 'message_read';
    
    const ADDR = '127.0.0.1';
    const PORT = 8101;

    /*
     * Количество секунд прослушивания события
     */
    const TIMEOUT = 20;

    private $_socket;
    private static $_object;

    /**
     * 
     * @return Event
     */
    public static function object() {
        if (!self::$_object) {
            self::$_object = new self();
        }
        return self::$_object;
    }

    /**
     * Отправка события (сообщение в чат, или пользователю)
     * @param string $event название события
     * @param array $data данные события
     */
    public function send($event, $data = array()) {
        $socket = $this->getSocket();
        $msg = serialize(array('type' => 'event', 'event' => $event, 'data' => $data));
        socket_write($socket, $msg, strlen($msg));
    }

    /**
     * Сравнение слушашего события и события, использоеться только на сокет сервере
     * @param array $eventListen  подписка на событие - array('event'=>'message_chat','expCondition'=>'$user_id=5;')
     * @param array $event событие - array('event'=>'message_chat','data'=>['user_id'=>5])
     */
    public function compare($eventListen, $event) {
        return $eventListen['event'] === $event['event'] && (empty($eventListen['expCondition']) || $this->evaluateExpression($eventListen['expCondition'], $event['data']));
    }

    /**
     * подписка на событие
     * @param string $event название события
     * @param string $expCondition строка php выражение для сравнение данных
     */
    public function listen($event, $expCondition) {
        Yii::app()->session->close();
        set_time_limit(self::TIMEOUT + 5);
        $socket = $this->getSocket();
        $timeout = array('sec' => self::TIMEOUT, 'usec' => 0);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, $timeout);
        $msg = serialize(array('type' => 'listen', 'event' => $event, 'expCondition' => $expCondition));
        socket_write($socket, $msg, strlen($msg));
        $answer = @socket_read($socket, 1024);
        if ($answer) {
            $answer = unserialize($answer);
        } else {
            $msg = 'close';
            socket_write($socket, $msg, strlen($msg));
        }
        return $answer;
    }

    /**
     * 
     * @return resource <b>socket_create</b> returns a socket resource on success
     * @throws Exception
     */
    private function getSocket() {
        if (!$this->_socket) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket < 0) {
                throw new Exception('socket_create() failed: ' . socket_strerror(socket_last_error()) . "\n");
            }

            $result = socket_connect($socket, self::ADDR, self::PORT);
            if ($result === false) {
                throw new Exception('socket_connect() failed: ' . socket_strerror(socket_last_error()) . "\n");
            }
            $this->_socket = $socket;
        }
        return $this->_socket;
    }

    public function __destruct() {
        $this->socketClose();
    }

    public function socketClose() {
        if ($this->_socket) {
            socket_close($this->_socket);
            $this->_socket = null;
        }
    }

    private function evaluateExpression($_expression_, $_data_ = array()) {
        if (is_string($_expression_)) {
            extract($_data_);
            return eval('return ' . $_expression_ . ';');
        } else {
            $_data_[] = $this;
            return call_user_func_array($_expression_, $_data_);
        }
    }

}
