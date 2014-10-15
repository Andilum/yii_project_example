<?php

class TagCloud extends CWidget {
    const DEFAULT_COUNT = 10;

    /**
     * @var int
     */
    public $count;

    public function init() {
        if (is_null($this->count)) {
            $this->count = self::DEFAULT_COUNT;
        }
    }

    public function run() {
        $limit = $this->count;

        $controllerId = Yii::app()->controller->getId();
        $actionId = Yii::app()->controller->getAction()->getId();
        if ($controllerId == 'user' && $actionId != 'index') {
            $userId = Yii::app()->request->getParam('id');
            $tags = Yii::app()->db->createCommand()
                ->select('name, COUNT(hash) AS count')
                ->from('hi.hi_tag t')
                ->where('t.id IN (
                    SELECT p.id FROM hi.hi_post p WHERE p.tp_user_id = :userId AND p.trash = \'f\' UNION
                    SELECT c.id FROM hi.hi_comment c WHERE c.tp_user_id = :userId AND c.trash = \'f\'
                )', array(':userId' => $userId))
                ->group('name, hash')
                ->order('count DESC, name')
                ->limit($limit)
                ->queryAll();
        } elseif ($controllerId == 'allocation' && $actionId != 'index') {
            $allocationId = Yii::app()->request->getParam('id');
            $tags = Yii::app()->db->createCommand()
                ->select('name, COUNT(hash) AS count')
                ->from('hi.hi_tag t')
                ->where('t.id IN (
                    SELECT p.id FROM hi.hi_post p WHERE p.allocation_id = :allocationId AND p.trash = false UNION
                    SELECT c.id FROM hi.hi_post p2
                    LEFT JOIN hi.hi_comment c ON c.post_id = p2.id AND c.trash = false
                    WHERE p2.allocation_id = :allocationId AND p2.trash = false AND c.id IS NOT NULL
                )', array(':allocationId' => $allocationId))
                ->group('name, hash')
                ->order('count DESC, name')
                ->limit($limit)
                ->queryAll();
        } else {
            $tags = Yii::app()->db->createCommand()
                ->select('name, COUNT(hash) AS count')
                ->from('hi.hi_tag t')
                ->group('name, hash')
                ->order('count DESC, name')
                ->limit($limit)
                ->queryAll();
        }

        return $this->render('tagCloud', array(
            'tags' => $tags,
        ));
    }
} 