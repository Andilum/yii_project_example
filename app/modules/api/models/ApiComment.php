<?php

/**
 * Description of ApiComment
 */
class ApiComment extends Comment {
    /**
     * @param array $data
     * @param int $userId
     * @return int|\ApiComment 
     */
    public static function create($data, $userId) {
        if ( !$userId || !isset($data['post_id']) ) {
            return 0;
        }
        
        $comment = new ApiComment();
        $comment->attributes = $data;
        $comment->tp_user_id = $userId;
        
        if ( $comment->save() ) {
            return $comment;
        }
        
        return 0;
    }

    /**
     * @param int $id
     * @param array $data
     * @param int $userId
     * @return int 
     */
    public static function updateByIdApi($id ,$data, $userId) {
        $comment = self::model()->findByPk($id, "trash = 'f'");
        
        if ( $comment ) {
            if ( isset($data['post_id']) ) {
                if ( $data['post_id'] != $comment->post_id ) {
                    return 0;
                }
            } else {
                return 0;
            }
            
            if ( $userId == $comment->tp_user_id ) {
                $comment->attributes = $data;
                
                if ( $comment->save() ) {
                    return $comment;
                }
            }
        }
        
        return 0;
    }
    
    /**
     * @param string $className
     * @return ApiComment
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
