<?php

class PhotoCloud extends CWidget {
    const DEFAULT_COUNT = 9;

    /**
     * @var int
     */
    public $count;

    public function init() {
        if (is_null($this->count)) {
            $this->count = self::DEFAULT_COUNT;
        }

        $baseAssetsPath = Yii::getPathOfAlias('application.components.assets.PhotoCloud');
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/photoCloud.js'));
    }

    public function run() {
        $controllerId = Yii::app()->controller->getId();
        $actionId = Yii::app()->controller->getAction()->getId();

        if ($controllerId == 'user' && $actionId != 'index') {
            $userId = Yii::app()->request->getParam('id');
            $criteria = new CDbCriteria();
            $criteria->select = 'id, ext';
            $criteria->addCondition('t.owner_id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.tp_user_id = :userId AND p.trash = \'f\' UNION
                SELECT c.id FROM hi.hi_comment c WHERE c.tp_user_id = :userId AND c.trash = \'f\'
            )');
            $criteria->params[':userId'] = $userId;
            $criteria->addCondition("t.trash = 'f' AND t.owner_id > 0");
            $criteria->order = 'date DESC';
            $criteria->limit = $this->count;
            $photos = Photo::model()->findAll($criteria);
        } elseif ($controllerId == 'allocation' && $actionId != 'index') {
            $allocationId = Yii::app()->request->getParam('id');
            $criteria = new CDbCriteria();
            $criteria->select = 'id, ext';
            $criteria->addCondition('t.owner_id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.allocation_id = :allocationId AND p.trash = false UNION
                SELECT c.id FROM hi.hi_post p2
                LEFT JOIN hi.hi_comment c ON c.post_id = p2.id AND c.trash = false
                WHERE p2.allocation_id = :allocationId AND p2.trash = false AND c.id IS NOT NULL
            )');
            $criteria->params[':allocationId'] = $allocationId;
            $criteria->addCondition("t.trash = 'f' AND t.owner_id > 0");
            $criteria->order = 'date DESC';
            $criteria->limit = $this->count;
            $photos = Photo::model()->findAll($criteria);
        } else {
            $criteria = new CDbCriteria();
            $criteria->select = 'id, ext';
            $criteria->addCondition('t.owner_id IN (
                SELECT p.id FROM hi.hi_post p WHERE p.trash = \'f\' UNION
                SELECT c.id FROM hi.hi_comment c WHERE c.post_id IN (SELECT t2.id FROM hi.hi_post t2 WHERE t2.trash = \'f\') AND c.trash = \'f\'
            )');
            $criteria->addCondition("t.trash = 'f' AND t.owner_id > 0");
            $criteria->order = 'date DESC';
            $criteria->limit = $this->count;
            $photos = Photo::model()->findAll($criteria);
        }

        return $this->render('photoCloud', array(
            'photos' => $photos,
        ));
    }
} 