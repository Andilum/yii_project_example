<?php

class RatingPopup extends CWidget {

    public function init() {
        $baseAssetsPath = Yii::getPathOfAlias('application.components.assets.RatingPopup');
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->getAssetManager()->publish($baseAssetsPath . '/ratingPopup.js'));
    }

    public function run() {
        if ((Yii::app()->controller->id == 'allocation') && isset($_GET['id'])) {
            $allocationId = $_GET['id'];
        } else {
            return '';
        }
        $allocation = DictAllocation::model()->findByPk($allocationId);

        $criteria = new CDbCriteria();
        $criteria->select = 'id, name';
        $criteria->addCondition("trash = 'f'");
        $criteria->order = 'name ASC';
        $categories = RatingCategory::model()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 't.id, t.name';
        $criteria->with = array(
            'category' => array(
                'select' => 'name'
            ),
        );
        $criteria->addCondition("t.trash = 'f'");
        $criteria->order = 't.name ASC';
        $services = RatingService::model()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = 'id, rate, label, description';
        $criteria->addCondition("trash = 'f'");
        $criteria->order = 'rate DESC';
        $ratings = Rating::model()->findAll($criteria);

        return $this->render('ratingPopup', array(
            'categories' => $categories,
            'services' => $services,
            'ratings' => $ratings,
            'allocation' => $allocation,
        ));
    }
} 