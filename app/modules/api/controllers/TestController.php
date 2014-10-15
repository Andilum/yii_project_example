<?php

class TestController extends ControllerAdmin {

    public $layout = '//layouts/column1';
    public $result = array();
    public $token;
    public $host;

    const LOG_HTML = 'html';
    const LOG_INFO = 'info';
    const LOG_WARNING = 'warning';
    const LOG_ERROR = 'error';
    const LOG_SUCCESS = 'success';

    /**
     * список тестов, 
     * @return array массив с данными всех тестов
     */
    public function getTests() {
        return array(
            'auth_get_token' => array('label' => 'Получение токена по логину и паролю', 'token' => false, 'field' => array('login', 'password')),
            'auth_get' => array('label' => 'Получение данных авторизированного пользователя', 'token' => true),
            'auth_get_byid' => array('label' => 'Получение данных пользователя по id', 'token' => true, 'field' => array('id')),
            'user_by_nick' => array('label' => 'получение данных пользователя по нику api/user/<nick:\w+>', 'token' => true, 'field' => array('nick')),
            'user_avatar_by_nick' => array('label' => 'аватор пользователя по нику', 'token' => true, 'field' => array('nick')),
            'user_feed' => array('label' => 'получение списка фидов по юзеру(принимает параметр offset_id, limit, order(“asc” или “desc”))',
                'token' => true,
                'field' => array('nick', array('name' => 'offset_id', 'required' => false), array('name' => 'limit', 'required' => false), array('name' => 'order', 'required' => false, 'help' => '“asc” или “desc”'),)),
            'app_post' => array('label' => 'добавление поста , принимает ассоциативный массив с атрибутами поста', 'token' => true),
            'alloc_feed' => array('label' => 'получение списка фидов по отелю', 'token' => true, 'field' => array('allocation_id', array('name' => 'offset_id', 'required' => false), array('name' => 'limit', 'required' => false), array('name' => 'order', 'required' => false, 'help' => '“asc” или “desc”'))),
            'feed' => array('label' => 'получение списка фидов', 'token' => true, 'field' => array(array('name' => 'offset_id', 'required' => false), array('name' => 'limit', 'required' => false), array('name' => 'order', 'required' => false, 'help' => '“asc” или “desc”'))),
            'feed_by_id' => array('label' => 'получение фида', 'token' => true, 'field' => array(array('name' => 'id', 'required' => true, 'help' => 'ид поста'))),
            'add_feed' => array('label' => 'добавление поста', 'token' => true),
            'put_feed' => array('label' => 'изменение поста', 'token' => true),
            'delete_feed' => array('label' => 'удаление поста', 'token' => true),
            'feed_comment' => array('label' => 'получение списка комментов к фиду', 'token' => true,
                'field' => array(
                    array('name' => 'id', 'required' => true, 'help' => 'id фида'),
                    array('name' => 'offset_id', 'required' => false),
                    array('name' => 'limit', 'required' => false),
                    array('name' => 'order', 'required' => false, 'help' => '“asc” или “desc”'))),
            'feed_get_like' => array('label' => 'получение списка лайкнувших пост', 'token' => true, 'field' => array(
                    array('name' => 'id', 'required' => true, 'help' => 'id поста'))),
            'feed_like' => array('label' => 'лайк поста', 'token' => true, 'field' => array(
                    array('name' => 'id', 'required' => true, 'help' => 'id поста'))),
            'feed_like_delete' => array('label' => 'анлайк поста', 'token' => true, 'field' => array(
                    array('name' => 'id', 'required' => true, 'help' => 'id поста'))),
            'comment_add' => array('label' => 'добавление комментария', 'token' => true, 'field' => array(
                    array('name' => 'post_id', 'required' => false, 'help' => 'id поста'))),
            'comment_put' => array('label' => 'изменение комментарий', 'token' => true),
            'comment_delete' => array('label' => 'удаление комментария', 'token' => true),
        );
    }

    /**
     * получить токен
     * @param type $login
     * @param type $passord
     */
    public function authApi($login, $passord) {
        $t = $this->request('/api/auth', 'POST', json_encode(array('data' => array('email' => $login, 'password' => $passord))), false);

        $t = json_decode($t, true);

        if (isset($t['data']['token'])) {
            $this->token = $t['data']['token'];
            $this->log('получен токен: ' . $this->token);
            return $this->token;
        }
        return false;
    }

    public function log($message, $type = self::LOG_INFO) {
        $this->result[] = array($type, $message);
    }

    /**
     * запуск тестов
     * @param type $tests
     * @return int количество успешно выполненых тестов
     */
    public function runTests($tests = array()) {
        set_time_limit(3600);

        $dataTest = $this->getTests();
        if (empty($tests)) {
            $tests = array_keys($dataTest);
        }
        $ok = 0;

        foreach ($tests as $key) {
            $test = $dataTest[$key];
            if (method_exists($this, 'test_' . $key)) {
                $this->log('тестирование "' . $test['label'] . '"..', self::LOG_INFO);
                $data = array();
                if (isset($test['field'])) {
                    foreach ($test['field'] as $field) {
                        $nameF = is_array($field) ? $field['name'] : $field;
                        $data[$nameF] = isset($_POST[$key][$nameF]) ? $_POST[$key][$nameF] : '';
                    }
                }

                if ($this->{'test_' . $key}($data)) {
                    $this->log('OK', self::LOG_SUCCESS);
                    $ok++;
                } else {
                    $this->log('ошибка при тестировании', self::LOG_ERROR);
                }

                $this->log('<hr>', self::LOG_HTML);
            }
        }
        return $ok;
    }

    public function actionIndex() {

        if (!empty($_POST['tests'])) {
            $tests = $_POST['tests'];

            $this->host = empty($_POST['host']) ? Yii::app()->request->hostInfo : $_POST['host'];

            if (!empty($_POST['token'])) {
                $this->token = $_POST['token'];
            } else {
                $tokenNeeded = false;
                foreach ($tests as $value) {
                    if (@$value['token']) {
                        $tokenNeeded = true;
                        break;
                    }
                }
                if ($tokenNeeded) {

                    if (isset($_POST['login'], $_POST['password'])) {
                        if (!$_POST['login'])
                            $this->errorShow('пустой логин');
                        $this->authApi($_POST['login'], $_POST['password']);
                    } else {
                        $this->errorShow('укажите логин и пароль для получения токена');
                    }

                    if (!$this->token) {
                        $this->errorShow('не удалось получить токен');
                    }
                }
            }
            $okCount = $this->runTests($tests);

            $errors = 0;
            $warning = 0;

            foreach ($this->result as $value) {
                if ($value[0] == self::LOG_WARNING)
                    $warning++;
                elseif ($value[0] == self::LOG_ERROR)
                    $errors++;
            }

            $this->render('result', array('errors' => $errors, 'warning' => $warning, 'result' => $this->result, 'okCount' => $okCount, 'allCount' => count($tests)));
        } else {
            $dataTest = $this->getTests();
            $this->render('index', array('dataTest' => $dataTest));
        }
    }

    public function errorShow($message) {
        Yii::app()->user->setFlash('error', $message);
        $this->refresh();
    }

    /**
     * выполнение запроса к api
     * @param string $url например /api/auth
     * @param string $method GET POST DELETE PUT..
     * @param array||sring $data данные POST или GET в виде массива
     * @param boolen $token нужно ли передавать токен
     * @return string ответ
     */
    public function request($url, $method = 'GET', $data = '', $token = false) {
        $method = strtoupper($method);

        if (is_array($data)) {
            $data = http_build_query($data);
        }

        $ch = curl_init();

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            if ($method != 'GET') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }
        }


        if ($data) {
            if ($method == 'GET') {
                $url.='?' . $data;
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }




        $host = $this->host; //Yii::app()->request->hostInfo
        curl_setopt($ch, CURLOPT_URL, $host . $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        if ($token) {

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'X-HI-AUTH-TOKEN: ' . $this->token
            ));
        }

        $result = curl_exec($ch);

        if ($result === false) {
            $this->log(curl_error($ch), self::LOG_ERROR);
            return false;
        }

        $statusString = substr($result, 0, strpos($result, "\n") - 1);
        $status = substr($statusString, 9, 3);
        if ($status !== '200') {
            $this->log($method . ': ' . $url . ' status:' . $status, self::LOG_WARNING);
        }

        $result = substr($result, strpos($result, "\n\r\n") + 3);

        return $result;
    }

    //тесты

    public function test_auth_get_token($data) {

        if (empty($data['login'])) {
            $this->log('не указан логин', self::LOG_WARNING);
        } else {
            $r = $this->request('/api/auth', 'POST', json_encode(array('data' => array('email' => $data['login'], 'password' => $data['password']))), false);
            $t = json_decode($r, true);

            if (isset($t['data']['token'])) {
                return true;
            } else {
                $this->log('токен не получен, ответ:' . $r, self::LOG_ERROR);
            }
        }
        return false;
    }

    public function test_auth_get($data) {
        $r = $this->request('/api/auth', 'GET', '', true);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success') {
            return true;
        } else {
            $this->log('ошибка, ответ:' . $r, self::LOG_ERROR);
        }
        return false;
    }

    public function test_auth_get_byid($data) {
        if (!empty($data['id'])) {
            $r = $this->request('/api/auth/' . $data['id'], 'GET', '', true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан id', self::LOG_WARNING);
        }
        return false;
    }

    public function test_user_by_nick($data) {
        if (!empty($data['nick'])) {
            $r = $this->request('/api/user/' . $data['nick'], 'GET', '', true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан nick', self::LOG_WARNING);
        }
        return false;
    }

    public function test_user_avatar_by_nick($data) {
        if (!empty($data['nick'])) {
            $r = $this->request('/api/user/' . $data['nick'] . '/avatar', 'GET', '', true);
            $this->log('ответ:' . substr($r, 0,3).'...');
            return true;
        } else {
            $this->log('не указан nick', self::LOG_WARNING);
        }
        return false;
    }

    public function test_user_feed($data) {
        if (!empty($data['nick'])) {
            $nick = $data['nick'];
            unset($data['nick']);
            $r = $this->request('/api/user/' . $nick . '/feed', 'GET', $data, true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан nick', self::LOG_WARNING);
        }
        return false;
    }

    public function test_app_post($data) {
        $r = $this->request('/api/users/feed', 'POST', json_encode(array('data' => array('name' => 'test', 'text' => 'test'))), true);
        $this->log('ответ:' . $r);
        $t = json_decode($r, true);

        if (isset($t['result']) && $t['result'] == 'success' && $t['data']) {
            if (ApiPost::model()->deleteByPk($t['data'])) {
                return true;
            } else {
                $this->log('созданный пост не удален #' . $t['data'], self::LOG_WARNING);
            }
        }
        return false;
    }

    /**
     * 'pattern'=>'api/allocation/<allocation_id:\d+>/feed','verb' => 'GET' - получение списка фидов по отелю(принимает параметр offset_id, limit, order(“asc” или “desc”))
     */
    public function test_alloc_feed($data) {
        if (!empty($data['allocation_id'])) {
            $allocation_id = $data['allocation_id'];
            unset($data['allocation_id']);
            $r = $this->request('/api/allocation/' . $allocation_id . '/feed', 'GET', $data, true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан allocation_id', self::LOG_WARNING);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed','verb' => 'GET' - получение списка фидов(принимает параметры offset_id, limit, order(“asc” или “desc”))
     */
    public function test_feed($data) {
        $r = $this->request('/api/feed', 'GET', $data, true);
        $this->log('ответ:' . $r);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success') {
            return true;
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed/<id:\d+>','verb' => 'GET' - получение фида
     */
    public function test_feed_by_id($data) {
        $r = $this->request('/api/feed/' . $data['id'], 'GET', $data, true);
        $this->log('ответ:' . $r);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success') {
            return true;
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed','verb' => 'POST' - добавление поста
     */
    public function test_add_feed($data) {
        $r = $this->request('/api/feed', 'POST', json_encode(array('data' => array('name' => 'test', 'text' => 'test'))), true);
        $this->log('ответ:' . $r);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success' && $t['data']) {
            if (ApiPost::model()->deleteByPk($t['data'])) {
                return true;
            } else {
                $this->log('созданный пост не удален #' . $t['data'], self::LOG_WARNING);
            }
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed/<id:\d+>','verb' => 'PUT' - изменение поста(может только владелец)
     */
    public function test_put_feed($data) {
        $r = $this->request('/api/feed', 'POST', json_encode(array('data' => array('name' => 'test', 'text' => 'test'))), true);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success' && $t['data']) {
            $this->log('добавлен пост :' . $t['data']);
            $id = $t['data'];
            $r2 = $this->request('/api/feed/' . $id, 'PUT', json_encode(array('data' => array('name' => 'test2', 'text' => 'test2'))), true);
            $t2 = json_decode($r2, true);

            if (isset($t2['result']) && $t2['result'] == 'success') {
                $this->log($r2);
                $result = true;
            } else {
                $this->log('ошибка при изменении:' . $r2, self::LOG_ERROR);
                $result = false;
            }
            ApiPost::model()->deleteByPk($id);
            return $result;
        } else {
            $this->log('не удалось добавить пост, ответ:' . $r, self::LOG_ERROR);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed/<id:\d+>','verb' => 'DELETE' - удаление поста(может только владелец)
     */
    public function test_delete_feed($data) {
        $r = $this->request('/api/feed', 'POST', json_encode(array('data' => array('name' => 'test', 'text' => 'test'))), true);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success' && $t['data']) {
            $this->log('добавлен пост :' . $t['data']);
            $id = $t['data'];
            $r2 = $this->request('/api/feed/' . $id, 'DELETE', '', true);
            $t2 = json_decode($r2, true);
            if (isset($t2['result']) && $t2['result'] == 'success') {
                $this->log($r2);
                $result = true;
            } else {
                $this->log('ошибка при удалении:' . $r2, self::LOG_ERROR);
                $result = false;
            }
            ApiPost::model()->deleteByPk($id);
            return $result;
        } else {
            $this->log('не удалось добавить пост, ответ:' . $r, self::LOG_ERROR);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed/<id:\d+>/comment','verb' => 'GET' - получение списка комментов к фиду(принимает параметр offset_id, limit, order(“asc” или “desc”))
     * @return boolean
     */
    public function test_feed_comment($data) {
        if (!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $r = $this->request('/api/feed/' . $id . '/comment', 'GET', $data, true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан id', self::LOG_WARNING);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed/<id:\d+>/like','verb' => 'GET' - получение списка лайкнувших пост
     * @return boolean
     */
    public function test_feed_get_like($data) {
        if (!empty($data['id'])) {

            $r = $this->request('/api/feed/' . $data['id'] . '/like', 'GET', '', true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан id', self::LOG_WARNING);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed/<id:\d+>/like','verb' => 'POST' - лайк поста
     * @return boolean
     */
    public function test_feed_like($data) {
        if (!empty($data['id'])) {
            $r = $this->request('/api/feed/' . $data['id'] . '/like', 'POST', '', true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан id', self::LOG_WARNING);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/feed/<id:\d+>/like','verb' => 'DELETE’ - анлайк поста
     * @return boolean
     */
    public function test_feed_like_delete($data) {
        if (!empty($data['id'])) {
            $r = $this->request('/api/feed/' . $data['id'] . '/like', 'DELETE', '', true);
            $this->log('ответ:' . $r);
            $t = json_decode($r, true);
            if (isset($t['result']) && $t['result'] == 'success') {
                return true;
            }
        } else {
            $this->log('не указан id', self::LOG_WARNING);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/comment','verb' => 'POST' - добавление комментария
     * @return boolean
     */
    public function test_comment_add($data) {
        if (empty($data['post_id'])) {
            $data['post_id'] = 1;
        }
        $data['test'] = 'test';
        $r = $this->request('/api/comment', 'POST', json_encode(array('data' => $data)), true);
        $this->log('ответ:' . $r);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success' && $t['data']) {
            if (ApiComment::model()->deleteByPk($t['data'])) {
                return true;
            } else {
                $this->log('созданный комментарий не удален #' . $t['data'], self::LOG_WARNING);
            }
        }
        return false;
    }

    /**
     * 'pattern'=>'api/comment/<id:\d+>','verb' => 'PUT' - изменение комментария(может только владелец)
     * @return boolean
     */
    public function test_comment_put($data) {
        if (empty($data['post_id'])) {
            $data['post_id'] = 1;
        }
        $data['test'] = 'test';
        $r = $this->request('/api/comment', 'POST', json_encode(array('data' => $data)), true);
        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success' && $t['data']) {
            $this->log('добавлен комментарий :' . $t['data']);
            $id = $t['data'];
            $r2 = $this->request('/api/comment/' . $id, 'PUT', json_encode(array('data' => array('text' => 'test2', 'post_id' => 1))), true);
            $t2 = json_decode($r2, true);
            if (isset($t2['result']) && $t2['result'] == 'success') {
                $this->log($r2);
                $result = true;
            } else {
                $this->log('ошибка при изменении:' . $r2, self::LOG_ERROR);
                $result = false;
            }
            ApiComment::model()->deleteByPk($id);
            return $result;
        } else {
            $this->log('не удалось добавить комментарий, ответ:' . $r, self::LOG_ERROR);
        }
        return false;
    }

    /**
     * 'pattern'=>'api/comment/<id:\d+>','verb' => 'DELETE' - удаление комментария(может только владелец)
     */
    public function test_comment_delete($data) {
        if (empty($data['post_id'])) {
            $data['post_id'] = 1;
        }
        $data['test'] = 'test';
        $r = $this->request('/api/comment', 'POST', json_encode(array('data' => $data)), true);

        $t = json_decode($r, true);
        if (isset($t['result']) && $t['result'] == 'success' && $t['data']) {
            $this->log('добавлен комментарий :' . $t['data']);
            $id = $t['data'];
            $r2 = $this->request('/api/comment/' . $id, 'DELETE', '', true);
            $t2 = json_decode($r2, true);
            if (isset($t2['result']) && $t2['result'] == 'success') {
                $this->log($r2);
                $result = true;
            } else {
                $this->log('ошибка при удалении:' . $r2, self::LOG_ERROR);
                $result = false;
            }
            ApiComment::model()->deleteByPk($id);
            return $result;
        } else {
            $this->log('не удалось добавить комментарий, ответ:' . $r, self::LOG_ERROR);
        }
        return false;
    }

}
