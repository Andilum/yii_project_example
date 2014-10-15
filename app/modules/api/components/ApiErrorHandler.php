<?php

/**
 * Description of ApiErrorHandler
 */
class ApiErrorHandler extends CErrorHandler {

    protected function renderException() {
        echo json_encode(array('result' => 'error', 'error' => $this->error['code'] . ':' . $this->error['message']));
        exit;
    }

    protected function renderError() {
        $this->renderException();
    }

}