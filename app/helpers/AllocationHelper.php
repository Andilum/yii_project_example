<?php

abstract class AllocationHelper {
    
    public static function getClassNameForStars($allocatName) {
        switch ($allocatName) {
            case '1*':
                $result = 'my-profile-hotel-stars star1';
                break;
            case '2*':
                $result = 'my-profile-hotel-stars star2';
                break;
            case '3*':
                $result = 'my-profile-hotel-stars star3';
                break;
            case '4*':
                $result = 'my-profile-hotel-stars star4';
                break;
            case '5*':
                $result = 'my-profile-hotel-stars star5';
                break;
            case 'HV-1':
                $result = 'my-profile-hotel-stars star5';
                break;
            case 'HV-2':
                $result = 'my-profile-hotel-stars star3';
                break;
            default :
                $result = '';
        }
        return $result;
    }
}

