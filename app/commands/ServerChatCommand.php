<?php

/**
 * сокет сервер для связи пользователей в чате в реальном времяни
 * запуск: 
 *  php yiic serverchat start > & runtime/serverchat.txt &
 * 
 */
class ServerChatCommand extends CConsoleCommand {

    /**
     * Клиенты ждущие своего события
     * @var type 
     */
    private $listenClients = array();

    /**
     * массив с информацией о событие для $listenClients
     * @var type 
     */
    private $listenEvent = array();

    /**
     * выводить лог
     * @var type 
     */
    public $logging = false;

    /**
     * запуск сервера
     */
    public function actionStart() {

        header('Content-Type: text/plain;');
        error_reporting(E_ALL ^ E_WARNING);
        set_time_limit(0);
        ob_implicit_flush();

        try {
            // 'Создание сокета ... ';
            if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
                throw new Exception('socket_create() failed: ' . socket_strerror(socket_last_error()) . "\n");
            }
            // 'Bind socket ... ';

            if (($ret = socket_bind($sock, Event::ADDR, Event::PORT)) < 0) {
                throw new Exception('socket_bind() failed: ' . socket_strerror(socket_last_error()) . "\n");
            }

            //'Listen socket ';

            if (($ret = socket_listen($sock, SOMAXCONN)) < 0) {
                throw new Exception('socket_listen() failed: ' . socket_strerror(socket_last_error()) . "\n");
            }

            echo 'server is running' . PHP_EOL;




            while (true) {
                $errorClients = $this->listenClients;
                $read = $this->listenClients;
                $read[] = $sock;
                $num_changed = socket_select($read, $NULL = null, $errorClients, null);


                /* Изменилось что-нибудь? */
                if ($num_changed) {

                    /* Изменился ли главный сокет (новое подключение) */
                    if (($i = array_search($sock, $read)) !== false) {
                        $new_sockets = socket_accept($sock);
                        $ans = socket_read($new_sockets, 1024);
                        if ($ans) {
                            if ($ans == 'stop') {
                                break;
                            }
                            $ans2 = unserialize($ans);
                            $this->log($ans2['type'] . ':' . $ans2['event']);
                            // пришло событие
                            if ($ans2['type'] == 'event') {

                                foreach ($this->listenEvent as $i => $value) {
                                    if (Event::object()->compare($value, $ans2)) {
                                        //дождался своего события
                                        @socket_write($this->listenClients[$i], $ans);
                                        //socket_shutdown($this->listenClients[$i]);
                                    }
                                }
                            } elseif ($ans2['type'] == 'listen') {

                                //новый подписчик
                                $i = count($this->listenClients);
                                $this->listenClients[$i] = $new_sockets;
                                $this->listenEvent[$i] = $ans2;
                            }
                        }
                        array_splice($read, $i, 1);
                    }


                    //если что то написал клиент подписчик значит он отключаеться
                    $errorClients = array_merge($errorClients, $read);


                    //удаление отключившихся клиентов
                    foreach ($errorClients as $clientError) {
                        $this->deleteListenClients($clientError);
                    }
                }
            }
        } catch (Exception $e) {
            echo "\nError: " . $e->getMessage();
        }

        if (isset($sock)) {
            echo 'Close socket ... ';
            socket_close($sock);
            echo 'OK' . PHP_EOL;
        }
    }

    private function deleteListenClients($sock) {
        if (($i = array_search($sock, $this->listenClients)) !== false) {
            @socket_shutdown($sock);
            $this->log('remove listen:' . $this->listenEvent[$i]['event']);
            array_splice($this->listenClients, $i, 1);
            array_splice($this->listenEvent, $i, 1);
        }
    }

    public function log($message) {
        if ($this->logging) {
            echo $message . PHP_EOL;
        }
    }
    
    /**
     * остановка сервера
     * @throws Exception
     */
    public function actionStop() {
        if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
            throw new Exception('socket_create() failed: ' . socket_strerror(socket_last_error()) . "\n");
        }

        $result = socket_connect($socket, Event::ADDR, Event::PORT);
        if ($result === false) {
            throw new Exception('socket_connect() failed: ' . socket_strerror(socket_last_error()) . "\n");
        }
        $msg = 'stop';
        socket_write($socket, $msg, strlen($msg));
        socket_shutdown($socket);
        socket_close($socket);
    }

}
