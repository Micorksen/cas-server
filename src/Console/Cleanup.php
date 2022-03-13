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

use Micorksen\CasServer\Models\Authentication;
use Micorksen\CasServer\Models\Ticket;
use Illuminate\Console\Command;

class Cleanup extends Command{
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

        $authentication = new Authentication();
        $authentications = $authentication->cleanup();
        $this->info("Deleted $authentications expired authentification session(s).");
    }
}
