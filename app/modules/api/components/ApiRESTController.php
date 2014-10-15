<?php

use Lightsoft\REST;

/**
 * Description of ApiRESTController
 */
class ApiRESTController extends REST\RESTController {

    protected function _responseSuccessView($responseData) {

        if (is_array($responseData) && (isset($responseData[0]) || empty($responseData))) {
            $responseData = array('items' => $responseData);
        }
        return parent::_responseSuccessView($responseData);
    }

}