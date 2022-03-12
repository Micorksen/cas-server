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
class Service{
    private $services;
    public function __construct(){ $this->services = collect(config("cas-server.services")); }
    public function validate($service){ return $this->services->first(function($value, $key) use($service){ return preg_match("#" . $value["urlRegex"] . "#", $service); }, false); }
    public function redirect($service, $ticket){ return $service . (str_contains($service, "?") ? "&" : "?") . "ticket=" . urlencode($ticket); }
    public function attributes($service, $userAttributes){
        $service = $this->validate($service);
        return !is_array($service) || !array_key_exists("attributes", $service) ? [] : collect($userAttributes)->only($service["attributes"])->all();
    }

    public function logoutRedirect($service){
        if(config("cas-server.logout.followServicesRedirects") !== true) return false;
        if($service && $this->validate($service) !== false) return $service;
        return false;
    }
}
