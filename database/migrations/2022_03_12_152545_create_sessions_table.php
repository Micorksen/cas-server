<?php
/******************************************************
 * Copyright (c) 2022 Micorksen.
 * 
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * 
 ******************************************************/

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration{
    /**
     * Run the migrations.
     * @return void
    **/
    public function up(){
        Schema::create("sessions", function(Blueprint $table){
            $table->string("id")->unique();
            $table->integer("user_id")->nullable();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->text("payload");
            $table->integer("last_activity");
        });
    }

    /**
     * Reverse the migrations.
     * @return void
    **/
    public function down(){ Schema::drop("sessions"); }
}