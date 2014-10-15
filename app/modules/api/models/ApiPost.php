<?php

/**
 * Description of ApiPost
 */
class ApiPost extends Post {

    //put your code here
    public static function makeFeedItemsFromPostListApi($postList, $userId = null) {
        $result = array();
        if (is_array($postList)) {

            $i = 0;
            foreach ($postList as $post) {
                $result[$i]['user'] = $post->user->nick;
                $result[$i]['id'] = $post->id;
                $result[$i]['date'] = $post->date;
                $result[$i]['place'] = 'place';
                $result[$i]['text'] = $post->text;
                $result[$i]['likesCount'] = $isLike = Like::getCountByOwnerId($post->id);
                $result[$i]['commentsCount'] = ApiComment::getCountByPostId($post->id);

                if ($userId) {
                    $result[$i]['hasLiked'] = Like::isAlreadyLiked($userId, $post->id);
                }

                if ($post->photo) {
                    foreach ($post->photo as $photo) {
                        $result[$i]['photos'][$photo->id]['id'] = $photo->id;
                        $result[$i]['photos'][$photo->id]['width'] = $photo->width;
                        $result[$i]['photos'][$photo->id]['height'] = $photo->height;
                        $result[$i]['photos'][$photo->id]['url'] = Yii::app()->request->hostInfo . $photo->getFileUrl();
                    }
                } else {
                    $result[$i]['photos'] = array();
                }
                if ($post->user_rating) {
                    foreach ($post->user_rating as $user_rating) {
                        $result[$i]['ratings'][] = array(
                            'service_id' => $user_rating->service_id,
                            'rating_id' => $user_rating->rating_id,
                        );
                    }
                } else {
                    $result[$i]['ratings'] = array();
                }
                ++$i;
            }
        }
        return $result;
    }

    public static function create($data, $userId) {
        if (!$userId) {
            return 0;
        }
        if (empty($data['allocation_id'])) {
            $data['allocation_id'] = 0;
        }
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $post = new ApiPost();
            $post->attributes = $data;
            $post->tp_user_id = $userId;
            if ($post->save()) {
                if (isset($data['ratings'])) {
                    if (is_array($data['ratings'])) {
                        foreach ($data['ratings'] as $rating) {
                            $userRating = new UserRating();
                            $userRating->tp_user_id = $userId;
                            $userRating->post_id = $post->id;
                            $userRating->service_id = $rating['service_id'];
                            $userRating->rating_id = $rating['rating_id'];
                            if (!$userRating->save()) {
                                throw new \Exception('Model "UserRating" not saved.');
                            }
                        }
                    }
                }
            } else {
                throw new \Exception('Model "Post" not saved.');
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            return 0;
        }
        return $post;
    }

    public static function updateByIdApi($id, $data, $userId) {
        $post = self::model()->findByPk($id, "trash = 'f'");
        if ($post) {
            if (isset($data['allocation_id'])) {
                if ($data['allocation_id'] != $post->allocation_id) {
                    return 0;
                }
            }
            if ($userId == $data['tp_user_id']) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $post->attributes = $data;
                    if ($post->save()) {
                        if (isset($data['ratings'])) {
                            if (is_array($data['ratings'])) {
                                foreach ($data['ratings'] as $rating) {
                                    $userRating = new UserRating();
                                    $userRating->tp_user_id = $userId;
                                    $userRating->post_id = $post->id;
                                    $userRating->service_id = $rating['service_id'];
                                    $userRating->rating_id = $rating['rating_id'];
                                    if (!$userRating->save()) {
                                        throw new \Exception('Model "UserRating" not saved.');
                                    }
                                }
                            }
                        }
                    } else {
                        throw new \Exception('Model "Post" not saved.');
                    }
                    $transaction->commit();
                    return $post;
                } catch (Exception $e) {
                    $transaction->rollback();
                    return 0;
                }
            }
        }
        return 0;
    }

    public static function getCommentsApi($postId, $limit = null, $offsetId = null, $order = null) {
        $commentsList = ApiComment::getListByPostId($postId, $limit, $offsetId, $order);

        $result = array();
        $i = 0;
        foreach ($commentsList as $comment) {
            $result[$i]['id'] = $comment->id;
            $result[$i]['user'] = $comment->user->nick;
            $result[$i]['content'] = $comment->text;
            if ($comment->photo) {
                foreach ($comment->photo as $photo) {
                    $result[$i]['photos'][$photo->id]['id'] = $photo->id;
                    $result[$i]['photos'][$photo->id]['width'] = $photo->width;
                    $result[$i]['photos'][$photo->id]['height'] = $photo->height;
                    $result[$i]['photos'][$photo->id]['url'] = Yii::app()->request->hostInfo . $photo->getFileUrl();
                }
            } else {
                $result[$i]['photos'] = array();
            }
            ++$i;
        }
        return $result;
    }

    public function behaviors() {
        return array(
            'apiMsg' => array('class' => 'ApiMessageBehavior'));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
