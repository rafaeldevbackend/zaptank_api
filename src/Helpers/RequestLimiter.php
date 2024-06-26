<?php

namespace App\ZapTank\Helpers;

use Slim\Psr7\Response;

use App\Zaptank\Helpers\Time;

session_start();

class RequestLimiter {

    private $ip;
    private $timeInSecondsForPasswordRecoverRequest;
    private $timeInSecondsForEmailActivationRequest;

    public function __construct($ip) {

        if(!isset($_SESSION[$ip])) {
            $_SESSION[$ip] = [];
        }

        $this->ip = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER['REMOTE_ADDR'];
        $this->timeInSecondsForPasswordRecoverRequest = $_ENV['INTERVAL_IN_SECONDS_FOR_PASSWORD_RECOVERY'];
        $this->timeInSecondsForEmailActivationRequest = $_ENV['INTERVAL_IN_SECONDS_FOR_EMAIL_ACTIVATION'];
    }

    public function addRequestInformation($ip, $request, $time) {
        $_SESSION[$ip][$request] = $time;
    }

    public function limitPasswordRecoveryRequests() {

        $ip = $this->ip;

        if(isset($_SESSION[$ip]['last_password_recovery_time'])) {

            $lastRequestTime = $_SESSION[$ip]['last_password_recovery_time'];
            $elapsedTime = Time::differenceInSecondsBetweenTwoHours($lastRequestTime, $start = Time::get());

            if($elapsedTime < $this->timeInSecondsForPasswordRecoverRequest) {
                $remainingTime = $this->timeInSecondsForPasswordRecoverRequest - $elapsedTime;
                return $remainingTime;
            }
        }
        return '0';
    }

    public function limitEmailActivationRequest() {

        $ip = $this->ip;

        if(isset($_SESSION[$ip]['last_email_activation_time'])) {

            $lastEmailActivationTime = $_SESSION[$ip]['last_email_activation_time'];
            $elapsedTime = Time::differenceInSecondsBetweenTwoHours($lastEmailActivationTime, $start = Time::get());
        
            if($elapsedTime < $this->timeInSecondsForEmailActivationRequest) {
                $remainingTime = $this->timeInSecondsForEmailActivationRequest - $elapsedTime;
                return $remainingTime;
            }
        }
        return '0';
    }
}