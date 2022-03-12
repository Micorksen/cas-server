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

class CreateCasTicketTable extends Migration{
    /**
     * Run the migrations.
     * @return void
    **/
    public function up(){
        Schema::create("cas-ticket", function(Blueprint $table){
            $table->string("id", 32);
            $table->primary("id");
            $table->bigInteger("authenticationID");
            $table->string("service");
            $table->boolean("renew");
            $table->boolean("used");
            $table->dateTime("createdAt");
            $table->foreign("authenticationId")->references("id")->on("CASAuthentication");
        });
    }

    /**
     * Reverse the migrations.
     * @return void
    **/
    public function down(){ Schema::drop(""); }
}