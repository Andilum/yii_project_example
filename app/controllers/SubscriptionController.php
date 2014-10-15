<?php

class SubscriptionController extends Controller {
    public function actionUserSubscribe() {
        $this->checkUserAuth();
        $data['result'] = 'error';

        if (Yii::app()->request->isAjaxRequest) {
            $subscriberId = Yii::app()->user->id;
            $userId = Yii::app()->getRequest()->getParam('id');
            $result = UserSubscription::add($subscriberId, $userId);
            if ($result) {
                $data['result'] = 'success';
                $data['data']['readers_count'] = UserSubscription::getReadersCount($userId);
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionUserUnsubscribe() {
        $this->checkUserAuth();
        $data['result'] = 'error';

        if (Yii::app()->request->isAjaxRequest) {
            $subscriberId = Yii::app()->user->id;
            $userId = Yii::app()->getRequest()->getParam('id');
            $result = UserSubscription::remove($subscriberId, $userId);
            if ($result) {
                $data['result'] = 'success';
                $data['data']['readers_count'] = UserSubscription::getReadersCount($userId);
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionAllocationSubscribe() {
        $this->checkUserAuth();
        $data['result'] = 'error';

        if (Yii::app()->request->isAjaxRequest) {
            $subscriberId = Yii::app()->user->id;
            $allocationId = Yii::app()->getRequest()->getParam('id');
            $result = AllocationSubscription::add($subscriberId, $allocationId);
            if ($result) {
                $data['result'] = 'success';
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionAllocationUnsubscribe() {
        $this->checkUserAuth();
        $data['result'] = 'error';

        if (Yii::app()->request->isAjaxRequest) {
            $subscriberId = Yii::app()->user->id;
            $allocationId = Yii::app()->getRequest()->getParam('id');
            $result = AllocationSubscription::remove($subscriberId, $allocationId);
            if ($result) {
                $data['result'] = 'success';
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }
} 