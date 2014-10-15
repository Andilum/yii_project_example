<?php

class LikeController extends Controller {

    public function actionCreate($ownerId) {
        $this->checkUserAuth();
        $data = array('result' => 'error');

        if (Yii::app()->request->isAjaxRequest) {
            if (!Like::isAlreadyLiked(Yii::app()->user->id, $ownerId)) {
                $result = Like::add(Yii::app()->user->id, $ownerId);
            } else {
                $result = Like::destroyByOwnerId($ownerId, Yii::app()->user->id);
            }

            if ($result) {
                $data['result'] = 'success';
                $data['data']['count'] = Like::getCountByOwnerId($ownerId);
                $data['data']['users'] = Like::getFirstUserByOwnerId($ownerId);
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }
    
    public function actionView($ownerId) {
        return Like::getCountByOwnerId($ownerId);
    }
    
    public function actionDestroy($id) {
        $this->checkUserAuth();
        return Like::destroy($id, Yii::app()->user->id);
    }
    
}