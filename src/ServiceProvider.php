<?php
/******************************************************
 * Copyright (c) 2022 Micorksen.
 * 
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * 
 ******************************************************/

namespace Micorksen\CasServer;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Micorksen\CasServer\Console\Cleanup;

class ServiceProvider extends IlluminateServiceProvider{
    /**
     * Bootstrap the application services.
     * @param Router $router
     * @return void
    **/
    public function boot(Router $router){
        if(!$this->app->routesAreCached()) $router->group([ "middleware" => "web" ], function(){ require __DIR__ . "/../resources/routes.php"; });

        $this->loadViewsFrom(__DIR__ . "/../resources/views", "cas-server");
        $this->loadViewsFrom(__DIR__ . "/../resources/xml", "cas-server-xml");

        $this->publishes([ __DIR__ . "/../config/cas-server.php" => config_path("cas-server.php") ], "config");
        $this->publishes([ __DIR__ . "/../database/migrations/" => database_path("migrations") ], "migrations");
        $this->publishes([ __DIR__ . "/../public/vendor/cas-server" => public_path("vendor/cas-server") ], "public");
        $this->publishes([ __DIR__ . "/../resources/views" => base_path(__DIR__ . "/../resources/views/vendor/cas-server") ], "views");
    }

    /**
     * Register the application services.
     * @return void 
    **/
    public function register(){
        $this->mergeConfigFrom(__DIR__ . "/../config/cas-server.php", "cas-server");
        $this->commands([ Cleanup::class ]);
    }
}
