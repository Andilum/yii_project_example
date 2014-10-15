<?php

abstract class DateHelper {

    const SECONDS_IN_MINUTE = 60,
            SECONDS_IN_HOUR = 3600,
            SECONDS_IN_DAY = 86400;
    const NOW = 'только что';
    const HOURS_FORM1 = 'час',
            HOURS_FORM2 = 'часа',
            HOURS_FORM3 = 'часов';
    const MINUTES_FORM1 = 'минута',
            MINUTES_FORM2 = 'минуты',
            MINUTES_FORM3 = 'минут';

    /**
     * @param $date
     * @return bool|string
     */
    public static function getDateFormat2Post($date) {
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }
        $now = time();
        $diff = $now - $date;

        if ($diff < self::SECONDS_IN_DAY) {
            if ($diff < 0) {
                $format = self::NOW;
            } else {
                $hours = floor($diff / self::SECONDS_IN_HOUR);
                $minutes = floor(($diff - ($hours * self::SECONDS_IN_HOUR)) / self::SECONDS_IN_MINUTE);
                $format = '';
                if ($hours) {
                    $format .= $hours . ' ' . DeclinationHelper::getFormat($hours, array(self::HOURS_FORM1, self::HOURS_FORM2, self::HOURS_FORM3)) . ' ';
                }
                $format .= $minutes . ' ' . DeclinationHelper::getFormat($minutes, array(self::MINUTES_FORM1, self::MINUTES_FORM2, self::MINUTES_FORM3));
                if (!$hours && !$minutes) {
                    $format = self::NOW;
                }
            }
        } else {
            $format = date('d.m.Y H:i', $date);
        }

        return $format;
    }

}
