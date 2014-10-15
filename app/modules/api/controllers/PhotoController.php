<?php
use Lightsoft\REST;
class PhotoController extends ApiRESTController {
    
    public function actionCreate() {
        $data = REST\Server::obtainRequestData();
        $result = ApiPhoto::create($data, REST\Server::obtainAuthId());
        
        if ( isset($result) ) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseNotFoundError("HotelsInspector info not found");
        }
    }
    
    public function actionCreate2() {
        $data['body'] = base64_encode(file_get_contents('php://input'));
        if(isset($_SERVER['HTTP_X_HI_IMG_EXT'])){
            $data['ext'] = $_SERVER['HTTP_X_HI_IMG_EXT'];
        }
        if(isset($_SERVER['HTTP_X_HI_IMG_NAME'])){
            $data['name'] = $_SERVER['HTTP_X_HI_IMG_NAME'];
        }
        if(isset($_SERVER['HTTP_X_HI_IMG_TEXT'])){
            $data['text'] = $_SERVER['HTTP_X_HI_IMG_TEXT'];
        }
        $result = ApiPhoto::create($data, REST\Server::obtainAuthId());
        
        if ( isset($result) ) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseNotFoundError("HotelsInspector info not found");
        }
    }

    public function actionDestroy($id) {
        $result = ApiPhoto::destroyById($id, REST\Server::obtainAuthId());

        if ( isset($result) ) {
            $this->_responseSuccessView($result);
        } else {
            $this->_responseNotFoundError("HotelsInspector info not found");
        }
    }
}