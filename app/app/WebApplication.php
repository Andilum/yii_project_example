<?php

Yii::import('CWebApplication', true);
Class WebApplication extends CWebApplication
{
    
    /**
     * Событие когда распарсился url и в $_GET['lang'] занесён язык из url
     * @param type $event
     */
    public function onParseLang($event) {
        // Непосредственно вызывать событие принято в его описании.
        // Это позволяет использовать данный метод вместо raiseEvent
        // во всём остальном коде.
        $this->raiseEvent('onParseLang', $event);
    }

}