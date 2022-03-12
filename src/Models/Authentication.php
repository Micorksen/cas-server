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
use Illuminate\Database\Query\Expression;
use Carbon\Carbon;

class Authentication extends Model{
    public $table = "cas-authentication";
    public $primaryKey = "id";
    public $timestamps = false;
    public $maximumTime;
    public $maximumInterval;
    public $dates = [
        "lastUsed",
        "createdAt"
    ];

    private $sessionVariable = "cas-server.authenticationId";
    protected $casts = [
        "id" => "int",
        "attributeJson" => "array",
        "sso" => "boolean"
    ];

    /**
     * Construct model.
     * @param array $attributes
     * @return void
    **/
    public function __construct(array $attributes = []){
        parent::_construct($attributes);
        $this->maximumTime = config("cas-server.timeouts.ssoSessionTimeout", "8 hours");
        $this->maximumInterval = config("cas-server.timeouts.ssoSessionMaximumIdle", "40 minutes");
        if(config("cas-server.dateFormatOverride")) $this->setDateFormat(config("cas-server.dateFormatOverride"));
    }

    /**
     * Check if user is logged in.
     * @return authentication | boolean
    **/
    public function loggedIn(){
        $authenticationID = session($this->sessionVariable);
        if($authenticationID){
            $lastUsed = new \DateTime();
            $lastUsed->modify("-" . $this->maximumInterval);

            $createdAt = new \DateTime();
            $createdAt->modify("-" . $this->maximumTime);

            $authentication = $this->where($this->primaryKey, "=", $authenticationID)
                ->where("lastUsed", ">", $lastUsed)
                ->where("createdAt", ">", $createdAt)
                ->where("sso", "=", 1)
                ->first();

            return is_null($authentication) ? false : $authentication;
        }

        return false;
    }

    /**
     * Login user.
     * @param $username string
     * @param $attributes array
     * @param $sso boolean
     * @return authentication
    **/
    public function login($username, $attributes, $sso){
        $authentication = new self;
        $authentication->username = $username;
        $authentication->attributeJson = $attributes;
        $authentication->lastUsed = Carbon::now();
        $authentication->createdAt = Carbon::now();
        $authentication->sso = $sso;
        $authentication->save();

        session([ $this->sessionVariable => $authentication->id ]);
        return $authentication;
    }

    /**
     * Logout user.
     * @return void
    **/
    public function logout(){
        $authenticationID = session($this->sessionVariable);
        if($authenticationID){
            $authentication = $this->where($this->primaryKey, "=", $authenticationID)
                ->first();

            if(!is_null($authentication)){
                $ticket = new Ticket();
                $ticket->deleteTicketsForauthentication($authenticationID);
                $authentication->delete();
            }
        }

        session([ $this->sessionVariable => false ]);
    }

    /**
     * Use authentication.
     * @return void
    **/
    public function useauthentication(){
        $this->lastUsed = Carbon::now();
        $this->save();
    }

    /**
     * Get tickets.
     * @return Ticket
    **/
    public function tickets(){ return $this->hasMany("Micorksen\CasServer\Models\Ticket", "authenticationId"); }
    
    /** 
     * Cleanup.
     * @return boolean
    **/ 
    public function cleanup(){
        $lastUsed = Carbon::parse("-" . $this->maximumInterval);
        $createdAt = Carbon::parse("-" . $this->maximumTime);

        return $this->where(function($query) use($lastUsed, $createdAt){
            $query->where("lastUsed", "<", $lastUsed)
                ->orWhere("createdAt", "<", $createdAt)
                ->orWhere("sso", "=", 0);
        })->whereNotExists(function($query){
            $ticket = new Ticket();
            $query->select(new Expression("1"))
                ->from($ticket->table)
                ->where($ticket->table . ".authenticationId", "=", new Expression($this->table . "." . $this->primaryKey));
        })->delete();
    }
}