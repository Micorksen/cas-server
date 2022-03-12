<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get("/", "\Micorksen\CasServer\Http\Controllers\CasController@getIndex");
Route::get("/logout", "\Micorksen\CasServer\Http\Controllers\CasController@getLogout");
Route::get("/login", "\Micorksen\CasServer\Http\Controllers\CasController@getLogin");
Route::post("/login", "\Micorksen\CasServer\Http\Controllers\CasController@postLogin");

// CAS validate
Route::get("/validate", "\Micorksen\CasServer\Http\Controllers\CasController@getValidate");
Route::get("/serviceValidate", "\Micorksen\CasServer\Http\Controllers\CasController@getServiceValidate");
Route::get("/p3/serviceValidate", "\Micorksen\CasServer\Http\Controllers\CasController@getServiceValidate3");