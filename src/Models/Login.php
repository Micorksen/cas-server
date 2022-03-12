<?php
/******************************************************
 * Copyright (c) 2022 Micorksen.
 * 
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * 
 ******************************************************/

namespace Micorksen\CasServer\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Carbon\Carbon;

class Login extends Model{
    public $table = "cas-login";
    public $primaryKey = "throttleBy";
    public $timestamps = false;
    public $throttleByConfig;
    public $maximumAttemptsBeforeThrottle;
    public $secondsBetweenAttempts;
    public $throttleReset;
    public $userClass;
    public $incrementing = false;
    public $dates = ["lastAttempt"];

    /**
     * Construct model.
     * @param array $attributes
     * @return void
    **/
    public function __construct(array $attributes = []){
        parent::__construct($attributes);
        $this->throttleByConfig = config("cas-server.loginThrottling.throttleBy", "username");
        $this->maximumAttemptsBeforeThrottle = intval(config("cas-server.loginThrottling.maximumAttemptsBeforeThrottle", 3), 10);
        $this->secondsBetweenAttempts = intval(config("cas-server.loginThrottling.secondsBetweenAttempts", 3), 10);
        $this->throttleReset = config("cas-server.loginThrottling.throttleReset", "30 minutes");
        $this->userClass = config("cas-server.userClass", "blank");

        if(!class_exists($this->userClass)) throw new \Exception($this->userClass . " doesn't exist !");
        if(!array_key_exists("Micorksen\CasServer\Models\UserInterface", class_implements($this->userClass))) throw new \Exception("The user class must implement Micorksen\CasServer\Models\UserInterface !");
        if(config("cas-server.dateFormatOverride")) $this->setDateFormat(config("cas-server.dateFormatOverride"));
    }

    /**
     * Get throttle type.
     * @param string $username
     * @param string $ip
     * @return void
    **/
    private function throttleBy($username, $ip){
        switch(strtolower($this->throttleByConfig)){
            case "username":
                return $username;
                break;
            case "ip":
                return $ip;
                break;
            case "ipandusername":
            case "usernameandip":
                return $username . $ip;
                break;
        }

        throw new \Exception(
            "Invalid 'throttleBy' setting : " . $this->throttleByConfig . "." .
            "Valid settings are 'username', 'ip' or 'ipAndUsername' (= 'usernameAndIp')"
        );
    }

    /**
     * Enable throttling.
     * @param $throttleBy
     * @return void
    **/
    private function throttle($throttleBy){
        $throttle = $this->findOrNew($throttleBy);
        $throttle->throttleBy = $throttleBy;
        $throttling = Carbon::parse("-" . $this->throttleReset);
        if($throttle->lastAttempt > $throttling){
            $throttle->attempts++;
            if($throttle->attempts > $this->maximumAttemptsBeforeThrottle){
                $delay = $this->secondsBetweenAttempts - $throttle->lastAttempt->diffInSeconds(null);
                if($delay > 0) sleep($delay);
            }
        } else{ $throttle->attempts = 1; }

        $throttle->lastAttempt = Carbon::now();
        $throttle->save();
    }

    /**
     * Validate if user exists.
     * @param string $username
     * @param string $ip
     * @param string $password
     * @return boolean
    **/
    public function validate($username, $ip, $password){
        $this->throttle($this->throttleBy($username, $ip));
        return new $this->userClass->checkLogin($username, $password) ? true : false;
    }

    /**
     * Return attributes of user.
     * @param string $username
     * @return array
    **/
    public function userAttributes($username){ return new $this->userClass->userAttributes($username); }
}
