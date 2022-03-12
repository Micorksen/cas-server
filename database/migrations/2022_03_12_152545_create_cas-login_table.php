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

class CreateCasLoginTable extends Migration{
    /**
     * Run the migrations.
     * @return void
    **/
    public function up(){
        Schema::create("cas-login", function(Blueprint $table){
            $table->string("throttleBy", 510);
            $table->primary("throttleBy");
            $table->unsignedInteger("attempts");
            $table->dateTime("lastAttempt");
        });
    }

    /**
     * Reverse the migrations.
     * @return void
    **/
    public function down(){ Schema::drop("cas-login"); }
}