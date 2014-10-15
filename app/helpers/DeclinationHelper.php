<?php

abstract class DeclinationHelper {

    /**
     * @param $number
     * @param $forms
     * @return mixed
     */
    public static function getFormat($number, $forms) {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
}