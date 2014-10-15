<?php
use Lightsoft\REST;

class AuthController extends ApiRESTController {
    /**
     * Это внешний апи, но тут авторизация не нужна
     * 
     * @param CAction $action
     * @return boolean 
     */
    protected function beforeAction($action) {
        return true;
    }
    
    public function actionCreate() {
        $data = REST\Server::obtainRequestData();
        
        $userId = Yii::app()->travelpassport->getUserIdByAuthInfo($data['email'], $data['password']);
        
        if ( $userId ) {
            try {
               
                $token = UserToken::createForUserId($userId);
                if (!($user = ApiUser::model()->findByPk($userId))) {
                    throw new \Exception('User not found.');
                }
            } catch(CException $e) {
                $this->_responseInternalError($e->getMessage());
            }
            
            $this->_responseSuccessView(array(
                "token" => $token->token,
                "user" => ApiUser::getDataApi($user)
            ));
        } else {
            $this->_responseWrongAuth();
        }
    }
    
    public function actionIndex() {
        $this->actionView(REST\Server::obtainAuthId());
    }
    
    public function actionView($id) {
        try {
            if (!($user = User::model()->findByPk($id))) {
                $this->_responseInternalError('User not found');
            }
            $result= ApiUser::getDataApi($user);
        } catch (Exception $e) {
            $result = $e;
        }
        
        if ( isset($result) ) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseWrongAuth();
        }
    }
}
