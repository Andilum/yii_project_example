<?php

class PublishCommand extends CConsoleCommand {

    public function actionIndex($url) {

        $request = array_slice(explode('/', $url), 2);

        $handler = Yii::app()->getHandler(array_shift($request));
        if ($handler->publish(implode('/', $request))) {
            header('Location:' . $url, null, 303);
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }
}