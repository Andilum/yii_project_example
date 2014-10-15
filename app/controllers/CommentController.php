<?php

class CommentController extends Controller {

    public function actionCreate() {
        $this->checkUserAuth();
        $data = array('result' => 'error');

        if (isset($_POST['Comment'])) {
            $photos = array();
            $files = CUploadedFile::getInstances(Photo::model(), 'file');
            if ($files) {
                foreach ($files as $file) {
                    $photo['file'] = $file;
                    $photos[] = $photo;
                }
            }
            $result = Comment::add(Yii::app()->user->id, $_POST['Comment'], $photos);
            if ($result) {
                $data['result'] = 'success';
                $commentList = Comment::getListByPostIdInArray($_POST['Comment']['post_id'], $_POST['lastCommentId']);
                $data['data']['comments'] = $commentList;
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionUpdate($id) {
        $this->checkUserAuth();
        $model = Comment::model()->findByPk($id);
        if (isset($_POST['Comment'])) {
            $photos = array();
            $files = CUploadedFile::getInstances(Photo::model(), 'file');
            if ($files) {
                foreach ($files as $file) {
                    $photo['file'] = $file;
                    $photos[] = $photo;
                }
            }
            Comment::updateById(Yii::app()->user->id, $id, $_POST['Comment'], $photos);
            $this->redirect('/');
        }

        $this->render('update', array('model' => $model));
    }

    public function actionDelete($id) {
        $this->checkUserAuth();
        Comment::destroyById($id, Yii::app()->user->id);
        $this->redirect('/');
    }

    public function actionList($postId) {
        if (Yii::app()->request->isAjaxRequest) {
            $commentList = Comment::getListByPostIdInArray($postId);
            $data['result'] = 'success';
            $data['data']['comments'] = $commentList;

            echo CJSON::encode($data);
            Yii::app()->end();
        }
        $this->redirect('/');
    }
}