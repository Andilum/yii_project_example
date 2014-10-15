<?php


class UnauthorizeController extends ApiRESTController{
    
    protected function beforeAction($action) {
        return true;
    }
}