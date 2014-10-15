<?php

class PhotoController extends Controller {
    public function actionIndex() {
        $page = Yii::app()->getRequest()->getParam('page');
        $type = Yii::app()->getRequest()->getParam('type', '');

        $params['postOnly'] = true;
        if (Yii::app()->request->isAjaxRequest) {
            $params['pageSize'] = Photo::DEFAULT_PHOTO_LIMIT_ON_PAGE;
            if (Yii::app()->getRequest()->getParam('all')) {
                $_GET['page'] = 1;
                $params['pageSize'] = Photo::DEFAULT_PHOTO_LIMIT_ON_PAGE * $page;
            }
            if ($tagName = Yii::app()->getRequest()->getParam('tagName')) {
                $params['tagName'] = $tagName;
            }
        }
        $dataProvider = Photo::initSearch($params);

        if (Yii::app()->request->isAjaxRequest) {
            $data['result'] = 'success';
            $data['data']['photos'] = $this->renderPartial('_photoList', array('dataProvider' => $dataProvider,'type' => $type), true);
            $data['data']['isLastPage'] = $page >= $dataProvider->pagination->pageCount;

            echo CJSON::encode($data);
            Yii::app()->end();
        }

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'type' => $type,
        ));
    }

    public function actionFullSizeInfo($id) {
        $data['result'] = 'error';

        $postId = Photo::getPostId($id);
        if ($postId) {
            $post = Post::getFullInfoById($postId);
            $comments = Comment::getListByPostIds(array($postId));

            if ($post) {
                $data['result'] = 'success';

                $data['data']['isGuest'] = Yii::app()->user->isGuest;
                $data['data']['post'] = array(
                    'id' => $post->id,
                    'date' => DateHelper::getDateFormat2Post($post->date),
                    'text' => $post->getReplacedText(),
                    'like_count' => $post->like_count,
                    'comment_count' => $post->comment_count,
                    'max_rating' => $post->max_rating,
                    'user_nick' => $post->user->nick,
                    'allocation_name' => $post->allocation ? $post->allocation->name : '',
                    'alloccat_name' => $post->allocation ? $post->allocation->alloccat->name : '',
                    'allocation_url' => Yii::app()->createUrl('/allocation/view', array('id' => $post->allocation ? $post->allocation->id : 0)),
                    'user_url' => Yii::app()->createUrl('/user/view', array('id' => $post->tp_user_id)),
                    'avatar_url' => User::getAvatarPath($post->tp_user_id, User::AVATAR_SIZE_50),
                    'like' => array('users' => Like::getFirstUserByOwnerId($postId)),
                    'photos' => array(),
                );

                foreach ($post->photo as $photo) {
                    $data['data']['post']['photos'][] = array(
                        'id' => $photo->id,
                        'url' => $photo->getFileUrl(),
                    );
                }

                $data['data']['comments'] = array();
                if (isset($comments[$postId])) {
                    foreach ($comments[$postId] as $comment) {
                        $photos = array();
                        foreach ($comment->photo as $photo) {
                            $photos[] = array(
                                'id' => $photo->id,
                                'url' => $photo->getFileUrl(),
                                'url_thumbnail' => $photo->getFileUrl(Photo::IMAGE_SIZE_129),
                            );
                        }

                        $data['data']['comments'][] = array(
                            'id' => $comment->id,
                            'text' => $comment->getReplacedText(),
                            'user_nick' => $comment->user->nick,
                            'user_url' => Yii::app()->createUrl('/user/view', array('id' => $comment->user->id)),
                            'photos' => $photos,
                        );
                    }
                }
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }
} 