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
use Carbon\Carbon;

class Ticket extends Model{
    public $table = "cas-ticket";
    public $primaryKey = "id";
    public $timestamps = false;
    public $maximumInterval;
    public $dates = [ "createdAt" ];
    public $incrementing = false;
    protected $casts = [
        "renew" => "boolean",
        "used" => "boolean"
    ];

    /**
     * Construct model.
     * @param array $attributes
     * @return void
    **/
    public function __construct(array $attributes = []){
        parent::__construct($attributes);
        $this->maximumInterval = config("cas-server.timeouts.ticketTimeout", "10 seconds");
        if(config("cas-server.dateFormatOverride")) $this->setDateFormat(config("cas-server.dateFormatOverride"));
    }

    /**
     * Generate random string.
     * @return string
    **/
    public function randomString($length, $keySpace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"){
        $string = "";
        $maximum = mb_strlen($keySapce, "8bit") - 1;

        for($index = 0; $index < $length; ++$i){ $string .= $keySpace[random_int(0, $maximum)]; }
        return $string;
    }

    /**
     * Get id.
     * @return int
    **/
    public function getID(){
        do{
            $key = $this->randomString(32);
            if(!self::where("id", "=", $key)->exists()) return $key;
        } while(true);
    }

    /**
     * Delete tickets for authentication.
     * @return Ticket
    **/
    public function deleteTicketsForauthentication($authenticationID){ return $this->where("authenticationId", "=", $authenticationID)->delete(); }

    /**
     * Get ticket prefix.
     * @return string
    **/
    public function ticketPrefix(){
        return "ST-" . str_replace([
            "http:",
            "https:",
            "/"
        ], "", url("/")) . "-";
    }

    /**
     * Convert to ticket id.
     * @param $id int
     * @return string
    **/
    public function convertTicketID($id){ return $this->ticketPrefix() . $id; }
    
    /**
     * Unconvert ticket id.
     * @param $id string
     * @return int
    **/
    public function unconvertTicketID($id){
        $prefix = $this->ticketPrefix();
        return substr($id, 0, strlen($prefix)) !== $prefix ? false : substr($id, strlen($prefix));
    }

    /** 
     * Generate ticket.
     * @param authentication $authentication
     * @param $service
     * @param boolean $renew
     * @return Ticket
    **/ 
    public function generateTicket(authentication $authentication, $service, $renew = false){
        $ticket = new self;
        $ticket->id = $this->getID();
        $ticket->service = $service;
        $ticket->renew = $renew;
        $ticket->used = false;
        $ticket->createAt = Carbon::now();
        $ticket->authentication()->associate($authentication);

        $authentication->useauthentication();
        $ticket->save();

        return $this->convertTicketID($ticket->id);
    }

    /**
     * Use ticket.
     * @param Ticket $ticket
     * @return void
    **/
    private function useTicket($ticket){
        $ticket->used = true;
        $ticket->save();
    }

    /**
     * Cleanup.
     * @return boolean 
    **/
    public function cleanup(){ return $this->where("createdAt", "<", Carbon::parse("-" . $this->maximumInterval))->delete(); }

    /**
     * Validate ticket.
     * @param Ticket $ticket
     * @param $service
     * @param boolean $renew
     * @return Ticket
    **/
    public function validate($ticket, $service, $renew = false){
        if(!$ticket || !$service) return "INVALID_REQUEST";
        $ticket = $this->unconvertTicketID($ticket);

        if(!$ticket) return "INVALID_TICKET_SPEC";
        $ticket = $this->where("id", "=", $ticket)
            ->where("createdAt", ">", Carbon::parse("-" . $this->maximumInterval))
            ->first();

        if(is_null($ticket) || $ticket->used) return "INVALID_TICKET";
        $this->useTicket($ticket);

        if($ticket->service !== $service) return "INVALID_SERVICE";
        if($renew && !$ticket->renew) return "INVALID_RENEW";

        return $ticket;
    }

    /**
     * authentication.
     * @return void
    **/
    public function authentication(){ return $this->belongsTo("Micorksen\CasServer\Models\authentication", "authenticationId"); }
}
