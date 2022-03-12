<?php
/******************************************************
 * Copyright (c) 2022 Micorksen.
 * 
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * 
 ******************************************************/

namespace Micorksen\CasServer\Console;

use Micorksen\CasServer\Models\Authentification;
use Micorksen\CasServer\Models\Ticket;
use Illuminate\Console\Command;

class Cleanup extends Commands{
    /**
     * The name and signature of the console command.
     * @var string
    **/
    protected $signature = "cas-server:cleanup";

    /**
     * The console command description.
     * @var string
    **/
    protected $description = "Cleanup expired authentication sessions and tickets";

    /**
     * Execute the console command.
     * @return mixed
    **/
    public function handle(){
        $ticket = new Ticket();
        $tickets = $ticket->cleanup();
        $this->info("Deleted $tickets expired ticket(s).");

        $authentification = new Authentification();
        $authentifications = $authentification->cleanup();
        $this->info("Deleted $authentifications expired authentification session(s).");
    }
}