<?php

Yii::import('application.modules.api.helpers.*');

class AvatarController extends UnauthorizeController {
    public function actionIndex($nick) {
        $user = ApiUser::model()->find('nick = :nick', array(':nick' => $nick));
                    
        if ( !$user ) {
            $this->_responseNotFoundError('User not found');
        }
            
        $url = ApiUser::getAvatarPath($user->id, ApiUser::AVATAR_SIZE_100);
        
        $response = UserHelper::makeCurlProccess($url, array(
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS => 5
        ));
        
        $responseHeaders = UserHelper::getCurlHeaders($response);
        
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            if (isset($responseHeaders['Last-Modified'])) {
                if (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= strtotime(($responseHeaders['Last-Modified']))) {
                    header("HTTP/1.1 304 Not Modified"); 
                    exit;
                }
            }
        }
        
        $responseBody = UserHelper::getCurlBody($response);
        echo $responseBody;
        exit;
    }
}