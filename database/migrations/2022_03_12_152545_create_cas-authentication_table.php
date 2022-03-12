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

class CreateCasAuthenticationTable extends Migration{
    /**
     * Run the migrations.
     * @return void
    **/
    public function up(){
        Schema::create("cas-authentication", function(Blueprint $table){
            $table->bigIncrements("id");
            $table->string("username");
            $table->string("attributeJson")->nullable();
            $table->boolean("sso");
            $table->dateTime("lastUsed");
            $table->dateTime("createdAt");
        });
    }

    /**
     * Reverse the migrations.
     * @return void
    **/
    public function down(){ Schema::drop("cas-authentication"); }
}