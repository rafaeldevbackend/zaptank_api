<?php

namespace App\Zaptank\Helpers;

use \DateTime;
use \DateInterval;

date_default_timezone_set('America/Sao_Paulo');

class Date {

    public static function getDate() :string {
        return date('Y-m-d H:i:s');
    }

    public static function difference($start, $end) :DateInterval {

        $start_time = DateTime::createFromFormat('Y-m-d H:i:s', $start);
        $end_time = DateTime::createFromFormat('Y-m-d H:i:s', $end);

        if ($end_time->format('H') == '00' && $start_time > $end_time) {
            $end_time->modify('+1 day');
        }        
        return $interval = $start_time->diff($end_time);
    }
}